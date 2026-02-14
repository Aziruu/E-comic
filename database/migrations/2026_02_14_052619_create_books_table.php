<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title_primary');      // Judul Utama
            $table->string('title_secondary')->nullable(); // Judul Alternatif
            $table->string('slug')->unique();     // Buat URL cantik (misal: /manga/solo-leveling)

            // "Series macem macem" (Manga, Manhwa, Webtoon) -> String bebas
            $table->string('type')->default('Manga');

            $table->foreignId('serialization_id')->nullable()->constrained('serializations')->onDelete('set null');
            $table->string('series')->nullable();
            $table->string('status_release')->default('Ongoing');
            $table->string('link_url')->nullable();
            $table->boolean('is_favorite')->default(false);

            $table->decimal('rating', 3, 1)->nullable(); // Skala 1.0 - 10.0
            $table->integer('total_chapters')->default(0);

            // Terakhir dibaca (Pake string biar bisa input 'Ch. 12.5')
            $table->string('last_read_chapter')->nullable();

            // Status baca (Reading, Plan to Read, Completed, Dropped)
            $table->string('status_reading')->default('Plan to Read');

            $table->text('synopsis')->nullable();
            $table->date('release_date')->nullable(); // Tanggal rilis (opsional)

            $table->timestamps(); // Created at & Updated at otomatis
        });
    }

    public function down()
    {
        Schema::dropIfExists('books');
    }
};
