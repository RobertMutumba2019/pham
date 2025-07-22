<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requisitions', function (Blueprint $table) {
            $table->id();
            $table->string('req_number')->nullable();
            $table->string('req_title');
            $table->string('req_division');
            $table->string('req_ref');
            $table->unsignedBigInteger('req_added_by');
            $table->timestamp('req_date_added')->nullable();
            $table->integer('req_status')->default(-1);
            $table->unsignedBigInteger('req_hod_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requisitions');
    }
}; 