<?php

/**
 * Script untuk memperbaiki masalah upload logo di production server
 * Jalankan dengan: php fix_logo_upload.php
 */

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== FIXING LOGO UPLOAD ISSUES ===\n\n";

// 1. Create storage directories
echo "1. CREATING STORAGE DIRECTORIES:\n";
echo "================================\n";

$storagePath = storage_path('app/public');
$logosPath = $storagePath . '/logos';
$signaturesPath = $storagePath . '/signatures';
$stampsPath = $storagePath . '/stamps';

$directories = [
    $storagePath => 'Storage public directory',
    $logosPath => 'Logos directory',
    $signaturesPath => 'Signatures directory',
    $stampsPath => 'Stamps directory'
];

foreach ($directories as $dir => $description) {
    if (!is_dir($dir)) {
        if (mkdir($dir, 0755, true)) {
            echo "✓ Created: $description ($dir)\n";
        } else {
            echo "✗ Failed to create: $description ($dir)\n";
        }
    } else {
        echo "✓ Exists: $description ($dir)\n";
    }
}

echo "\n";

// 2. Fix directory permissions
echo "2. FIXING DIRECTORY PERMISSIONS:\n";
echo "================================\n";

foreach ($directories as $dir => $description) {
    if (is_dir($dir)) {
        if (chmod($dir, 0755)) {
            echo "✓ Fixed permissions: $description\n";
        } else {
            echo "✗ Failed to fix permissions: $description\n";
        }
    }
}

echo "\n";

// 3. Create storage symlink
echo "3. CREATING STORAGE SYMLINK:\n";
echo "============================\n";

$publicStoragePath = public_path('storage');

// Remove existing directory/symlink if it exists
if (is_link($publicStoragePath)) {
    if (unlink($publicStoragePath)) {
        echo "✓ Removed existing symlink\n";
    } else {
        echo "✗ Failed to remove existing symlink\n";
    }
} elseif (is_dir($publicStoragePath)) {
    if (rmdir($publicStoragePath)) {
        echo "✓ Removed existing directory\n";
    } else {
        echo "✗ Failed to remove existing directory (may contain files)\n";
    }
}

// Create new symlink
if (symlink($storagePath, $publicStoragePath)) {
    echo "✓ Created storage symlink\n";
} else {
    echo "✗ Failed to create storage symlink\n";
}

echo "\n";

// 4. Test file operations
echo "4. TESTING FILE OPERATIONS:\n";
echo "===========================\n";

try {
    // Test creating a file
    $testFile = $logosPath . '/test_' . time() . '.txt';
    $testContent = 'Test file for logo upload functionality';
    
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
    echo "✗ File operations test failed: " . $e->getMessage() . "\n";
}

echo "\n";

// 5. Clear Laravel caches
echo "5. CLEARING LARAVEL CACHES:\n";
echo "===========================\n";

try {
    \Artisan::call('config:clear');
    echo "✓ Configuration cache cleared\n";
    
    \Artisan::call('cache:clear');
    echo "✓ Application cache cleared\n";
    
    \Artisan::call('view:clear');
    echo "✓ View cache cleared\n";
    
    \Artisan::call('route:clear');
    echo "✓ Route cache cleared\n";
} catch (Exception $e) {
    echo "✗ Failed to clear caches: " . $e->getMessage() . "\n";
}

echo "\n";

// 6. Verify configuration
echo "6. VERIFYING CONFIGURATION:\n";
echo "===========================\n";

echo "App environment: " . app()->environment() . "\n";
echo "Default filesystem disk: " . config('filesystems.default') . "\n";
echo "Public disk root: " . config('filesystems.disks.public.root') . "\n";
echo "Public disk URL: " . config('filesystems.disks.public.url') . "\n";

// Test Storage facade
try {
    $testPath = 'test_' . time() . '.txt';
    \Storage::disk('public')->put($testPath, 'test content');
    
    if (\Storage::disk('public')->exists($testPath)) {
        echo "✓ Storage facade working correctly\n";
        \Storage::disk('public')->delete($testPath);
    } else {
        echo "✗ Storage facade not working correctly\n";
    }
} catch (Exception $e) {
    echo "✗ Storage facade test failed: " . $e->getMessage() . "\n";
}

echo "\n";

// 7. Final recommendations
echo "7. FINAL RECOMMENDATIONS:\n";
echo "========================\n";

echo "• Ensure web server (Apache/Nginx) has write permissions to storage directories\n";
echo "• Check PHP configuration for file upload limits\n";
echo "• Verify that the storage symlink is accessible via web browser\n";
echo "• Test logo upload functionality in the application\n";
echo "• Monitor application logs for any errors during file upload\n";

echo "\n=== FIX COMPLETE ===\n";
