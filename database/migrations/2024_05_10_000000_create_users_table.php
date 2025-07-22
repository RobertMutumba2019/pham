<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('user_name')->unique();
            $table->string('user_surname');
            $table->string('user_othername')->nullable();
            $table->integer('user_status')->default(1);
            $table->string('user_email')->unique();
            $table->string('user_telephone')->nullable();
            $table->string('user_gender')->nullable();
            $table->string('user_password');
            $table->timestamp('user_date_added')->nullable();
            $table->unsignedBigInteger('user_added_by')->nullable();
            $table->string('user_role');
            $table->boolean('user_forgot_password')->default(0);
            $table->boolean('user_active')->default(1);
            $table->string('check_number')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
}; 