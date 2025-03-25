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
            $table->boolean('done')->default(false);  // Add done column with default value of false
        });
    }
    
    public function down()
    {
        Schema::table('goals', function (Blueprint $table) {
            $table->dropColumn('done'); // Drop the done column when rolling back
        });
    }
    
};
