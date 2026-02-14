<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('characters', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke buku
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
            
            $table->string('name');
            $table->string('role')->nullable(); // Main, Villain, Support
            $table->string('status')->default('Alive');
            $table->string('image_path')->nullable(); // Foto karakter
            $table->text('description')->nullable(); // Penjelasan singkat
            $table->boolean('is_favorite')->default(false); // Karakter favorit
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('characters');
    }
};