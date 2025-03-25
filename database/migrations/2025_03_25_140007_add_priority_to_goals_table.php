<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('goals', function (Blueprint $table) {
            $table->enum('priority', ['high', 'medium', 'low'])->nullable()->default('medium'); // Add priority column
        });
    }
    
    public function down()
    {
        Schema::table('goals', function (Blueprint $table) {
            $table->dropColumn('priority'); // Drop the priority column if rolling back
        });
    }
    
};
