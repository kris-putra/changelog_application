<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('feature_requests', function (Blueprint $table) {
            $table->string('pic')->nullable()->after('status');
            $table->text('rollback_plan')->nullable()->after('pic');
            $table->dateTime('estimasi_selesai')->nullable()->after('rollback_plan');
        });
    }

    public function down(): void
    {
        Schema::table('feature_requests', function (Blueprint $table) {
            $table->dropColumn(['pic', 'rollback_plan', 'estimasi_selesai']);
        });
    }
};