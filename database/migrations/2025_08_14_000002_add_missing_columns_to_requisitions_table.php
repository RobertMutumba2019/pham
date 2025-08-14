<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('requisitions', function (Blueprint $table) {
            $table->text('req_description')->nullable()->after('req_title');
            $table->enum('req_priority', ['Normal', 'High', 'Urgent'])->default('Normal')->after('req_description');
            $table->date('req_date_needed')->nullable()->after('req_priority');
            $table->text('req_justification')->nullable()->after('req_date_needed');
        });
    }

    public function down(): void
    {
        Schema::table('requisitions', function (Blueprint $table) {
            $table->dropColumn(['req_description', 'req_priority', 'req_date_needed', 'req_justification']);
        });
    }
};
