<?php

/**
 * Script untuk mengupdate logo path secara manual
 */

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== MANUAL LOGO PATH UPDATE ===\n\n";

// 1. Check existing logo files
echo "1. CHECKING EXISTING LOGO FILES:\n";
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

// 2. Get the most recent logo file
echo "2. SELECTING MOST RECENT LOGO FILE:\n";
echo "===================================\n";

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

if ($mostRecentFile) {
    echo "Most recent logo file: $mostRecentFile\n";
    echo "File modified: " . date('Y-m-d H:i:s', $mostRecentTime) . "\n";
} else {
    echo "✗ No logo files found\n";
    exit(1);
}

$logoPath = 'logos/' . $mostRecentFile;
echo "Logo path: $logoPath\n";

echo "\n";

// 3. Update database directly
echo "3. UPDATING DATABASE DIRECTLY:\n";
echo "==============================\n";

try {
    // Update database directly
    \DB::table('org_settings')->update(['logo_path' => $logoPath]);
    
    echo "✓ Database updated directly\n";
    echo "Logo path set to: $logoPath\n";
    
    // Verify update
    $result = \DB::table('org_settings')->first();
    if ($result && $result->logo_path === $logoPath) {
        echo "✓ Database update verified\n";
    } else {
        echo "✗ Database update verification failed\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error updating database: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n";

// 4. Test logo functionality
echo "4. TESTING LOGO FUNCTIONALITY:\n";
echo "=============================\n";

try {
    // Test organization settings
    $orgSettings = \App\Models\OrgSettings::getInstance();
    echo "Organization: " . $orgSettings->name . "\n";
    echo "Logo path: " . $orgSettings->logo_path . "\n";
    
    // Test logo file existence
    if (\Storage::disk('public')->exists($orgSettings->logo_path)) {
        echo "✓ Logo file exists\n";
    } else {
        echo "✗ Logo file does not exist\n";
    }
    
    // Test logo URL
    $logoUrl = \Storage::disk('public')->url($orgSettings->logo_path);
    echo "Logo URL: $logoUrl\n";
    
} catch (Exception $e) {
    echo "✗ Error testing logo functionality: " . $e->getMessage() . "\n";
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
    
} catch (Exception $e) {
    echo "✗ Error clearing cache: " . $e->getMessage() . "\n";
}

echo "\n";

// 6. Final verification
echo "6. FINAL VERIFICATION:\n";
echo "=====================\n";

try {
    $orgSettings = \App\Models\OrgSettings::getInstance();
    
    echo "Organization: " . $orgSettings->name . "\n";
    echo "Logo path: " . $orgSettings->logo_path . "\n";
    echo "Logo file exists: " . (\Storage::disk('public')->exists($orgSettings->logo_path) ? 'YES' : 'NO') . "\n";
    echo "Logo URL: " . \Storage::disk('public')->url($orgSettings->logo_path) . "\n";
    
    if ($orgSettings->logo_path && \Storage::disk('public')->exists($orgSettings->logo_path)) {
        echo "✓ Logo is ready to be displayed\n";
    } else {
        echo "✗ Logo is not ready\n";
    }
    
} catch (Exception $e) {
    echo "✗ Final verification failed: " . $e->getMessage() . "\n";
}

echo "\n=== LOGO PATH UPDATED MANUALLY ===\n";
echo "\nLogo should now be displayed in the application!\n";
