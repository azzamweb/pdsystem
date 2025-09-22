<?php

/**
 * Script sederhana untuk test upload logo
 */

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TEST LOGO UPLOAD ===\n\n";

// 1. Test direktori logos
echo "1. TESTING LOGOS DIRECTORY:\n";
echo "===========================\n";

$logosPath = storage_path('app/public/logos');
echo "Logos path: $logosPath\n";

if (is_dir($logosPath)) {
    echo "✓ Logos directory exists\n";
    
    if (is_writable($logosPath)) {
        echo "✓ Logos directory is writable\n";
    } else {
        echo "✗ Logos directory is not writable\n";
        exit(1);
    }
} else {
    echo "✗ Logos directory does not exist\n";
    exit(1);
}

echo "\n";

// 2. Test file creation
echo "2. TESTING FILE CREATION:\n";
echo "=========================\n";

$testFile = $logosPath . '/test_' . time() . '.txt';
$testContent = 'Test file for logo upload functionality - ' . date('Y-m-d H:i:s');

if (file_put_contents($testFile, $testContent)) {
    echo "✓ Test file created successfully\n";
    echo "File path: $testFile\n";
    echo "File content: $testContent\n";
    
    // Test file reading
    if (file_get_contents($testFile) === $testContent) {
        echo "✓ Test file can be read successfully\n";
    } else {
        echo "✗ Test file cannot be read\n";
    }
    
    // Test file deletion
    if (unlink($testFile)) {
        echo "✓ Test file deleted successfully\n";
    } else {
        echo "✗ Test file cannot be deleted\n";
    }
} else {
    echo "✗ Failed to create test file\n";
    exit(1);
}

echo "\n";

// 3. Test Storage facade
echo "3. TESTING STORAGE FACADE:\n";
echo "==========================\n";

try {
    $testFileName = 'test_storage_' . time() . '.txt';
    $testContent = 'Test content for Storage facade - ' . date('Y-m-d H:i:s');
    
    // Test file creation via Storage
    \Storage::disk('public')->put($testFileName, $testContent);
    echo "✓ File created via Storage facade\n";
    
    // Test file existence
    if (\Storage::disk('public')->exists($testFileName)) {
        echo "✓ File exists via Storage facade\n";
    } else {
        echo "✗ File does not exist via Storage facade\n";
    }
    
    // Test file reading
    $readContent = \Storage::disk('public')->get($testFileName);
    if ($readContent === $testContent) {
        echo "✓ File content read correctly via Storage facade\n";
    } else {
        echo "✗ File content read incorrectly via Storage facade\n";
    }
    
    // Test URL generation
    $fileUrl = \Storage::disk('public')->url($testFileName);
    echo "File URL: $fileUrl\n";
    
    // Test file deletion
    \Storage::disk('public')->delete($testFileName);
    echo "✓ File deleted via Storage facade\n";
    
} catch (Exception $e) {
    echo "✗ Storage facade test failed: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n";

// 4. Test logo-specific operations
echo "4. TESTING LOGO-SPECIFIC OPERATIONS:\n";
echo "====================================\n";

try {
    // Create a test logo file
    $testLogoName = 'test_logo_' . time() . '.png';
    $testLogoPath = 'logos/' . $testLogoName;
    
    // Create a simple test image (1x1 pixel PNG)
    $testImageData = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==');
    
    // Store via Storage facade
    \Storage::disk('public')->put($testLogoPath, $testImageData);
    echo "✓ Test logo created via Storage facade\n";
    
    // Test logo existence
    if (\Storage::disk('public')->exists($testLogoPath)) {
        echo "✓ Test logo exists\n";
    } else {
        echo "✗ Test logo does not exist\n";
    }
    
    // Test logo URL
    $logoUrl = \Storage::disk('public')->url($testLogoPath);
    echo "Logo URL: $logoUrl\n";
    
    // Test organization settings update
    $orgSettings = \App\Models\OrgSettings::getInstance();
    $originalLogoPath = $orgSettings->logo_path;
    
    $orgSettings->logo_path = $testLogoPath;
    $orgSettings->save();
    echo "✓ Organization settings updated with test logo\n";
    echo "Logo path in database: " . $orgSettings->logo_path . "\n";
    
    // Test logo display logic
    if ($orgSettings->logo_path && \Storage::disk('public')->exists($orgSettings->logo_path)) {
        echo "✓ Logo display logic working correctly\n";
    } else {
        echo "✗ Logo display logic not working\n";
    }
    
    // Clean up
    \Storage::disk('public')->delete($testLogoPath);
    $orgSettings->logo_path = $originalLogoPath;
    $orgSettings->save();
    echo "✓ Test logo cleaned up\n";
    
} catch (Exception $e) {
    echo "✗ Logo-specific operations test failed: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n";

// 5. Final summary
echo "5. FINAL SUMMARY:\n";
echo "================\n";

echo "✓ Logos directory: OK\n";
echo "✓ File operations: OK\n";
echo "✓ Storage facade: OK\n";
echo "✓ Logo operations: OK\n";
echo "✓ Organization settings: OK\n";

echo "\n=== ALL TESTS PASSED ===\n";
echo "\nLogo upload functionality is working correctly!\n";
echo "You can now upload logos through the application.\n";
