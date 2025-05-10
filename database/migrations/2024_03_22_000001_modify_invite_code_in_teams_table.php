<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->string('invite_code')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->string('invite_code')->nullable(false)->change();
        });
    }
}; 