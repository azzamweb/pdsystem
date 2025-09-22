-- SQL commands untuk mengupdate logo path di database production
-- Jalankan dengan: sqlite3 database/database.sqlite < update_logo_sql.sql

-- 1. Check current org_settings
SELECT * FROM org_settings;

-- 2. Update logo_path dengan file logo yang ada
-- Ganti 'logos/filename.png' dengan nama file logo yang sebenarnya
UPDATE org_settings SET logo_path = 'logos/filename.png' WHERE id = 1;

-- 3. Verify update
SELECT id, name, logo_path FROM org_settings;

-- 4. Jika tidak ada logo file, set ke NULL
-- UPDATE org_settings SET logo_path = NULL WHERE id = 1;
