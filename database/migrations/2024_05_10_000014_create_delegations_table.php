<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delegations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('del_to');
            $table->timestamp('del_start_date')->nullable();
            $table->timestamp('del_end_date')->nullable();
            $table->string('del_reason');
            $table->unsignedBigInteger('del_added_by')->nullable();
            $table->timestamp('del_date_added')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delegations');
    }
}; 