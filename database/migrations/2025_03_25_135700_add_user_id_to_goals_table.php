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
            $table->unsignedBigInteger('user_id'); // Add user_id column
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // Add foreign key constraint if needed
        });
    }
    
    public function down()
    {
        Schema::table('goals', function (Blueprint $table) {
            $table->dropColumn('user_id'); // Drop the column when rolling back
        });
    }
    
};
