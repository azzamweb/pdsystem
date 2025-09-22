#!/bin/bash

# Script untuk memperbaiki permissions di production server
# Jalankan dengan: bash fix_permissions.sh

echo "=== FIXING PRODUCTION PERMISSIONS ==="
echo ""

# 1. Fix storage permissions
echo "1. FIXING STORAGE PERMISSIONS:"
echo "=============================="

# Set permissions untuk storage directory
chmod -R 755 storage/
echo "✓ Set storage permissions to 755"

# Set permissions untuk logs directory
chmod -R 755 storage/logs/
echo "✓ Set logs permissions to 755"

# Set permissions untuk framework directory
chmod -R 755 storage/framework/
echo "✓ Set framework permissions to 755"

# Set permissions untuk app directory
chmod -R 755 storage/app/
echo "✓ Set app permissions to 755"

echo ""

# 2. Fix log file permissions
echo "2. FIXING LOG FILE PERMISSIONS:"
echo "==============================="

# Create log file if it doesn't exist
touch storage/logs/laravel.log
echo "✓ Created log file if not exists"

# Set log file permissions
chmod 644 storage/logs/laravel.log
echo "✓ Set log file permissions to 644"

# Set log directory permissions
chmod 755 storage/logs/
echo "✓ Set log directory permissions to 755"

echo ""

# 3. Fix database permissions
echo "3. FIXING DATABASE PERMISSIONS:"
echo "==============================="

# Set permissions untuk database file
if [ -f "database/database.sqlite" ]; then
    chmod 664 database/database.sqlite
    echo "✓ Set database file permissions to 664"
else
    echo "⚠ Database file not found"
fi

# Set permissions untuk database directory
chmod 755 database/
echo "✓ Set database directory permissions to 755"

echo ""

# 4. Fix public directory permissions
echo "4. FIXING PUBLIC DIRECTORY PERMISSIONS:"
echo "======================================="

# Set permissions untuk public directory
chmod -R 755 public/
echo "✓ Set public directory permissions to 755"

# Set permissions untuk storage symlink
if [ -L "public/storage" ]; then
    chmod 755 public/storage
    echo "✓ Set storage symlink permissions to 755"
else
    echo "⚠ Storage symlink not found"
fi

echo ""

# 5. Fix bootstrap directory permissions
echo "5. FIXING BOOTSTRAP DIRECTORY PERMISSIONS:"
echo "=========================================="

# Set permissions untuk bootstrap directory
chmod -R 755 bootstrap/
echo "✓ Set bootstrap directory permissions to 755"

# Set permissions untuk bootstrap cache
chmod -R 755 bootstrap/cache/
echo "✓ Set bootstrap cache permissions to 755"

echo ""

# 6. Clear cache files
echo "6. CLEARING CACHE FILES:"
echo "========================"

# Clear bootstrap cache
rm -rf bootstrap/cache/*.php
echo "✓ Cleared bootstrap cache"

# Clear storage cache
rm -rf storage/framework/cache/data/*
echo "✓ Cleared storage cache"

# Clear view cache
rm -rf storage/framework/views/*
echo "✓ Cleared view cache"

# Clear session files
rm -rf storage/framework/sessions/*
echo "✓ Cleared session files"

echo ""

# 7. Set ownership (jika diperlukan)
echo "7. SETTING OWNERSHIP:"
echo "===================="

# Get current user
CURRENT_USER=$(whoami)
echo "Current user: $CURRENT_USER"

# Set ownership untuk storage
chown -R $CURRENT_USER:$CURRENT_USER storage/
echo "✓ Set storage ownership to $CURRENT_USER"

# Set ownership untuk database
chown -R $CURRENT_USER:$CURRENT_USER database/
echo "✓ Set database ownership to $CURRENT_USER"

# Set ownership untuk bootstrap
chown -R $CURRENT_USER:$CURRENT_USER bootstrap/
echo "✓ Set bootstrap ownership to $CURRENT_USER"

echo ""

# 8. Final verification
echo "8. FINAL VERIFICATION:"
echo "====================="

# Check storage permissions
if [ -w "storage/" ]; then
    echo "✓ Storage directory is writable"
else
    echo "✗ Storage directory is not writable"
fi

# Check logs permissions
if [ -w "storage/logs/" ]; then
    echo "✓ Logs directory is writable"
else
    echo "✗ Logs directory is not writable"
fi

# Check database permissions
if [ -w "database/database.sqlite" ]; then
    echo "✓ Database file is writable"
else
    echo "✗ Database file is not writable"
fi

echo ""
echo "=== PERMISSIONS FIXED ==="
echo ""
echo "Next steps:"
echo "1. Run: php artisan cache:clear"
echo "2. Run: php artisan config:clear"
echo "3. Run: php artisan view:clear"
echo "4. Test logo upload functionality"
