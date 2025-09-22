<?php

/**
 * Script untuk debug masalah upload via interface web
 */

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== DEBUG WEB UPLOAD INTERFACE ===\n\n";

// 1. Check Livewire configuration
echo "1. CHECKING LIVEWIRE CONFIGURATION:\n";
echo "===================================\n";

echo "Livewire version: " . \Livewire\Livewire::VERSION . "\n";
echo "Livewire assets published: " . (file_exists(public_path('livewire/livewire.js')) ? 'YES' : 'NO') . "\n";

// Check if Livewire is properly configured
try {
    $livewireConfig = config('livewire');
    echo "Livewire config loaded: " . (is_array($livewireConfig) ? 'YES' : 'NO') . "\n";
} catch (Exception $e) {
    echo "✗ Livewire config error: " . $e->getMessage() . "\n";
}

echo "\n";

// 2. Check file upload configuration
echo "2. CHECKING FILE UPLOAD CONFIGURATION:\n";
echo "======================================\n";

echo "Upload max filesize: " . ini_get('upload_max_filesize') . "\n";
echo "Post max size: " . ini_get('post_max_size') . "\n";
echo "Max execution time: " . ini_get('max_execution_time') . " seconds\n";
echo "Memory limit: " . ini_get('memory_limit') . "\n";
echo "File uploads enabled: " . (ini_get('file_uploads') ? 'Yes' : 'No') . "\n";
echo "Max input vars: " . ini_get('max_input_vars') . "\n";
echo "Max input time: " . ini_get('max_input_time') . " seconds\n";

echo "\n";

// 3. Check storage configuration
echo "3. CHECKING STORAGE CONFIGURATION:\n";
echo "===================================\n";

echo "Default filesystem disk: " . config('filesystems.default') . "\n";
echo "Public disk root: " . config('filesystems.disks.public.root') . "\n";
echo "Public disk URL: " . config('filesystems.disks.public.url') . "\n";

$storagePath = storage_path('app/public');
echo "Storage path: $storagePath\n";
echo "Storage exists: " . (is_dir($storagePath) ? 'YES' : 'NO') . "\n";
echo "Storage writable: " . (is_writable($storagePath) ? 'YES' : 'NO') . "\n";

$logosPath = storage_path('app/public/logos');
echo "Logos path: $logosPath\n";
echo "Logos directory exists: " . (is_dir($logosPath) ? 'YES' : 'NO') . "\n";
echo "Logos directory writable: " . (is_writable($logosPath) ? 'YES' : 'NO') . "\n";

echo "\n";

// 4. Check web server configuration
echo "4. CHECKING WEB SERVER CONFIGURATION:\n";
echo "=====================================\n";

echo "Server software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "\n";
echo "Document root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Unknown') . "\n";
echo "Script filename: " . ($_SERVER['SCRIPT_FILENAME'] ?? 'Unknown') . "\n";

// Check if running as web server user
$currentUser = get_current_user();
echo "Current user: $currentUser\n";

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

// 5. Check Livewire file upload component
echo "5. CHECKING LIVEWIRE FILE UPLOAD COMPONENT:\n";
echo "===========================================\n";

try {
    // Check if OrganizationSettings component exists
    $componentPath = app_path('Livewire/Settings/OrganizationSettings.php');
    if (file_exists($componentPath)) {
        echo "✓ OrganizationSettings component exists\n";
        
        // Check if component uses WithFileUploads trait
        $componentContent = file_get_contents($componentPath);
        if (strpos($componentContent, 'WithFileUploads') !== false) {
            echo "✓ OrganizationSettings uses WithFileUploads trait\n";
        } else {
            echo "✗ OrganizationSettings does not use WithFileUploads trait\n";
        }
        
        if (strpos($componentContent, 'logo_file') !== false) {
            echo "✓ OrganizationSettings has logo_file property\n";
        } else {
            echo "✗ OrganizationSettings does not have logo_file property\n";
        }
        
        if (strpos($componentContent, 'store(') !== false) {
            echo "✓ OrganizationSettings has store method\n";
        } else {
            echo "✗ OrganizationSettings does not have store method\n";
        }
    } else {
        echo "✗ OrganizationSettings component does not exist\n";
    }
} catch (Exception $e) {
    echo "✗ Error checking OrganizationSettings component: " . $e->getMessage() . "\n";
}

echo "\n";

// 6. Test file upload functionality
echo "6. TESTING FILE UPLOAD FUNCTIONALITY:\n";
echo "=====================================\n";

try {
    $logosPath = storage_path('app/public/logos');
    
    // Test creating a file
    $testFile = $logosPath . '/test_web_upload_' . time() . '.txt';
    $testContent = 'Test web upload functionality - ' . date('Y-m-d H:i:s');
    
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

// 7. Test Storage facade
echo "7. TESTING STORAGE FACADE:\n";
echo "==========================\n";

try {
    $testFileName = 'test_web_storage_' . time() . '.txt';
    $testContent = 'Test web Storage facade - ' . date('Y-m-d H:i:s');
    
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

// 8. Check organization settings
echo "8. CHECKING ORGANIZATION SETTINGS:\n";
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

// 9. Check application logs
echo "9. CHECKING APPLICATION LOGS:\n";
echo "=============================\n";

$logFile = storage_path('logs/laravel.log');
if (file_exists($logFile)) {
    echo "Log file exists: $logFile\n";
    echo "Log file size: " . filesize($logFile) . " bytes\n";
    echo "Log file readable: " . (is_readable($logFile) ? 'YES' : 'NO') . "\n";
    
    // Get last 10 lines of log
    $logLines = file($logFile);
    $lastLines = array_slice($logLines, -10);
    echo "Last 10 log entries:\n";
    foreach ($lastLines as $line) {
        echo "  " . trim($line) . "\n";
    }
} else {
    echo "✗ Log file does not exist\n";
}

echo "\n";

// 10. Recommendations
echo "10. RECOMMENDATIONS:\n";
echo "====================\n";

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

echo "• Check web server error logs for upload errors\n";
echo "• Ensure Livewire assets are published: php artisan livewire:publish --assets\n";
echo "• Test upload with a smaller file first\n";
echo "• Check browser console for JavaScript errors\n";

echo "\n=== WEB UPLOAD DEBUG COMPLETE ===\n";
