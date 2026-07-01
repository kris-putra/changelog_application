<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('feature_requests', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->enum('type', ['feature','change','bug'])->default('feature');
            $table->enum('priority', ['low','medium','high','urgent'])->default('medium');
            $table->enum('status', ['pending','approved','in_progress','completed','rejected'])->default('pending');
            $table->unsignedBigInteger('requested_by');
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->json('metadata')->nullable();
            $table->unsignedInteger('votes_count')->default(0);
            $table->timestamps();

            // foreign keys may be added by integrator depending on users table
        });
    }

    public function down()
    {
        Schema::dropIfExists('feature_requests');
    }
};
