<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('book_genre', function (Blueprint $table) {
            // Relasi ke books
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            
            // Relasi ke genres
            $table->foreignId('genre_id')->constrained()->onDelete('cascade');
            
            // Mencegah duplikat (satu buku gak bisa punya genre 'Action' dua kali)
            $table->primary(['book_id', 'genre_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('book_genre');
    }
};