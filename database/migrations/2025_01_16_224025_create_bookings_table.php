<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id'); // Student who created the booking
            $table->unsignedBigInteger('tutor_id');  // Tutor the booking is about
            $table->unsignedBigInteger('category_id'); // Subject category
            $table->date('date'); // Booking date
            $table->time('start_hour'); // Start hour
            $table->time('end_hour'); // End hour
            $table->enum('status', ['pending', 'rejected', 'accepted'])->default('pending'); // Status
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('tutor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
