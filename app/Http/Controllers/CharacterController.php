<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Character;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CharacterController extends Controller
{
    // Form Tambah Karakter (Butuh ID Buku biar tau ini tokoh manga apa)
    public function create(Book $book)
    {
        return view('characters.create', compact('book'));
    }

    // Proses Simpan ke Database
    public function store(Request $request, Book $book)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string', // Main, Villain, Support
            'image' => 'nullable|image|max:2048', // Foto wajah
            'status' => 'required', // Alive / Deceased
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('characters', 'public');
        }

        Character::create([
            'book_id' => $book->id,
            'name' => $request->name,
            'role' => $request->role,
            'status' => $request->status,
            'description' => $request->description,
            'is_favorite' => $request->has('is_favorite'), // Checkbox: kalau dicentang jadi true
            'image_path' => $imagePath,
        ]);

        return redirect()->route('books.show', $book->slug)->with('success', 'Karakter berhasil ditambahkan!');
    }

    public function edit(Character $character)
    {
        // Kita butuh data buku juga buat tombol "Back"
        $book = $character->book;
        return view('characters.edit', compact('character', 'book'));
    }

    // 2. PROSES UPDATE DATA
    public function update(Request $request, Character $character)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'status' => 'required',
        ]);

        // Logic Ganti Gambar
        if ($request->hasFile('image')) {
            // 1. Hapus gambar lama kalau ada (biar server gak penuh)
            if ($character->image_path) {
                Storage::disk('public')->delete($character->image_path);
            }
            // 2. Upload gambar baru
            $imagePath = $request->file('image')->store('characters', 'public');
        } else {
            // Kalau gak upload baru, pakai yang lama
            $imagePath = $character->image_path;
        }

        // Update Database
        $character->update([
            'name' => $request->name,
            'role' => $request->role,
            'status' => $request->status,
            'description' => $request->description,
            'is_favorite' => $request->has('is_favorite'), // Checkbox logic
            'image_path' => $imagePath,
        ]);

        return redirect()->route('books.show', $character->book->slug)
                         ->with('success', 'Data karakter berhasil diperbarui!');
    }

    // Hapus Karakter
    public function destroy(Character $character)
    {
        // Hapus foto kalau ada
        if ($character->image_path) {
            Storage::disk('public')->delete($character->image_path);
        }

        // Ambil slug buku dulu sebelum dihapus buat redirect
        $bookSlug = $character->book->slug;

        $character->delete();

        return redirect()->route('books.show', $bookSlug)->with('success', 'Karakter dihapus.');
    }
}
