<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Drop the old pivot table and recreate with proper schema
        Schema::dropIfExists('feature_request_components');

        Schema::create('feature_request_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feature_request_id')->constrained('feature_requests')->cascadeOnDelete();
            $table->foreignId('technical_component_id')->constrained('technical_components')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['feature_request_id', 'technical_component_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feature_request_components');

        Schema::create('feature_request_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feature_request_id')->constrained('feature_requests')->cascadeOnDelete();
            $table->string('component');
            $table->timestamps();

            $table->unique(['feature_request_id', 'component']);
        });
    }
};