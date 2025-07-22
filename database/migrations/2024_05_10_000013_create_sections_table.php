<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('section_name')->unique();
            $table->unsignedBigInteger('section_dept_id');
            $table->unsignedBigInteger('section_added_by')->nullable();
            $table->timestamp('section_date_added')->nullable();
            $table->integer('section_status')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
}; 