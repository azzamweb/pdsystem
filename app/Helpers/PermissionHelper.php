<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class PermissionHelper
{
    /**
     * Check if user has permission
     */
    public static function can(string $permission): bool
    {
        return Auth::check() && Auth::user()->can($permission);
    }

    /**
     * Check if user has any of the given permissions
     */
    public static function canAny(array $permissions): bool
    {
        if (!Auth::check()) {
            return false;
        }

        foreach ($permissions as $permission) {
            if (Auth::user()->can($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user has all of the given permissions
     */
    public static function canAll(array $permissions): bool
    {
        if (!Auth::check()) {
            return false;
        }

        foreach ($permissions as $permission) {
            if (!Auth::user()->can($permission)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if user has role
     */
    public static function hasRole(string $role): bool
    {
        return Auth::check() && Auth::user()->hasRole($role);
    }

    /**
     * Check if user has any of the given roles
     */
    public static function hasAnyRole(array $roles): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole($roles);
    }

    /**
     * Check if user can access all data (no unit scope restriction)
     */
    public static function canAccessAllData(): bool
    {
        if (!Auth::check()) {
            return false;
        }
        return Auth::user()->hasAnyRole(['super-admin', 'admin', 'bendahara-pengeluaran']);
    }

    /**
     * Check if user can manage documents
     */
    public static function canManageDocuments(): bool
    {
        return self::canAny([
            'documents.create',
            'documents.edit',
            'documents.delete',
            'documents.approve'
        ]);
    }

    /**
     * Check if user can view rekapitulasi
     */
    public static function canViewRekap(): bool
    {
        return self::can('rekap.view');
    }

    /**
     * Check if user can export rekapitulasi
     */
    public static function canExportRekap(): bool
    {
        return self::can('rekap.export');
    }

    /**
     * Get user's unit ID for scope filtering
     */
    public static function getUserUnitId(): ?int
    {
        if (!Auth::check()) {
            return null;
        }

        // If user can access all data, return null (no filtering)
        if (self::canAccessAllData()) {
            return null;
        }

        // Return user's unit ID for scope filtering
        return Auth::user()->unit_id;
    }

    /**
     * Get user's role name
     */
    public static function getUserRoleName(): ?string
    {
        if (!Auth::check()) {
            return null;
        }

        return Auth::user()->getRoleNames()->first();
    }

    /**
     * Get user's role display name
     */
    public static function getUserRoleDisplayName(): ?string
    {
        $roleName = self::getUserRoleName();
        
        if (!$roleName) {
            return null;
        }

        $roleDisplayNames = [
            'super-admin' => 'Super Admin',
            'admin' => 'Admin',
            'bendahara-pengeluaran' => 'Bendahara Pengeluaran',
            'bendahara-pengeluaran-pembantu' => 'Bendahara Pengeluaran Pembantu',
            'sekretariat' => 'Sekretariat',
        ];

        return $roleDisplayNames[$roleName] ?? ucfirst(str_replace('-', ' ', $roleName));
    }
}
