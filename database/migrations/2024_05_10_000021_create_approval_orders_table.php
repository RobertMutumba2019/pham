<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('approval_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('app_role_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approval_orders');
    }
}; 