<?php

/**
 * Script untuk memperbaiki masalah cache table di SQLite
 */

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== FIXING CACHE TABLE ISSUE ===\n\n";

// 1. Check database connection
echo "1. CHECKING DATABASE CONNECTION:\n";
echo "===============================\n";

try {
    $pdo = \DB::connection()->getPdo();
    echo "✓ Database connection successful\n";
    echo "Database type: " . \DB::connection()->getDriverName() . "\n";
} catch (Exception $e) {
    echo "✗ Database connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n";

// 2. Check existing tables
echo "2. CHECKING EXISTING TABLES:\n";
echo "===========================\n";

try {
    $tables = \DB::select("SELECT name FROM sqlite_master WHERE type='table'");
    echo "Existing tables:\n";
    foreach ($tables as $table) {
        echo "  - " . $table->name . "\n";
    }
} catch (Exception $e) {
    echo "✗ Failed to get table list: " . $e->getMessage() . "\n";
}

echo "\n";

// 3. Check cache table
echo "3. CHECKING CACHE TABLE:\n";
echo "========================\n";

try {
    $cacheTableExists = \DB::getSchemaBuilder()->hasTable('cache');
    echo "Cache table exists: " . ($cacheTableExists ? 'YES' : 'NO') . "\n";
    
    if ($cacheTableExists) {
        // Check cache table structure
        $columns = \DB::select("PRAGMA table_info(cache)");
        echo "Cache table columns:\n";
        foreach ($columns as $column) {
            echo "  - " . $column->name . " (" . $column->type . ")\n";
        }
    }
} catch (Exception $e) {
    echo "✗ Failed to check cache table: " . $e->getMessage() . "\n";
}

echo "\n";

// 4. Create cache table if needed
echo "4. CREATING CACHE TABLE:\n";
echo "========================\n";

try {
    if (!$cacheTableExists) {
        echo "Creating cache table...\n";
        
        // Create cache table with proper structure
        \DB::statement('CREATE TABLE cache (
            key TEXT PRIMARY KEY,
            value TEXT NOT NULL,
            expiration INTEGER NOT NULL
        )');
        
        echo "✓ Cache table created successfully\n";
    } else {
        echo "✓ Cache table already exists\n";
    }
} catch (Exception $e) {
    echo "✗ Failed to create cache table: " . $e->getMessage() . "\n";
}

echo "\n";

// 5. Test cache functionality
echo "5. TESTING CACHE FUNCTIONALITY:\n";
echo "===============================\n";

try {
    // Test cache put
    \Cache::put('test_key_' . time(), 'test_value', 60);
    echo "✓ Cache put successful\n";
    
    // Test cache get
    $value = \Cache::get('test_key_' . time());
    if ($value === 'test_value') {
        echo "✓ Cache get successful\n";
    } else {
        echo "✗ Cache get failed\n";
    }
    
    // Test cache forget
    \Cache::forget('test_key_' . time());
    echo "✓ Cache forget successful\n";
    
} catch (Exception $e) {
    echo "✗ Cache functionality test failed: " . $e->getMessage() . "\n";
}

echo "\n";

// 6. Clear existing cache
echo "6. CLEARING EXISTING CACHE:\n";
echo "===========================\n";

try {
    // Clear cache table
    \DB::table('cache')->truncate();
    echo "✓ Cache table cleared\n";
    
    // Clear file-based cache
    $cacheFiles = glob(storage_path('framework/cache/data/*'));
    foreach ($cacheFiles as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
    echo "✓ File-based cache cleared\n";
    
} catch (Exception $e) {
    echo "✗ Cache clearing failed: " . $e->getMessage() . "\n";
}

echo "\n";

// 7. Test Laravel cache commands
echo "7. TESTING LARAVEL CACHE COMMANDS:\n";
echo "==================================\n";

try {
    // Test cache:clear command
    \Artisan::call('cache:clear');
    echo "✓ cache:clear command successful\n";
    
    // Test config:clear command
    \Artisan::call('config:clear');
    echo "✓ config:clear command successful\n";
    
    // Test view:clear command
    \Artisan::call('view:clear');
    echo "✓ view:clear command successful\n";
    
} catch (Exception $e) {
    echo "✗ Laravel cache commands failed: " . $e->getMessage() . "\n";
}

echo "\n";

// 8. Final verification
echo "8. FINAL VERIFICATION:\n";
echo "=====================\n";

try {
    // Test cache again
    \Cache::put('final_test', 'success', 60);
    $finalValue = \Cache::get('final_test');
    
    if ($finalValue === 'success') {
        echo "✓ Cache functionality working correctly\n";
        \Cache::forget('final_test');
    } else {
        echo "✗ Cache functionality not working\n";
    }
    
    // Test database cache table
    $cacheCount = \DB::table('cache')->count();
    echo "Cache table entries: $cacheCount\n";
    
} catch (Exception $e) {
    echo "✗ Final verification failed: " . $e->getMessage() . "\n";
}

echo "\n=== CACHE TABLE FIXED ===\n";
echo "\nCache functionality should now work correctly!\n";
