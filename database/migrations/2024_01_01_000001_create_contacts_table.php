<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('profile_image')->nullable();
            $table->string('additional_file')->nullable();
            $table->json('additional_emails')->nullable();
            $table->json('additional_phones')->nullable();
            $table->json('custom_field_values')->nullable();
            $table->enum('status', ['active', 'merged', 'deleted'])->default('active');
            $table->unsignedBigInteger('merged_into_id')->nullable();
            $table->timestamps();
            
            $table->foreign('merged_into_id')->references('id')->on('contacts')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};



