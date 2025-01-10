<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Post title
            $table->text('content'); // Post content
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // User who created the post
            $table->foreignId('categories_id')->constrained()->onDelete('cascade'); // Category of the post
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};

