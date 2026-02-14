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
        'serialization_id',
        'series',
        'status_release',
        'link_url',
        'is_favorite',
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
        'is_favorite' => 'boolean'
    ];

    // --- RELASI (RELATIONSHIPS) ---

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

    // Cara panggil: $book->genres
    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'book_genre', 'book_id', 'genre_id');
    }

    // Cara panggil: $book->characters
    public function characters()
    {
        return $this->hasMany(Character::class);
    }

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'book_author');
    }

    public function serialization()
    {
        return $this->belongsTo(Serialization::class);
    }
}
