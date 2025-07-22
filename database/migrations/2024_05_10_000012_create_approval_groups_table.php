<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('approval_groups', function (Blueprint $table) {
            $table->id();
            $table->string('apg_name');
            $table->unsignedBigInteger('apg_user');
            $table->unsignedBigInteger('apg_added_by')->nullable();
            $table->timestamp('apg_date_added')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approval_groups');
    }
}; 