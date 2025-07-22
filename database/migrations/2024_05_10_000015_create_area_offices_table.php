<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('area_offices', function (Blueprint $table) {
            $table->id();
            $table->string('area_office_name')->unique();
            $table->unsignedBigInteger('area_office_district_code_id');
            $table->unsignedBigInteger('area_office_territory_id');
            $table->string('area_office_cost_center');
            $table->unsignedBigInteger('area_office_added_by')->nullable();
            $table->timestamp('area_office_date_added')->nullable();
            $table->integer('area_office_status')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('area_offices');
    }
}; 