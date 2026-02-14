<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'name',
        'role',
        'image_path',
        'description',
        'is_favorite',
        'status'
    ];

    protected $casts = [
        'is_favorite' => 'boolean',
    ];

    // Relasi: Tokoh ini muncul di buku mana?
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
