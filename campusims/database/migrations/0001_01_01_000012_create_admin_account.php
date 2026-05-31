<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::table('users')->where('email', 'admin@campus.edu')->exists()) {
            return;
        }

        DB::table('users')->insert([
            'name' => 'System Admin',
            'email' => 'admin@campus.edu',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'approved',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')->where('email', 'admin@campus.edu')->delete();
    }
};
