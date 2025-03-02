<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamUserTable extends Migration
{
    public function up()
    {
        Schema::create('team_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->onDelete('cascade'); // References the teams table
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // References the users table
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('team_user');
    }
}
