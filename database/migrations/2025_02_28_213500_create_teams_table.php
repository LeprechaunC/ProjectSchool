<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('owner'); // Store the owner's user ID
            $table->string('invite_code')->unique(); // Unique invite code
            $table->timestamps();

            $table->foreign('owner')->references('id')->on('users')->onDelete('cascade'); // Ensure owner exists
        });
    }

    public function down()
    {
        Schema::dropIfExists('teams');
    }
};



