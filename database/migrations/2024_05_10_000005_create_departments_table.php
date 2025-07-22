<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('dept_name')->unique();
            $table->unsignedBigInteger('dept_added_by')->nullable();
            $table->timestamp('dept_date_added')->nullable();
            $table->unsignedBigInteger('dept_office_id')->nullable();
            $table->integer('dept_status')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
}; 