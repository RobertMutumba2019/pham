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
            $table->foreignId('requisition_id')->constrained('requisitions')->onDelete('cascade');
            $table->string('item_description');
            $table->integer('item_quantity');
            $table->string('item_unit')->nullable();
            $table->decimal('item_estimated_cost', 10, 2)->nullable();
            
            // Keep old fields for backward compatibility
            $table->string('ri_code')->nullable();
            $table->integer('ri_quantity')->nullable();
            $table->string('ri_uom')->nullable();
            $table->text('ri_description')->nullable();
            $table->string('ri_ref')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requisition_items');
    }
};
