<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tutor_availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Only tutors
            $table->date('date');
            $table->time('start_hour');
            $table->time('end_hour');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tutor_availabilities');
    }
};

