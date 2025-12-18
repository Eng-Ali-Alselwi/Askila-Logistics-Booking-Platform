<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionHelper
{
    /**
     * Check if user has permission
     */
    public static function hasPermission(string $permission): bool
    {
        if (!Auth::check()) {
            return false;
        }

        return Auth::user()->hasPermissionTo($permission);
    }

    /**
     * Check if user has any of the given permissions
     */
    public static function hasAnyPermission(array $permissions): bool
    {
        if (!Auth::check()) {
            return false;
        }

        return Auth::user()->hasAnyPermission($permissions);
    }

    /**
     * Check if user has all of the given permissions
     */
    public static function hasAllPermissions(array $permissions): bool
    {
        if (!Auth::check()) {
            return false;
        }

        return Auth::user()->hasAllPermissions($permissions);
    }

    /**
     * Check if user has role
     */
    public static function hasRole(string $role): bool
    {
        if (!Auth::check()) {
            return false;
        }

        return Auth::user()->hasRole($role);
    }

    /**
     * Check if user has any of the given roles
     */
    public static function hasAnyRole(array $roles): bool
    {
        if (!Auth::check()) {
            return false;
        }

        return Auth::user()->hasAnyRole($roles);
    }

    /**
     * Get user's permissions
     */
    public static function getUserPermissions(): array
    {
        if (!Auth::check()) {
            return [];
        }

        return Auth::user()->getAllPermissions()->pluck('name')->toArray();
    }

    /**
     * Get user's roles
     */
    public static function getUserRoles(): array
    {
        if (!Auth::check()) {
            return [];
        }

        return Auth::user()->getRoleNames()->toArray();
    }

    /**
     * Check if user can access dashboard section
     */
    public static function canAccessSection(string $section): bool
    {
        $sectionPermissions = [
            'users' => ['view users', 'manage users'],
            'roles' => ['view roles', 'manage roles'],
            'shipments' => ['view shipments', 'manage shipments'],
            'customers' => ['view customers', 'manage customers'],
            'branches' => ['view branches', 'manage branches'],
            'reports' => ['view reports'],
            'bookings' => ['manage bookings'],
            'flights' => ['manage flights'],
            'settings' => ['view settings', 'manage settings'],
        ];

        if (!isset($sectionPermissions[$section])) {
            return false;
        }

        return self::hasAnyPermission($sectionPermissions[$section]);
    }

    /**
     * Check if user can perform action on resource
     */
    public static function canPerformAction(string $resource, string $action): bool
    {
        $permission = $action . ' ' . $resource;
        return self::hasPermission($permission);
    }

    /**
     * Get all available permissions
     */
    public static function getAllPermissions(): array
    {
        return Permission::pluck('name')->toArray();
    }

    /**
     * Get all available roles
     */
    public static function getAllRoles(): array
    {
        return Role::pluck('name')->toArray();
    }

    /**
     * Get menu items based on user permissions
     */
    public static function getMenuItems(): array
    {
        $menuItems = [
            [
                'name' => 'dashboard',
                'label' => 'Dashboard',
                'icon' => 'home',
                'route' => 'dashboard.index',
                'permissions' => [], // Dashboard is accessible to all authenticated users
            ],
            [
                'name' => 'users',
                'label' => 'Users',
                'icon' => 'users',
                'route' => 'dashboard.users.index',
                'permissions' => ['view users', 'manage users'],
            ],
            [
                'name' => 'roles',
                'label' => 'Roles',
                'icon' => 'shield',
                'route' => 'dashboard.roles.index',
                'permissions' => ['view roles', 'manage roles'],
            ],
            [
                'name' => 'shipments',
                'label' => 'Shipments',
                'icon' => 'truck',
                'route' => 'dashboard.shipments.index',
                'permissions' => ['view shipments', 'manage shipments'],
            ],
            [
                'name' => 'customers',
                'label' => 'Customers',
                'icon' => 'user-group',
                'route' => 'dashboard.customers.index',
                'permissions' => ['view customers', 'manage customers'],
            ],
            [
                'name' => 'branches',
                'label' => 'Branches',
                'icon' => 'building-office',
                'route' => 'dashboard.branches.index',
                'permissions' => ['view branches', 'manage branches'],
            ],
            [
                'name' => 'bookings',
                'label' => 'Bookings',
                'icon' => 'calendar',
                'route' => 'dashboard.bookings.index',
                'permissions' => ['manage bookings'],
            ],
            [
                'name' => 'flights',
                'label' => 'Flights',
                'icon' => 'airplane',
                'route' => 'dashboard.flights.index',
                'permissions' => ['manage flights'],
            ],
            [
                'name' => 'reports',
                'label' => 'Reports',
                'icon' => 'chart-bar',
                'route' => 'dashboard.reports.index',
                'permissions' => ['view reports'],
            ],
            [
                'name' => 'settings',
                'label' => 'Settings',
                'icon' => 'cog',
                'route' => 'dashboard.settings.index',
                'permissions' => ['view settings', 'manage settings'],
            ],
        ];

        // Filter menu items based on user permissions
        return array_filter($menuItems, function ($item) {
            if (empty($item['permissions'])) {
                return true; // No permissions required
            }
            return self::hasAnyPermission($item['permissions']);
        });
    }

    /**
     * Check if user can create a resource
     */
    public static function canCreate(string $resource): bool
    {
        return self::hasPermission('create ' . $resource);
    }

    /**
     * Check if user can view a resource
     */
    public static function canView(string $resource): bool
    {
        return self::hasPermission('view ' . $resource);
    }

    /**
     * Check if user can edit a resource
     */
    public static function canEdit(string $resource): bool
    {
        return self::hasPermission('edit ' . $resource);
    }

    /**
     * Check if user can delete a resource
     */
    public static function canDelete(string $resource): bool
    {
        return self::hasPermission('delete ' . $resource);
    }

    /**
     * Check if user can manage a resource
     */
    public static function canManage(string $resource): bool
    {
        return self::hasPermission('manage ' . $resource);
    }

    /**
     * Check if user can export a resource
     */
    public static function canExport(string $resource): bool
    {
        return self::hasPermission('export ' . $resource);
    }
}
