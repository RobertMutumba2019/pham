<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rejected_copy_masters', function (Blueprint $table) {
            $table->id();
            $table->text('rcm_comment');
            $table->timestamp('rcm_date_added');
            $table->unsignedBigInteger('rcm_added_by');
            $table->unsignedBigInteger('rcm_rejected_by');
            $table->unsignedBigInteger('rcm_type_id');
            $table->string('rcm_type');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rejected_copy_masters');
    }
}; 