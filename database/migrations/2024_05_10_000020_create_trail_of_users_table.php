<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trail_of_users', function (Blueprint $table) {
            $table->id();
            $table->text('trail_sql');
            $table->timestamp('trail_date');
            $table->unsignedBigInteger('trail_user');
            $table->string('trail_page');
            $table->string('trail_browser');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trail_of_users');
    }
}; 