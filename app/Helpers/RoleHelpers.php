<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class UserRole
{
    const ADMIN = 'admin';
    const SITE_LEADER = 'site_leader';
    const MODERATOR = 'moderator';
    const VIEWER = 'viewer';
    const GODMODE = 'godmode';
}

class RoleHelpers
{
    public $edsparkRoles = ['Superadmin' => 'Superadmin',
        'Moderator' => 'Moderator',
        'SCHLDR' => 'School Principal',
        'PRESCLDR' => 'Preschool Director',
        'SITELDR' => 'Site Leadership Team',
        'STCH' => 'School Teacher',
        'PTCH' => 'Preschool Teacher',
        'SITESUPP' => 'Site Support Staff',
        'PSACT' => 'Public Sector Act',
        'CONT' => 'External Contractor',
        'IT' => 'Staff With IT Admin Responsibilities',
        'BUSMAN' => 'Business Manager',
        'WELLNESS' => 'Wellbeing Staff',
        'STDCHR' => 'Student Teacher',
        'VOLUN' => 'Volunteer',
        'OTHER' => 'Other',
        'STAFF' => 'Staff',
        'PWDRESET' => 'Password Reset Administrator (Staff)',
        'PWDRESETSTD' => 'Password Reset Administrator (Student)'];

    public $roles = ['Superadmin' => 'Superadmin',
        'Moderator' => 'Moderator',
        'SCHLDR' => 'SCHLDR',
        'PRESCLDR' => 'PRESCLDR',
        'SITELDR' => 'SITELDR',
        'STCH' => 'STCH',
        'PTCH' => 'PTCH',
        'SITESUPP' => 'SITESUPP',
        'PSACT' => 'PSACT',
        'CONT' => 'CONT',
        'IT' => 'IT',
        'BUSMAN' => 'BUSMAN',
        'WELLNESS' => 'WELLNESS',
        'STDCHR' => 'STDCHR',
        'VOLUN' => 'VOLUN',
        'OTHER' => 'OTHER',
        'STAFF' => 'STAFF',
        'PWDRESET' => 'PWDRESET',
        'PWDRESETSTD' => 'PWDRESETSTD',];
    protected static array $god_key = ['Godmode'];

    protected static array $admin_keys = ['Superadmin', 'Moderator',];
    protected static array $moderator_keys = ['PSACT'];
    protected static array $site_leader_keys = ['SCHLDR', 'PRESCLDR', 'SITELDR'];
    protected static array $role_hierarchy = [
        UserRole::VIEWER => 10,
        UserRole::SITE_LEADER => 20,
        UserRole::MODERATOR => 25,
        UserRole::ADMIN => 30,
        UserRole::GODMODE => 50
    ];

    public static function get_privilege_level($role): int
    {
        return self::$role_hierarchy[$role] ?? -1;
    }

    public static function get_current_user_access_level(): string
    {
        $user_role = Auth::user()->role->role_name;

        if (in_array($user_role, self::$god_key)) {
            return UserRole::GODMODE;
        } elseif (in_array($user_role, self::$admin_keys)) {
            return UserRole::ADMIN;
        } elseif (in_array($user_role, self::$site_leader_keys)) {
            return UserRole::SITE_LEADER;
        } elseif (in_array($user_role, self::$moderator_keys)) {
            return UserRole::MODERATOR;
        } else {
            return UserRole::VIEWER;
        }
    }
    // check the current user's role and check the hierarchy based on the role_hierarchy
    // and return true if the current users meet the requirements
    public static function has_minimum_privilege($min_required = UserRole::VIEWER): bool
    {
        $role = self::get_current_user_access_level();
        $user_privilege_level = self::get_privilege_level($role);
        $min_required_level = self::get_privilege_level($min_required);

        return $user_privilege_level >= $min_required_level;
    }

}


?>
