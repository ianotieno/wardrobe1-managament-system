<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClothingItemsTable extends Migration
{
    public function up()
    {
        Schema::create('clothing_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Links to users table
            $table->string('name');
            $table->string('category'); // e.g., tops, bottoms, shoes
            $table->string('description')->nullable();
            $table->string('image')->nullable(); // Path to stored image
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('clothing_items');
    }
}