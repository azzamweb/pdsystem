<?php

/**
 * Script untuk memperbaiki masalah org_settings di MySQL
 */

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== FIXING MYSQL ORG_SETTINGS ===\n\n";

// 1. Check database connection
echo "1. CHECKING DATABASE CONNECTION:\n";
echo "===============================\n";

try {
    $pdo = \DB::connection()->getPdo();
    echo "✓ Database connection successful\n";
    echo "Database type: " . \DB::connection()->getDriverName() . "\n";
    echo "Database name: " . \DB::connection()->getDatabaseName() . "\n";
} catch (Exception $e) {
    echo "✗ Database connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n";

// 2. Check existing tables
echo "2. CHECKING EXISTING TABLES:\n";
echo "===========================\n";

try {
    $tables = \DB::select("SHOW TABLES");
    echo "Existing tables:\n";
    foreach ($tables as $table) {
        $tableName = array_values((array)$table)[0];
        echo "  - $tableName\n";
    }
} catch (Exception $e) {
    echo "✗ Failed to get table list: " . $e->getMessage() . "\n";
}

echo "\n";

// 3. Check if org_settings table exists
echo "3. CHECKING ORG_SETTINGS TABLE:\n";
echo "===============================\n";

try {
    $tableExists = \DB::getSchemaBuilder()->hasTable('org_settings');
    echo "org_settings table exists: " . ($tableExists ? 'YES' : 'NO') . "\n";
    
    if ($tableExists) {
        $columns = \DB::select("DESCRIBE org_settings");
        echo "org_settings table columns:\n";
        foreach ($columns as $column) {
            echo "  - " . $column->Field . " (" . $column->Type . ")\n";
        }
    }
} catch (Exception $e) {
    echo "✗ Failed to check org_settings table: " . $e->getMessage() . "\n";
}

echo "\n";

// 4. Create org_settings table if it doesn't exist
echo "4. CREATING ORG_SETTINGS TABLE:\n";
echo "===============================\n";

try {
    if (!$tableExists) {
        echo "Creating org_settings table...\n";
        
        \DB::statement("CREATE TABLE org_settings (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            short_name VARCHAR(50) NULL,
            address TEXT NULL,
            city VARCHAR(100) NULL,
            province VARCHAR(100) NULL,
            phone VARCHAR(20) NULL,
            email VARCHAR(255) NULL,
            website VARCHAR(255) NULL,
            head_user_id BIGINT UNSIGNED NULL,
            head_title VARCHAR(100) NOT NULL,
            signature_path VARCHAR(255) NULL,
            stamp_path VARCHAR(255) NULL,
            logo_path VARCHAR(255) NULL,
            ym_separator VARCHAR(5) NOT NULL DEFAULT '/',
            qr_footer_text VARCHAR(255) NULL,
            show_left_logo BOOLEAN NOT NULL DEFAULT 1,
            show_right_logo BOOLEAN NOT NULL DEFAULT 0,
            singleton BOOLEAN NOT NULL DEFAULT 1,
            created_at TIMESTAMP NULL DEFAULT NULL,
            updated_at TIMESTAMP NULL DEFAULT NULL,
            FOREIGN KEY (head_user_id) REFERENCES users(id) ON DELETE SET NULL
        )");
        
        echo "✓ org_settings table created successfully\n";
    } else {
        echo "✓ org_settings table already exists\n";
    }
} catch (Exception $e) {
    echo "✗ Failed to create org_settings table: " . $e->getMessage() . "\n";
}

echo "\n";

// 5. Check if data exists
echo "5. CHECKING ORG_SETTINGS DATA:\n";
echo "=============================\n";

try {
    $count = \DB::table('org_settings')->count();
    echo "org_settings records: $count\n";
    
    if ($count === 0) {
        echo "No data found. Creating default organization settings...\n";
        
        \DB::table('org_settings')->insert([
            'name' => 'Badan Pengelola Keuangan dan Aset Daerah',
            'short_name' => 'BPKAD',
            'address' => 'Jl. Raya Bengkalis',
            'city' => 'Bengkalis',
            'province' => 'Riau',
            'phone' => '0766-123456',
            'email' => 'info@bpkad.bengkalis.go.id',
            'website' => 'https://bpkad.bengkalis.go.id',
            'head_title' => 'Kepala Badan Pengelola Keuangan dan Aset Daerah',
            'ym_separator' => '/',
            'qr_footer_text' => 'BPKAD Kabupaten Bengkalis',
            'show_left_logo' => true,
            'show_right_logo' => false,
            'singleton' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        echo "✓ Default organization settings created\n";
    } else {
        $orgSettings = \DB::table('org_settings')->first();
        echo "✓ Organization settings found:\n";
        echo "  Name: " . $orgSettings->name . "\n";
        echo "  Short Name: " . ($orgSettings->short_name ?? 'NULL') . "\n";
        echo "  Logo Path: " . ($orgSettings->logo_path ?? 'NULL') . "\n";
    }
} catch (Exception $e) {
    echo "✗ Failed to check/create org_settings data: " . $e->getMessage() . "\n";
}

echo "\n";

// 6. Test organization settings model
echo "6. TESTING ORGANIZATION SETTINGS MODEL:\n";
echo "=======================================\n";

try {
    $orgSettings = \App\Models\OrgSettings::getInstance();
    echo "✓ Organization settings model working\n";
    echo "Organization: " . $orgSettings->name . "\n";
    echo "Short Name: " . ($orgSettings->short_name ?? 'NULL') . "\n";
    echo "Logo Path: " . ($orgSettings->logo_path ?? 'NULL') . "\n";
} catch (Exception $e) {
    echo "✗ Organization settings model failed: " . $e->getMessage() . "\n";
}

echo "\n";

// 7. Check existing logo files
echo "7. CHECKING EXISTING LOGO FILES:\n";
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
} else {
    echo "Found " . count($logoFiles) . " logo file(s):\n";
    foreach ($logoFiles as $index => $file) {
        echo "  " . ($index + 1) . ". $file\n";
    }
    
    // Update logo path with the most recent file
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
        $logoPath = 'logos/' . $mostRecentFile;
        echo "Most recent logo: $mostRecentFile\n";
        
        try {
            \DB::table('org_settings')->update(['logo_path' => $logoPath]);
            echo "✓ Logo path updated: $logoPath\n";
        } catch (Exception $e) {
            echo "✗ Failed to update logo path: " . $e->getMessage() . "\n";
        }
    }
}

echo "\n";

// 8. Final verification
echo "8. FINAL VERIFICATION:\n";
echo "=====================\n";

try {
    $orgSettings = \App\Models\OrgSettings::getInstance();
    echo "✓ Organization settings working correctly\n";
    echo "Organization: " . $orgSettings->name . "\n";
    echo "Logo Path: " . ($orgSettings->logo_path ?? 'NULL') . "\n";
    
    if ($orgSettings->logo_path) {
        if (\Storage::disk('public')->exists($orgSettings->logo_path)) {
            echo "✓ Logo file exists and is accessible\n";
        } else {
            echo "⚠ Logo file not accessible\n";
        }
    }
} catch (Exception $e) {
    echo "✗ Final verification failed: " . $e->getMessage() . "\n";
}

echo "\n=== MYSQL ORG_SETTINGS FIXED ===\n";
echo "\nOrganization settings should now work correctly!\n";
