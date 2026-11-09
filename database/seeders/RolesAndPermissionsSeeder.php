<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Role;
use App\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            'Residents' => ['view', 'create', 'edit', 'delete'],
            'Households' => ['view', 'create', 'edit', 'delete'],
            'Puroks' => ['view', 'create', 'edit', 'delete'],
            'Users' => ['view', 'create', 'edit', 'delete'],
            'Roles' => ['view', 'create', 'edit', 'delete'],
            'Certificates' => ['view', 'create', 'edit', 'delete'],
            'Requests' => ['view', 'create', 'edit', 'delete'],
            'Blotter' => ['view', 'create', 'edit', 'delete'],
            'Inventory' => ['view', 'create', 'edit', 'delete'],
            'Announcements' => ['view', 'create', 'edit', 'delete'],
            'Finance' => ['view', 'create', 'edit', 'delete'],
        ];

        $allPermissions = [];

        foreach ($modules as $module => $actions) {
            foreach ($actions as $action) {
                $slug = $action . '_' . strtolower($module);
                $permission = Permission::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'name' => ucfirst($action) . ' ' . $module,
                        'module' => $module
                    ]
                );
                $allPermissions[$slug] = $permission->id;
            }
        }

        // Roles
        $roles = [
            'super_admin' => [
                'name' => 'Super Admin',
                'description' => 'System administrator with full access.',
                'permissions' => array_keys($allPermissions) // All permissions
            ],
            'secretary' => [
                'name' => 'Secretary',
                'description' => 'Barangay Secretary with management access.',
                'permissions' => [
                    'view_residents', 'create_residents', 'edit_residents', 'delete_residents',
                    'view_households', 'create_households', 'edit_households', 'delete_households',
                    'view_puroks', 'create_puroks', 'edit_puroks', 'delete_puroks',
                ]
            ],
            'bhw' => [
                'name' => 'BHW',
                'description' => 'Barangay Health Worker.',
                'permissions' => [
                    'view_residents', 'create_residents',
                    'view_households',
                    'view_puroks',
                ]
            ],
            'sk' => [
                'name' => 'SK',
                'description' => 'Sangguniang Kabataan.',
                'permissions' => [
                    'view_residents',
                ]
            ],
            'brgy_official' => [
                'name' => 'Brgy Official',
                'description' => 'Barangay Official.',
                'permissions' => [
                    'view_residents',
                    'view_households',
                    'view_puroks',
                ]
            ],
        ];

        foreach ($roles as $slug => $data) {
            $role = Role::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $data['name'],
                    'description' => $data['description']
                ]
            );

            // Sync permissions
            $permissionIds = [];
            foreach ($data['permissions'] as $pSlug) {
                if (isset($allPermissions[$pSlug])) {
                    $permissionIds[] = $allPermissions[$pSlug];
                }
            }
            $role->permissions()->sync($permissionIds);
        }
    }
}
