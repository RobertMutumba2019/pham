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
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->string('file_name', 255);
            $table->string('original_name', 255);
            $table->string('file_path', 500);
            $table->bigInteger('file_size');
            $table->string('mime_type', 100);
            $table->unsignedBigInteger('uploader_id');
            $table->string('attachable_type', 100); // Requisition, VehicleRequest, etc.
            $table->unsignedBigInteger('attachable_id');
            $table->string('description')->nullable();
            $table->boolean('is_public')->default(false);
            $table->timestamps();
            
            $table->foreign('uploader_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['attachable_type', 'attachable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
