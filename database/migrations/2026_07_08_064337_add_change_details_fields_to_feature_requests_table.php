<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('feature_requests', function (Blueprint $table) {
            $table->text('detail_perubahan')->nullable()->after('description');
            $table->string('pemohon_perubahan')->nullable()->after('detail_perubahan');
            $table->text('as_is')->nullable()->after('pemohon_perubahan');
            $table->text('to_be')->nullable()->after('as_is');
            $table->enum('klasifikasi_perubahan', ['Normal', 'Emergency'])->default('Normal')->after('to_be');
        });
    }

    public function down(): void
    {
        Schema::table('feature_requests', function (Blueprint $table) {
            $table->dropColumn(['detail_perubahan', 'pemohon_perubahan', 'as_is', 'to_be', 'klasifikasi_perubahan']);
        });
    }
};