<?php

/**
 * Script untuk debug masalah upload logo di production
 */

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== DEBUG LOGO UPLOAD ISSUES ===\n\n";

// 1. Check storage configuration
echo "1. CHECKING STORAGE CONFIGURATION:\n";
echo "==================================\n";

echo "Default filesystem disk: " . config('filesystems.default') . "\n";
echo "Public disk root: " . config('filesystems.disks.public.root') . "\n";
echo "Public disk URL: " . config('filesystems.disks.public.url') . "\n";

$storagePath = storage_path('app/public');
echo "Storage path: $storagePath\n";
echo "Storage exists: " . (is_dir($storagePath) ? 'YES' : 'NO') . "\n";
echo "Storage writable: " . (is_writable($storagePath) ? 'YES' : 'NO') . "\n";

echo "\n";

// 2. Check logos directory
echo "2. CHECKING LOGOS DIRECTORY:\n";
echo "============================\n";

$logosPath = storage_path('app/public/logos');
echo "Logos path: $logosPath\n";
echo "Logos directory exists: " . (is_dir($logosPath) ? 'YES' : 'NO') . "\n";

if (is_dir($logosPath)) {
    echo "Logos directory writable: " . (is_writable($logosPath) ? 'YES' : 'NO') . "\n";
    
    // List existing files
    $files = scandir($logosPath);
    $logoFiles = array_filter($files, function($file) {
        return !in_array($file, ['.', '..']) && is_file($logosPath . '/' . $file);
    });
    
    echo "Existing logo files: " . count($logoFiles) . "\n";
    foreach ($logoFiles as $file) {
        echo "  - $file\n";
    }
} else {
    echo "⚠ Logos directory does not exist\n";
}

echo "\n";

// 3. Check file upload configuration
echo "3. CHECKING FILE UPLOAD CONFIGURATION:\n";
echo "======================================\n";

echo "Upload max filesize: " . ini_get('upload_max_filesize') . "\n";
echo "Post max size: " . ini_get('post_max_size') . "\n";
echo "Max execution time: " . ini_get('max_execution_time') . " seconds\n";
echo "Memory limit: " . ini_get('memory_limit') . "\n";
echo "File uploads enabled: " . (ini_get('file_uploads') ? 'Yes' : 'No') . "\n";

echo "\n";

// 4. Test file upload functionality
echo "4. TESTING FILE UPLOAD FUNCTIONALITY:\n";
echo "====================================\n";

try {
    // Test creating a file in logos directory
    $testFile = $logosPath . '/test_upload_' . time() . '.txt';
    $testContent = 'Test upload functionality - ' . date('Y-m-d H:i:s');
    
    if (file_put_contents($testFile, $testContent)) {
        echo "✓ Can create files in logos directory\n";
        
        // Test reading the file
        if (file_get_contents($testFile) === $testContent) {
            echo "✓ Can read files from logos directory\n";
        } else {
            echo "✗ Cannot read files from logos directory\n";
        }
        
        // Test deleting the file
        if (unlink($testFile)) {
            echo "✓ Can delete files from logos directory\n";
        } else {
            echo "✗ Cannot delete files from logos directory\n";
        }
    } else {
        echo "✗ Cannot create files in logos directory\n";
    }
} catch (Exception $e) {
    echo "✗ File upload test failed: " . $e->getMessage() . "\n";
}

echo "\n";

// 5. Test Storage facade
echo "5. TESTING STORAGE FACADE:\n";
echo "==========================\n";

try {
    $testFileName = 'test_storage_' . time() . '.txt';
    $testContent = 'Test Storage facade - ' . date('Y-m-d H:i:s');
    
    // Test file creation via Storage
    \Storage::disk('public')->put($testFileName, $testContent);
    echo "✓ Storage facade put successful\n";
    
    // Test file existence
    if (\Storage::disk('public')->exists($testFileName)) {
        echo "✓ Storage facade exists check successful\n";
    } else {
        echo "✗ Storage facade exists check failed\n";
    }
    
    // Test file reading
    $readContent = \Storage::disk('public')->get($testFileName);
    if ($readContent === $testContent) {
        echo "✓ Storage facade get successful\n";
    } else {
        echo "✗ Storage facade get failed\n";
    }
    
    // Test file deletion
    \Storage::disk('public')->delete($testFileName);
    echo "✓ Storage facade delete successful\n";
    
} catch (Exception $e) {
    echo "✗ Storage facade test failed: " . $e->getMessage() . "\n";
}

