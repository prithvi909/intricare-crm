<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('merge_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('master_contact_id');
            $table->unsignedBigInteger('merged_contact_id');
            $table->string('master_contact_name');
            $table->string('merged_contact_name');
            $table->json('merged_data')->nullable();
            $table->json('fields_added')->nullable();
            $table->timestamps();
            
            $table->foreign('master_contact_id')->references('id')->on('contacts')->onDelete('cascade');
            $table->foreign('merged_contact_id')->references('id')->on('contacts')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('merge_histories');
    }
};



