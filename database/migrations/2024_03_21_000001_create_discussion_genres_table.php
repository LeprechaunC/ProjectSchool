<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('discussion_genres', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Add genre_id to discussions table
        Schema::table('discussions', function (Blueprint $table) {
            $table->foreignId('genre_id')->after('user_id')->constrained('discussion_genres')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('discussions', function (Blueprint $table) {
            $table->dropForeign(['genre_id']);
            $table->dropColumn('genre_id');
        });
        Schema::dropIfExists('discussion_genres');
    }
}; 