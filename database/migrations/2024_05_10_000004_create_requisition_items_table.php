<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requisition_items', function (Blueprint $table) {
            $table->id();
            $table->string('ri_code');
            $table->integer('ri_quantity');
            $table->string('ri_uom');
            $table->text('ri_description')->nullable();
            $table->string('ri_ref');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requisition_items');
    }
}; 