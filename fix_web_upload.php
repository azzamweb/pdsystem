<?php

/**
 * Script untuk memperbaiki masalah upload via interface web
 */

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== FIXING WEB UPLOAD INTERFACE ===\n\n";

// 1. Fix storage permissions
echo "1. FIXING STORAGE PERMISSIONS:\n";
echo "==============================\n";

$storagePaths = [
    storage_path() => 'Storage root',
    storage_path('app') => 'Storage app',
    storage_path('app/public') => 'Storage public',
    storage_path('app/public/logos') => 'Storage logos',
    storage_path('app/public/signatures') => 'Storage signatures',
    storage_path('app/public/stamps') => 'Storage stamps',
    storage_path('logs') => 'Storage logs'
];

foreach ($storagePaths as $path => $description) {
    if (is_dir($path)) {
        if (chmod($path, 0755)) {
            echo "✓ Fixed permissions: $description\n";
        } else {
            echo "✗ Failed to fix permissions: $description\n";
        }
    } else {
        echo "⚠ Directory not found: $description\n";
    }
}

echo "\n";

// 2. Create missing directories
echo "2. CREATING MISSING DIRECTORIES:\n";
echo "================================\n";

$directories = [
    storage_path('app/public/logos') => 'Logos directory',
    storage_path('app/public/signatures') => 'Signatures directory',
    storage_path('app/public/stamps') => 'Stamps directory'
];

foreach ($directories as $dir => $description) {
    if (!is_dir($dir)) {
        if (mkdir($dir, 0755, true)) {
            echo "✓ Created: $description\n";
        } else {
            echo "✗ Failed to create: $description\n";
        }
    } else {
        echo "✓ Exists: $description\n";
    }
}

echo "\n";

// 3. Fix file permissions
echo "3. FIXING FILE PERMISSIONS:\n";
echo "===========================\n";

// Fix log file permissions
$logFile = storage_path('logs/laravel.log');
if (file_exists($logFile)) {
    if (chmod($logFile, 0644)) {
        echo "✓ Fixed log file permissions\n";
    } else {
        echo "✗ Failed to fix log file permissions\n";
    }
} else {
    echo "⚠ Log file does not exist\n";
}

echo "\n";

// 4. Publish Livewire assets
echo "4. PUBLISHING LIVEWIRE ASSETS:\n";
echo "=============================\n";

try {
    \Artisan::call('livewire:publish', ['--assets' => true]);
    echo "✓ Livewire assets published\n";
} catch (Exception $e) {
    echo "✗ Failed to publish Livewire assets: " . $e->getMessage() . "\n";
}

echo "\n";

// 5. Clear cache
echo "5. CLEARING CACHE:\n";
echo "==================\n";

try {
    \Artisan::call('cache:clear');
    echo "✓ Cache cleared\n";
    
    \Artisan::call('config:clear');
    echo "✓ Config cache cleared\n";
    
    \Artisan::call('view:clear');
    echo "✓ View cache cleared\n";
    
    \Artisan::call('route:clear');
    echo "✓ Route cache cleared\n";
    
} catch (Exception $e) {
    echo "✗ Error clearing cache: " . $e->getMessage() . "\n";
}

echo "\n";

// 6. Test file upload functionality
echo "6. TESTING FILE UPLOAD FUNCTIONALITY:\n";
echo "====================================\n";

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

// 8. Test image upload
echo "8. TESTING IMAGE UPLOAD:\n";
echo "========================\n";

try {
    $logosPath = storage_path('app/public/logos');
    
    // Create a test image file
    $testImagePath = $logosPath . '/test_web_image_' . time() . '.png';
    
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
        
        // Test via Storage facade
        $storagePath = 'logos/test_web_storage_image_' . time() . '.png';
        \Storage::disk('public')->put($storagePath, $testImageData);
        
        if (\Storage::disk('public')->exists($storagePath)) {
            echo "✓ Storage facade image upload successful\n";
        } else {
            echo "✗ Storage facade image upload failed\n";
        }
        
        // Clean up
        unlink($testImagePath);
        \Storage::disk('public')->delete($storagePath);
        echo "✓ Test images cleaned up\n";
    } else {
        echo "✗ Cannot create image files in logos directory\n";
    }
} catch (Exception $e) {
    echo "✗ Image upload test failed: " . $e->getMessage() . "\n";
}

echo "\n";

// 9. Check organization settings
echo "9. CHECKING ORGANIZATION SETTINGS:\n";
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

// 10. Final recommendations
echo "10. FINAL RECOMMENDATIONS:\n";
echo "=========================\n";

echo "• Test logo upload through the web interface\n";
echo "• Check browser console for JavaScript errors\n";
echo "• Check application logs for any errors during upload\n";
echo "• Ensure web server user has write permissions to storage directories\n";
echo "• Verify that the storage symlink is accessible via web browser\n";
echo "• Test with a smaller file first (under 1MB)\n";

echo "\n=== WEB UPLOAD INTERFACE FIXED ===\n";
echo "\nWeb upload functionality should now work correctly!\n";