echo "\n";

// 6. Test Livewire file upload
echo "6. TESTING LIVEWIRE FILE UPLOAD:\n";
echo "================================\n";

try {
    // Test creating a test image file
    $testImagePath = $logosPath . '/test_image_' . time() . '.png';
    
    // Create a simple test image (1x1 pixel PNG)
    $testImageData = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==');
    
    if (file_put_contents($testImagePath, $testImageData)) {
        echo "✓ Can create image files in logos directory\n";
        
        // Test file size
        $fileSize = filesize($testImagePath);
        echo "Test image file size: $fileSize bytes\n";
        
        // Test mime type
        $mimeType = mime_content_type($testImagePath);
        echo "Test image mime type: $mimeType\n";
        
        // Clean up
        unlink($testImagePath);
        echo "✓ Test image cleaned up\n";
    } else {
        echo "✗ Cannot create image files in logos directory\n";
    }
} catch (Exception $e) {
    echo "✗ Livewire file upload test failed: " . $e->getMessage() . "\n";
}

echo "\n";

// 7. Check organization settings
echo "7. CHECKING ORGANIZATION SETTINGS:\n";
echo "==================================\n";

try {
    $orgSettings = \App\Models\OrgSettings::getInstance();
    echo "✓ Organization settings model working\n";
    echo "Organization: " . $orgSettings->name . "\n";
    echo "Current logo path: " . ($orgSettings->logo_path ?? 'NULL') . "\n";
    
    if ($orgSettings->logo_path) {
        if (\Storage::disk('public')->exists($orgSettings->logo_path)) {
            echo "✓ Logo file exists and is accessible\n";
        } else {
            echo "✗ Logo file does not exist or is not accessible\n";
        }
    }
} catch (Exception $e) {
    echo "✗ Organization settings check failed: " . $e->getMessage() . "\n";
}

echo "\n";

// 8. Check web server permissions
echo "8. CHECKING WEB SERVER PERMISSIONS:\n";
echo "===================================\n";

$currentUser = get_current_user();
echo "Current user: $currentUser\n";

// Check if running as web server user
$webServerUsers = ['www-data', 'nginx', 'apache', 'httpd'];
if (in_array($currentUser, $webServerUsers)) {
    echo "✓ Running as web server user\n";
} else {
    echo "⚠ Not running as web server user\n";
}

// Check file permissions
$permissions = [
    'storage/' => 'Storage root',
    'storage/app/' => 'Storage app',
    'storage/app/public/' => 'Storage public',
    'storage/app/public/logos/' => 'Storage logos',
    'storage/logs/' => 'Storage logs'
];

foreach ($permissions as $path => $description) {
    $fullPath = base_path($path);
    if (is_dir($fullPath)) {
        $perms = fileperms($fullPath);
        $readable = is_readable($fullPath);
        $writable = is_writable($fullPath);
        
        echo "$description: " . substr(sprintf('%o', $perms), -4) . " (R:$readable W:$writable)\n";
    } else {
        echo "$description: NOT EXISTS\n";
    }
}

echo "\n";

// 9. Recommendations
echo "9. RECOMMENDATIONS:\n";
echo "===================\n";

if (!is_writable($logosPath)) {
    echo "• Fix logos directory permissions: chmod 755 $logosPath\n";
}

if (!is_writable($storagePath)) {
    echo "• Fix storage directory permissions: chmod 755 $storagePath\n";
}

$uploadMax = ini_get('upload_max_filesize');
if (str_replace(['K', 'M', 'G'], ['000', '000000', '000000000'], $uploadMax) < 2097152) {
    echo "• Increase upload_max_filesize in php.ini (current: $uploadMax, recommended: 2M or higher)\n";
}

echo "• Ensure web server user has write permissions to storage directories\n";
echo "• Check application logs for file upload errors\n";
echo "• Test logo upload through the web interface\n";

echo "\n=== DEBUG COMPLETE ===\n";
