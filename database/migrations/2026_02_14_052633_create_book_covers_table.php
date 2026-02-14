<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('book_covers', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel books
            // onDelete('cascade') artinya kalau bukunya dihapus, covernya ikut kehapus
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
            
            $table->string('image_path'); // Path file gambar
            $table->boolean('is_primary')->default(false); // Penanda cover utama
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('book_covers');
    }
};