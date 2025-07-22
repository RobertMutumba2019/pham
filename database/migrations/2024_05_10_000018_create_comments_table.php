<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('comment_from');
            $table->unsignedBigInteger('comment_to');
            $table->text('comment_message');
            $table->string('comment_type');
            $table->timestamp('comment_date');
            $table->unsignedBigInteger('comment_part_id');
            $table->integer('comment_level')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
}; 