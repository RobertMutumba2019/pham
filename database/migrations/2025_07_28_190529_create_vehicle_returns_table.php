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
        Schema::create('vehicle_returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehicle_request_id');
            $table->date('return_date');
            $table->time('return_time');
            $table->string('return_location');
            $table->integer('mileage_covered')->nullable();
            $table->text('return_notes')->nullable();
            $table->enum('vehicle_condition', ['excellent', 'good', 'fair', 'poor'])->default('good');
            $table->text('damage_description')->nullable();
            $table->unsignedBigInteger('returned_by');
            $table->unsignedBigInteger('received_by')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->timestamps();
            
            $table->foreign('vehicle_request_id')->references('id')->on('vehicle_requests')->onDelete('cascade');
            $table->foreign('returned_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('received_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_returns');
    }
};
