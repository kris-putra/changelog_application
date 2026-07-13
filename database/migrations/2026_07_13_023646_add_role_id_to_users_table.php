<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->nullable()->after('email');
        });

        // Ensure roles exist (in case seeder hasn't run)
        $now = now();
        DB::table('roles')->insertOrIgnore([
            ['name' => 'Administrator', 'slug' => 'administrator', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'User', 'slug' => 'user', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Assign default 'user' role to existing users
        $userRole = DB::table('roles')->where('slug', 'user')->first();
        if ($userRole) {
            DB::table('users')->whereNull('role_id')->update(['role_id' => $userRole->id]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->nullable(false)->change();
            $table->foreign('role_id')->references('id')->on('roles');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
    }
};
