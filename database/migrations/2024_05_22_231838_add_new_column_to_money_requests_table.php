<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('money_requests', function (Blueprint $table) {
            $table->boolean('stauts')->default(1); // 1 under review, 2 completed, 0 cancenlled
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('money_requests', function (Blueprint $table) {
            //
        });
    }
};
