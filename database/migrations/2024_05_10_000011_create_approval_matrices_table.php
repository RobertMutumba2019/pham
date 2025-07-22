<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('approval_matrices', function (Blueprint $table) {
            $table->id();
            $table->string('ap_code')->unique();
            $table->string('ap_unit_code');
            $table->unsignedBigInteger('ap_added_by')->nullable();
            $table->timestamp('ap_date_added')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approval_matrices');
    }
}; 