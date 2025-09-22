<?php

/**
 * Script untuk test upload logo secara manual
 */

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== MANUAL LOGO UPLOAD TEST ===\n\n";

// 1. Check if logo file exists
echo "1. CHECKING FOR LOGO FILES:\n";
echo "===========================\n";

$logosPath = storage_path('app/public/logos');
$logoFiles = [];

if (is_dir($logosPath)) {
    $files = scandir($logosPath);
    foreach ($files as $file) {
        if (!in_array($file, ['.', '..']) && is_file($logosPath . '/' . $file)) {
            $logoFiles[] = $file;
        }
    }
}

if (empty($logoFiles)) {
    echo "⚠ No logo files found in $logosPath\n";
    echo "Creating a test logo file...\n";
    
    // Create a test logo file
    $testLogoPath = $logosPath . '/test_logo_' . time() . '.png';
    
    // Create a simple test image (1x1 pixel PNG)
    $testImageData = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==');
    
    if (file_put_contents($testLogoPath, $testImageData)) {
        echo "✓ Test logo file created: " . basename($testLogoPath) . "\n";
        $logoFiles[] = basename($testLogoPath);
    } else {
        echo "✗ Failed to create test logo file\n";
        exit(1);
    }
} else {
    echo "Found " . count($logoFiles) . " logo file(s):\n";
    foreach ($logoFiles as $index => $file) {
        echo "  " . ($index + 1) . ". $file\n";
    }
}

echo "\n";

// 2. Select logo file
echo "2. SELECTING LOGO FILE:\n";
echo "======================\n";

if (count($logoFiles) === 1) {
    $selectedLogo = $logoFiles[0];
    echo "Auto-selecting: $selectedLogo\n";
} else {
    // Get the most recent file
    $mostRecentFile = null;
    $mostRecentTime = 0;
    
    foreach ($logoFiles as $file) {
        $filePath = $logosPath . '/' . $file;
        $fileTime = filemtime($filePath);
        
        if ($fileTime > $mostRecentTime) {
            $mostRecentTime = $fileTime;
            $mostRecentFile = $file;
        }
    }
    
    $selectedLogo = $mostRecentFile;
    echo "Auto-selecting most recent: $selectedLogo\n";
}

$logoPath = 'logos/' . $selectedLogo;
echo "Logo path: $logoPath\n";

echo "\n";

// 3. Test logo file
echo "3. TESTING LOGO FILE:\n";
echo "=====================\n";

$fullLogoPath = storage_path('app/public/' . $logoPath);
if (file_exists($fullLogoPath)) {
    echo "✓ Logo file exists: $fullLogoPath\n";
    echo "File size: " . filesize($fullLogoPath) . " bytes\n";
    echo "File type: " . mime_content_type($fullLogoPath) . "\n";
    echo "File modified: " . date('Y-m-d H:i:s', filemtime($fullLogoPath)) . "\n";
} else {
    echo "✗ Logo file does not exist: $fullLogoPath\n";
    exit(1);
}

echo "\n";

// 4. Test Storage facade
echo "4. TESTING STORAGE FACADE:\n";
echo "==========================\n";

try {
    // Test if file exists via Storage
    if (\Storage::disk('public')->exists($logoPath)) {
        echo "✓ Logo file exists via Storage facade\n";
    } else {
        echo "✗ Logo file does not exist via Storage facade\n";
    }
    
    // Test URL generation
    $logoUrl = \Storage::disk('public')->url($logoPath);
    echo "Logo URL: $logoUrl\n";
    
    // Test file reading
    $fileContent = \Storage::disk('public')->get($logoPath);
    if ($fileContent) {
        echo "✓ Can read logo file via Storage facade\n";
        echo "File content size: " . strlen($fileContent) . " bytes\n";
    } else {
        echo "✗ Cannot read logo file via Storage facade\n";
    }
    
} catch (Exception $e) {
    echo "✗ Storage facade test failed: " . $e->getMessage() . "\n";
}

echo "\n";

// 5. Update organization settings
echo "5. UPDATING ORGANIZATION SETTINGS:\n";
echo "==================================\n";

try {
    $orgSettings = \App\Models\OrgSettings::getInstance();
    $originalLogoPath = $orgSettings->logo_path;
    
    // Update logo path
    $orgSettings->logo_path = $logoPath;
    $orgSettings->save();
    
    echo "✓ Logo path updated in database\n";
    echo "Previous logo path: " . ($originalLogoPath ?? 'NULL') . "\n";
    echo "New logo path: $logoPath\n";
    
    // Verify update
    $updatedSettings = \App\Models\OrgSettings::getInstance();
    if ($updatedSettings->logo_path === $logoPath) {
        echo "✓ Database update verified\n";
    } else {
        echo "✗ Database update verification failed\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error updating organization settings: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n";

// 6. Test logo display
echo "6. TESTING LOGO DISPLAY:\n";
echo "========================\n";

try {
    $orgSettings = \App\Models\OrgSettings::getInstance();
    
    // Test logo display logic
    if ($orgSettings->logo_path && \Storage::disk('public')->exists($orgSettings->logo_path)) {
        echo "✓ Logo display logic working correctly\n";
        echo "Logo will be displayed in sidebar and welcome page\n";
    } else {
        echo "✗ Logo display logic not working\n";
    }
    
    // Test dynamic logo component
    $view = view('components.dynamic-app-logo');
    $html = $view->render();
    
    if (strpos($html, $logoPath) !== false) {
        echo "✓ Dynamic logo component working correctly\n";
    } else {
        echo "⚠ Dynamic logo component may not be working\n";
    }
    
    // Test welcome page logo
    $welcomeView = view('welcome');
    $welcomeHtml = $welcomeView->render();
    
    if (strpos($welcomeHtml, $logoPath) !== false) {
        echo "✓ Welcome page logo working correctly\n";
    } else {
        echo "⚠ Welcome page logo may not be working\n";
    }
    
} catch (Exception $e) {
    echo "✗ Logo display test failed: " . $e->getMessage() . "\n";
}

echo "\n";

// 7. Clear cache
echo "7. CLEARING CACHE:\n";
echo "==================\n";

try {
    \Artisan::call('cache:clear');
    echo "✓ Cache cleared\n";
    
    \Artisan::call('config:clear');
    echo "✓ Config cache cleared\n";
    
    \Artisan::call('view:clear');
    echo "✓ View cache cleared\n";
    
} catch (Exception $e) {
    echo "✗ Error clearing cache: " . $e->getMessage() . "\n";
}

echo "\n";

// 8. Final summary
echo "8. FINAL SUMMARY:\n";
echo "=================\n";

$orgSettings = \App\Models\OrgSettings::getInstance();
echo "Organization: " . $orgSettings->name . "\n";
echo "Logo path: " . $orgSettings->logo_path . "\n";
echo "Logo URL: " . \Storage::disk('public')->url($orgSettings->logo_path) . "\n";
echo "Logo file exists: " . (\Storage::disk('public')->exists($orgSettings->logo_path) ? 'YES' : 'NO') . "\n";

echo "\n=== MANUAL LOGO UPLOAD TEST COMPLETE ===\n";
echo "\nLogo should now be displayed in the application!\n";
echo "Check the sidebar and welcome page to verify.\n";
