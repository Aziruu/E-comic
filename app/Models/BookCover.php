<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookCover extends Model
{
    use HasFactory;

    protected $fillable = ['book_id', 'image_path', 'is_primary'];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    // Kebalikan relasi: Cover ini punya buku apa?
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
