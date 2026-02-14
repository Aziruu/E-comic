<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    // Field yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'title_primary',
        'title_secondary',
        'slug',
        'type',
        'author',
        'serialization',
        'rating',
        'total_chapters',
        'last_read_chapter',
        'status_reading',
        'synopsis',
        'release_date'
    ];

    // Casting tipe data biar otomatis bener pas dipanggil
    protected $casts = [
        'rating' => 'float',
        'release_date' => 'date',
    ];

    // --- RELASI (RELATIONSHIPS) ---

    // 1. Relasi ke Cover (One to Many)
    // Cara panggil: $book->covers
    public function covers()
    {
        return $this->hasMany(BookCover::class);
    }

    // Helper: Ambil cover utama aja (buat thumbnail)
    // Cara panggil: $book->primary_cover
    public function getPrimaryCoverAttribute()
    {
        return $this->covers->where('is_primary', true)->first()
            ?? $this->covers->first(); // Kalau gak ada yang primary, ambil yang pertama aja
    }

    // 2. Relasi ke Genre (Many to Many)
    // Cara panggil: $book->genres
    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'book_genre', 'book_id', 'genre_id');
    }

    // 3. Relasi ke Character (One to Many)
    // Cara panggil: $book->characters
    public function characters()
    {
        return $this->hasMany(Character::class);
    }
}
