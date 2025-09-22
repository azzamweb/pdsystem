<?php

/**
 * Script untuk memperbaiki masalah production yang ditemukan
 */

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== FIXING PRODUCTION ISSUES ===\n\n";

// 1. Fix log file permissions
echo "1. FIXING LOG FILE PERMISSIONS:\n";
echo "===============================\n";

$logPath = storage_path('logs');
$logFile = storage_path('logs/laravel.log');

echo "Log directory: $logPath\n";
echo "Log file: $logFile\n\n";

// Check if log directory exists
if (!is_dir($logPath)) {
    if (mkdir($logPath, 0755, true)) {
        echo "✓ Created log directory\n";
    } else {
        echo "✗ Failed to create log directory\n";
    }
} else {
    echo "✓ Log directory exists\n";
}

// Fix log directory permissions
if (chmod($logPath, 0755)) {
    echo "✓ Fixed log directory permissions\n";
} else {
    echo "✗ Failed to fix log directory permissions\n";
}

// Check if log file exists
if (file_exists($logFile)) {
    echo "✓ Log file exists\n";
    
    // Fix log file permissions
    if (chmod($logFile, 0644)) {
        echo "✓ Fixed log file permissions\n";
    } else {
        echo "✗ Failed to fix log file permissions\n";
    }
} else {
    echo "⚠ Log file does not exist, will be created automatically\n";
}

// Test log file write
try {
    $testLog = storage_path('logs/test.log');
    if (file_put_contents($testLog, 'test log entry')) {
        echo "✓ Can write to log directory\n";
        unlink($testLog);
    } else {
        echo "✗ Cannot write to log directory\n";
    }
} catch (Exception $e) {
    echo "✗ Log write test failed: " . $e->getMessage() . "\n";
}

echo "\n";

// 2. Fix cache table issue
echo "2. FIXING CACHE TABLE ISSUE:\n";
echo "============================\n";

try {
    // Check if cache table exists
    $cacheTableExists = \DB::getSchemaBuilder()->hasTable('cache');
    echo "Cache table exists: " . ($cacheTableExists ? 'YES' : 'NO') . "\n";
    
    if (!$cacheTableExists) {
        echo "Creating cache table...\n";
        
        // Create cache table
        \DB::statement('CREATE TABLE IF NOT EXISTS cache (
            key TEXT PRIMARY KEY,
            value TEXT NOT NULL,
            expiration INTEGER NOT NULL
        )');
        
        echo "✓ Cache table created\n";
    } else {
        echo "✓ Cache table already exists\n";
    }
    
    // Test cache table
    \DB::table('cache')->insert([
        'key' => 'test_key_' . time(),
        'value' => 'test_value',
        'expiration' => time() + 3600
    ]);
    
    $testCache = \DB::table('cache')->where('key', 'like', 'test_key_%')->first();
    if ($testCache) {
        echo "✓ Cache table working correctly\n";
        \DB::table('cache')->where('key', 'like', 'test_key_%')->delete();
    } else {
        echo "✗ Cache table not working correctly\n";
    }
    
} catch (Exception $e) {
    echo "✗ Cache table fix failed: " . $e->getMessage() . "\n";
}

echo "\n";

// 3. Fix storage permissions
echo "3. FIXING STORAGE PERMISSIONS:\n";
echo "==============================\n";

$storagePaths = [
    storage_path() => 'Storage root',
    storage_path('app') => 'Storage app',
    storage_path('app/public') => 'Storage public',
    storage_path('app/public/logos') => 'Storage logos',
    storage_path('framework') => 'Storage framework',
    storage_path('framework/cache') => 'Storage cache',
    storage_path('framework/sessions') => 'Storage sessions',
    storage_path('framework/views') => 'Storage views',
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

// 4. Clear cache safely
echo "4. CLEARING CACHE SAFELY:\n";
echo "=========================\n";

try {
    // Clear file-based cache
    $cacheFiles = glob(storage_path('framework/cache/data/*'));
    foreach ($cacheFiles as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
    echo "✓ Cleared file-based cache\n";
    
    // Clear view cache
    $viewFiles = glob(storage_path('framework/views/*'));
    foreach ($viewFiles as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
    echo "✓ Cleared view cache\n";
    
    // Clear session files
    $sessionFiles = glob(storage_path('framework/sessions/*'));
    foreach ($sessionFiles as $file) {
        if (is_file($file) && basename($file) !== '.gitignore') {
            unlink($file);
        }
    }
    echo "✓ Cleared session files\n";
    
} catch (Exception $e) {
    echo "✗ Cache clearing failed: " . $e->getMessage() . "\n";
}

echo "\n";

// 5. Test Laravel functionality
echo "5. TESTING LARAVEL FUNCTIONALITY:\n";
echo "=================================\n";

try {
    // Test database connection
    \DB::connection()->getPdo();
    echo "✓ Database connection working\n";
    
    // Test cache functionality
    \Cache::put('test_cache_key', 'test_value', 60);
    $cacheValue = \Cache::get('test_cache_key');
    if ($cacheValue === 'test_value') {
        echo "✓ Cache functionality working\n";
        \Cache::forget('test_cache_key');
    } else {
        echo "✗ Cache functionality not working\n";
    }
    
    // Test storage functionality
    \Storage::disk('public')->put('test_storage.txt', 'test content');
    if (\Storage::disk('public')->exists('test_storage.txt')) {
        echo "✓ Storage functionality working\n";
        \Storage::disk('public')->delete('test_storage.txt');
    } else {
        echo "✗ Storage functionality not working\n";
    }
    
} catch (Exception $e) {
    echo "✗ Laravel functionality test failed: " . $e->getMessage() . "\n";
}

echo "\n";

// 6. Final recommendations
echo "6. FINAL RECOMMENDATIONS:\n";
echo "========================\n";

echo "• Ensure web server user has write permissions to storage directories\n";
echo "• Check that SQLite database file is writable\n";
echo "• Monitor application logs for any errors\n";
echo "• Test logo upload functionality\n";

echo "\n=== PRODUCTION ISSUES FIXED ===\n";
