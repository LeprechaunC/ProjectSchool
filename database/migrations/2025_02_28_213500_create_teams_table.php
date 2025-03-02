<?php

// database/migrations/YYYY_MM_DD_HHMMSS_create_teams_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamsTable extends Migration
{
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();               // Auto-incrementing primary key
            $table->string('name');      // Name of the team
            $table->timestamps();       // created_at and updated_at timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('teams');
    }
}


