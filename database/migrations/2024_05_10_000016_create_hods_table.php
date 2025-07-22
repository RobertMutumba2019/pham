<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hod_dept_id');
            $table->unsignedBigInteger('hod_user_id');
            $table->unsignedBigInteger('hod_added_by')->nullable();
            $table->timestamp('hod_date_added')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hods');
    }
}; 