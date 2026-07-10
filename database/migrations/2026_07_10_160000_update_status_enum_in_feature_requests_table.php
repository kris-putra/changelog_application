<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            // Step 1: Convert ENUM to VARCHAR(20) to support new workflow values
            DB::statement("ALTER TABLE feature_requests MODIFY COLUMN status VARCHAR(20) NOT NULL DEFAULT 'pending'");

            // Step 2: Update existing data: 'pending' -> 'Open'
            DB::table('feature_requests')
                ->where('status', 'pending')
                ->update(['status' => 'Open']);

            // Step 3: Convert to new ENUM with workflow values
            DB::statement("ALTER TABLE feature_requests MODIFY COLUMN status ENUM('Open', 'In Progress', 'Completed') NOT NULL DEFAULT 'Open'");
        } else {
            // SQLite: column is already VARCHAR, just update data
            DB::table('feature_requests')
                ->where('status', 'pending')
                ->update(['status' => 'Open']);
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            // Convert to VARCHAR first
            DB::statement("ALTER TABLE feature_requests MODIFY COLUMN status VARCHAR(20) NOT NULL DEFAULT 'pending'");

            // Revert data: 'Open' -> 'pending'
            DB::table('feature_requests')
                ->where('status', 'Open')
                ->update(['status' => 'pending']);

            // Revert enum to original values
            DB::statement("ALTER TABLE feature_requests MODIFY COLUMN status ENUM('pending','approved','in_progress','completed','rejected') NOT NULL DEFAULT 'pending'");
        } else {
            // SQLite: just revert data
            DB::table('feature_requests')
                ->where('status', 'Open')
                ->update(['status' => 'pending']);
        }
    }
};