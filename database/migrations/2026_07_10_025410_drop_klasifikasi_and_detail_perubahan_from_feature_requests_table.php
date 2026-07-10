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
        Schema::table('feature_requests', function (Blueprint $table) {
            $table->dropColumn(['detail_perubahan', 'klasifikasi_perubahan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feature_requests', function (Blueprint $table) {
            $table->text('detail_perubahan')->nullable()->after('description');
            $table->enum('klasifikasi_perubahan', ['Normal', 'Emergency'])->default('Normal')->after('to_be');
        });
    }
};