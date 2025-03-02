<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoalsTable extends Migration
{
    public function up()
    {
        Schema::create('goals', function (Blueprint $table) {
            $table->id();                          
            $table->string('title');                 
            $table->string('description');          
            $table->dateTime('start_time');          
            $table->dateTime('end_time');            
            $table->foreignId('team_id')->constrained()->onDelete('cascade'); 
            $table->timestamps();                   
        });
    }

    public function down()
    {
        Schema::dropIfExists('goals');
    }
}
