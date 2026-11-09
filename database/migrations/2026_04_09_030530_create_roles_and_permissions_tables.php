<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('module');
            $table->timestamps();
        });

        Schema::create('permission_role', function (Blueprint $table) {
            $table->foreignId('permission_id')->constrained()->onDelete('cascade');
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->primary(['permission_id', 'role_id']);
        });

        // Seed Data Directly
        $this->seedData();
    }

    /**
     * Seed initial security data
     */
    private function seedData(): void
    {
        // 1. Permissions
        $permissions = [
            // Residents
            ['name' => 'View Residents', 'slug' => 'view_residents', 'module' => 'Residents'],
            ['name' => 'Create Residents', 'slug' => 'create_residents', 'module' => 'Residents'],
            ['name' => 'Edit Residents', 'slug' => 'edit_residents', 'module' => 'Residents'],
            ['name' => 'Delete Residents', 'slug' => 'delete_residents', 'module' => 'Residents'],

            // Households
            ['name' => 'View Households', 'slug' => 'view_households', 'module' => 'Households'],
            ['name' => 'Create Households', 'slug' => 'create_households', 'module' => 'Households'],
            ['name' => 'Edit Households', 'slug' => 'edit_households', 'module' => 'Households'],
            ['name' => 'Delete Households', 'slug' => 'delete_households', 'module' => 'Households'],

            // Puroks
            ['name' => 'View Puroks', 'slug' => 'view_puroks', 'module' => 'Puroks'],
            ['name' => 'Create Puroks', 'slug' => 'create_puroks', 'module' => 'Puroks'],
            ['name' => 'Edit Puroks', 'slug' => 'edit_puroks', 'module' => 'Puroks'],
            ['name' => 'Delete Puroks', 'slug' => 'delete_puroks', 'module' => 'Puroks'],

            // System Management
            ['name' => 'Manage Users', 'slug' => 'manage_users', 'module' => 'System'],
            ['name' => 'Manage Roles', 'slug' => 'manage_roles', 'module' => 'System'],
            ['name' => 'View Audit Logs', 'slug' => 'view_audit_logs', 'module' => 'System'],

            // Documents
            ['name' => 'View Certificates', 'slug' => 'view_certificates', 'module' => 'Documents'],
            ['name' => 'Issue Certificates', 'slug' => 'issue_certificates', 'module' => 'Documents'],

            // Requests
            ['name' => 'View Requests', 'slug' => 'view_requests', 'module' => 'Operations'],
            ['name' => 'Manage Requests', 'slug' => 'manage_requests', 'module' => 'Operations'],

            // Blotter
            ['name' => 'View Blotter', 'slug' => 'view_blotter', 'module' => 'Operations'],
            ['name' => 'Manage Blotter', 'slug' => 'manage_blotter', 'module' => 'Operations'],

            // Inventory
            ['name' => 'View Inventory', 'slug' => 'view_inventory', 'module' => 'Operations'],
            ['name' => 'Manage Inventory', 'slug' => 'manage_inventory', 'module' => 'Operations'],

            // Announcements
            ['name' => 'View Announcements', 'slug' => 'view_announcements', 'module' => 'Operations'],
            ['name' => 'Manage Announcements', 'slug' => 'manage_announcements', 'module' => 'Operations'],

            // Finance
            ['name' => 'View Finance', 'slug' => 'view_finance', 'module' => 'Finance'],
            ['name' => 'Manage Finance', 'slug' => 'manage_finance', 'module' => 'Finance'],
        ];

        foreach ($permissions as $p) {
            DB::table('permissions')->insert(array_merge($p, ['created_at' => now(), 'updated_at' => now()]));
        }

        // 2. Roles
        $roles = [
            ['name' => 'Super Admin', 'slug' => 'super_admin', 'description' => 'Unrestricted access to the entire system.'],
            ['name' => 'Barangay Captain', 'slug' => 'captain', 'description' => 'Full administrative control over barangay operations.'],
            ['name' => 'Administrator', 'slug' => 'administrator', 'description' => 'Operational management with most permissions.'],
            ['name' => 'Secretary', 'slug' => 'secretary', 'description' => 'Manages residents and household records.'],
            ['name' => 'Purok Leader', 'slug' => 'purok_leader', 'description' => 'Registry view access for their specific area.'],
        ];

        foreach ($roles as $r) {
            $roleId = DB::table('roles')->insertGetId(array_merge($r, ['created_at' => now(), 'updated_at' => now()]));

            // Assign Permissions
            $permsToAssign = [];
            if ($r['slug'] === 'super_admin' || $r['slug'] === 'captain') {
                $permsToAssign = DB::table('permissions')->pluck('id')->toArray();
            } elseif ($r['slug'] === 'administrator') {
                $permsToAssign = DB::table('permissions')->where('module', '!=', 'System')->pluck('id')->toArray();
            } elseif ($r['slug'] === 'secretary') {
                $permsToAssign = DB::table('permissions')->whereIn('module', ['Residents', 'Households', 'Documents', 'Operations'])->pluck('id')->toArray();
            } elseif ($r['slug'] === 'purok_leader') {
                $permsToAssign = DB::table('permissions')->whereIn('slug', ['view_residents', 'view_households', 'view_puroks'])->pluck('id')->toArray();
            }

            foreach ($permsToAssign as $permId) {
                DB::table('permission_role')->insert([
                    'permission_id' => $permId,
                    'role_id' => $roleId
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};
