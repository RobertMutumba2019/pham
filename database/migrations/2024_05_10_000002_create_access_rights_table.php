<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('access_rights', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ar_role_id');
            $table->string('ar_page');
            $table->boolean('ar_a')->default(false);
            $table->boolean('ar_v')->default(false);
            $table->boolean('ar_e')->default(false);
            $table->boolean('ar_d')->default(false);
            $table->boolean('ar_p')->default(false);
            $table->boolean('ar_i')->default(false);
            $table->boolean('ar_x')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('access_rights');
    }
}; 