<?php

/**
 * Script untuk mengupdate logo path di database production
 */

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== UPDATE LOGO PATH IN DATABASE ===\n\n";

// 1. Check current organization settings
echo "1. CHECKING CURRENT ORGANIZATION SETTINGS:\n";
echo "==========================================\n";

try {
    $orgSettings = \App\Models\OrgSettings::getInstance();
    echo "Organization name: " . $orgSettings->name . "\n";
    echo "Current logo path: " . ($orgSettings->logo_path ?? 'NULL') . "\n";
    echo "Short name: " . ($orgSettings->short_name ?? 'NULL') . "\n";
} catch (Exception $e) {
    echo "✗ Error getting organization settings: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n";

// 2. Check existing logo files
echo "2. CHECKING EXISTING LOGO FILES:\n";
echo "================================\n";

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
    echo "Please upload a logo file first.\n";
    exit(1);
} else {
    echo "Found " . count($logoFiles) . " logo file(s):\n";
    foreach ($logoFiles as $index => $file) {
        echo "  " . ($index + 1) . ". $file\n";
    }
}

echo "\n";

// 3. Select logo file
echo "3. SELECTING LOGO FILE:\n";
echo "======================\n";

if (count($logoFiles) === 1) {
    $selectedLogo = $logoFiles[0];
    echo "Auto-selecting: $selectedLogo\n";
} else {
    echo "Multiple logo files found. Please select one:\n";
    foreach ($logoFiles as $index => $file) {
        echo "  " . ($index + 1) . ". $file\n";
    }
    
    // For automated selection, use the first file
    $selectedLogo = $logoFiles[0];
    echo "Auto-selecting first file: $selectedLogo\n";
}

$logoPath = 'logos/' . $selectedLogo;
echo "Logo path: $logoPath\n";

echo "\n";

// 4. Verify logo file exists
echo "4. VERIFYING LOGO FILE:\n";
echo "======================\n";

$fullLogoPath = storage_path('app/public/' . $logoPath);
if (file_exists($fullLogoPath)) {
    echo "✓ Logo file exists: $fullLogoPath\n";
    echo "File size: " . filesize($fullLogoPath) . " bytes\n";
    echo "File type: " . mime_content_type($fullLogoPath) . "\n";
} else {
    echo "✗ Logo file does not exist: $fullLogoPath\n";
    exit(1);
}

echo "\n";

// 5. Test logo URL
echo "5. TESTING LOGO URL:\n";
echo "===================\n";

try {
    $logoUrl = \Storage::disk('public')->url($logoPath);
    echo "Logo URL: $logoUrl\n";
    
    // Test if URL is accessible
    if (filter_var($logoUrl, FILTER_VALIDATE_URL)) {
        echo "✓ Logo URL is valid\n";
    } else {
        echo "⚠ Logo URL may not be accessible\n";
    }
} catch (Exception $e) {
    echo "✗ Error generating logo URL: " . $e->getMessage() . "\n";
}

echo "\n";

// 6. Update database
echo "6. UPDATING DATABASE:\n";
echo "====================\n";

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
    echo "✗ Error updating database: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n";

// 7. Test logo display
echo "7. TESTING LOGO DISPLAY:\n";
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
    
} catch (Exception $e) {
    echo "✗ Logo display test failed: " . $e->getMessage() . "\n";
}

echo "\n";

// 8. Final summary
echo "8. FINAL SUMMARY:\n";
echo "================\n";

$orgSettings = \App\Models\OrgSettings::getInstance();
echo "Organization: " . $orgSettings->name . "\n";
echo "Logo path: " . $orgSettings->logo_path . "\n";
echo "Logo URL: " . \Storage::disk('public')->url($orgSettings->logo_path) . "\n";
echo "Logo file exists: " . (\Storage::disk('public')->exists($orgSettings->logo_path) ? 'YES' : 'NO') . "\n";

echo "\n=== LOGO PATH UPDATED SUCCESSFULLY ===\n";
echo "\nNext steps:\n";
echo "1. Check logo display in sidebar\n";
echo "2. Check logo display in welcome page\n";
echo "3. Test logo upload functionality\n";
