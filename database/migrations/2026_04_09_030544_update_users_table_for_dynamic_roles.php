<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->after('password')->constrained('roles')->onDelete('cascade');
        });

        // Migrate existing data
        $users = DB::table('users')->get();
        foreach ($users as $user) {
            $roleSlug = $user->role ?? 'bhw';
            
            // Ensure role exists in roles table
            $roleId = DB::table('roles')->where('slug', $roleSlug)->value('id');
            
            if (!$roleId) {
                $roleId = DB::table('roles')->insertGetId([
                    'name' => ucfirst(str_replace('_', ' ', $roleSlug)),
                    'slug' => $roleSlug,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            DB::table('users')->where('id', $user->id)->update(['role_id' => $roleId]);
        }

        // Ensure default Super Admin exists
        $adminEmail = 'admin@barangay.com';
        $adminExists = DB::table('users')->where('email', $adminEmail)->exists();

        if (!$adminExists) {
            $superAdminRoleId = DB::table('roles')->where('slug', 'super_admin')->value('id');

            if ($superAdminRoleId) {
                DB::table('users')->insert([
                    'name' => 'System Admin',
                    'email' => $adminEmail,
                    'password' => Hash::make('password'),
                    'role_id' => $superAdminRoleId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->nullable()->after('password');
        });

        // Optional: rollback role_id to role string (only if roles table still exists)
        if (Schema::hasTable('roles')) {
            $users = DB::table('users')->get();
            foreach ($users as $user) {
                $roleSlug = DB::table('roles')->where('id', $user->role_id)->value('slug');
                DB::table('users')->where('id', $user->id)->update(['role' => $roleSlug]);
            }
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('role_id');
        });
    }
};
