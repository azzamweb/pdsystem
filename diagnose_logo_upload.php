<?php

/**
 * Script untuk mendiagnosis masalah upload logo di production server
 * Jalankan dengan: php diagnose_logo_upload.php
 */

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== DIAGNOSIS LOGO UPLOAD PRODUCTION ===\n\n";

// 1. Check storage directory permissions
echo "1. STORAGE DIRECTORY PERMISSIONS:\n";
echo "================================\n";

$storagePath = storage_path('app/public');
$publicStoragePath = public_path('storage');

echo "Storage path: $storagePath\n";
echo "Public storage path: $publicStoragePath\n\n";

// Check if storage directory exists
if (is_dir($storagePath)) {
    echo "✓ Storage directory exists\n";
    
    // Check permissions
    $perms = fileperms($storagePath);
    echo "Storage permissions: " . substr(sprintf('%o', $perms), -4) . "\n";
    
    if (is_writable($storagePath)) {
        echo "✓ Storage directory is writable\n";
    } else {
        echo "✗ Storage directory is NOT writable\n";
    }
} else {
    echo "✗ Storage directory does not exist\n";
}

// Check if public storage symlink exists
if (is_link($publicStoragePath)) {
    echo "✓ Public storage symlink exists\n";
    echo "Symlink target: " . readlink($publicStoragePath) . "\n";
} elseif (is_dir($publicStoragePath)) {
    echo "⚠ Public storage is a directory (not symlink)\n";
} else {
    echo "✗ Public storage symlink does not exist\n";
}

echo "\n";

// 2. Check logos directory
echo "2. LOGOS DIRECTORY:\n";
echo "==================\n";

$logosPath = $storagePath . '/logos';
echo "Logos path: $logosPath\n";

if (is_dir($logosPath)) {
    echo "✓ Logos directory exists\n";
    
    if (is_writable($logosPath)) {
        echo "✓ Logos directory is writable\n";
    } else {
        echo "✗ Logos directory is NOT writable\n";
    }
    
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
    echo "✗ Logos directory does not exist\n";
    echo "Attempting to create...\n";
    
    if (mkdir($logosPath, 0755, true)) {
        echo "✓ Logos directory created successfully\n";
    } else {
        echo "✗ Failed to create logos directory\n";
    }
}

echo "\n";

// 3. Check PHP configuration
echo "3. PHP CONFIGURATION:\n";
echo "====================\n";

echo "Upload max filesize: " . ini_get('upload_max_filesize') . "\n";
echo "Post max size: " . ini_get('post_max_size') . "\n";
echo "Max execution time: " . ini_get('max_execution_time') . " seconds\n";
echo "Memory limit: " . ini_get('memory_limit') . "\n";
echo "File uploads enabled: " . (ini_get('file_uploads') ? 'Yes' : 'No') . "\n";

echo "\n";

// 4. Check Laravel configuration
echo "4. LARAVEL CONFIGURATION:\n";
echo "========================\n";

echo "App environment: " . app()->environment() . "\n";
echo "App debug: " . (config('app.debug') ? 'Enabled' : 'Disabled') . "\n";
echo "Default filesystem disk: " . config('filesystems.default') . "\n";
echo "Public disk root: " . config('filesystems.disks.public.root') . "\n";
echo "Public disk URL: " . config('filesystems.disks.public.url') . "\n";

echo "\n";

// 5. Test file operations
echo "5. FILE OPERATIONS TEST:\n";
echo "=======================\n";

try {
    // Test creating a test file
    $testFile = $logosPath . '/test_' . time() . '.txt';
    if (file_put_contents($testFile, 'test content')) {
        echo "✓ Can create files in logos directory\n";
        
        // Test reading the file
        if (file_get_contents($testFile) === 'test content') {
            echo "✓ Can read files from logos directory\n";
        } else {
            echo "✗ Cannot read files from logos directory\n";
        }
        
        // Clean up
        unlink($testFile);
        echo "✓ Can delete files from logos directory\n";
    } else {
        echo "✗ Cannot create files in logos directory\n";
    }
} catch (Exception $e) {
    echo "✗ File operations test failed: " . $e->getMessage() . "\n";
}

echo "\n";

// 6. Check current organization settings
echo "6. ORGANIZATION SETTINGS:\n";
echo "========================\n";

try {
    $orgSettings = \App\Models\OrgSettings::getInstance();
    echo "Organization name: " . $orgSettings->name . "\n";
    echo "Logo path: " . ($orgSettings->logo_path ?? 'NULL') . "\n";
    
    if ($orgSettings->logo_path) {
        $fullLogoPath = $storagePath . '/' . $orgSettings->logo_path;
        echo "Full logo path: $fullLogoPath\n";
        
        if (file_exists($fullLogoPath)) {
            echo "✓ Logo file exists\n";
            echo "Logo file size: " . filesize($fullLogoPath) . " bytes\n";
        } else {
            echo "✗ Logo file does not exist\n";
        }
        
        // Test URL generation
        $logoUrl = \Storage::url($orgSettings->logo_path);
        echo "Logo URL: $logoUrl\n";
    }
} catch (Exception $e) {
    echo "✗ Error checking organization settings: " . $e->getMessage() . "\n";
}

echo "\n";

// 7. Recommendations
echo "7. RECOMMENDATIONS:\n";
echo "==================\n";

if (!is_writable($storagePath)) {
    echo "• Fix storage directory permissions: chmod 755 " . $storagePath . "\n";
}

if (!is_link($publicStoragePath)) {
    echo "• Create storage symlink: php artisan storage:link\n";
}

if (!is_dir($logosPath) || !is_writable($logosPath)) {
    echo "• Create/fix logos directory: mkdir -p " . $logosPath . " && chmod 755 " . $logosPath . "\n";
}

$uploadMax = ini_get('upload_max_filesize');
$postMax = ini_get('post_max_size');

if (str_replace(['K', 'M', 'G'], ['000', '000000', '000000000'], $uploadMax) < 2097152) {
    echo "• Increase upload_max_filesize in php.ini (current: $uploadMax, recommended: 2M or higher)\n";
}

if (str_replace(['K', 'M', 'G'], ['000', '000000', '000000000'], $postMax) < 2097152) {
    echo "• Increase post_max_size in php.ini (current: $postMax, recommended: 2M or higher)\n";
}

echo "\n=== DIAGNOSIS COMPLETE ===\n";
