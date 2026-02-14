<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Genre;
use App\Models\BookCover;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * TAMPILKAN SEMUA BUKU (Library)
     */
    public function index()
    {
        // Kita ambil buku + cover utamanya aja biar ringan
        // Paginasi 12 buku per halaman
        $books = Book::with('covers')->latest()->paginate(12);
        
        return view('books.index', compact('books'));
    }

    /**
     * FORM TAMBAH BUKU BARU
     */
    public function create()
    {
        // Kita butuh data Genre buat dipilih di checkbox/select2
        $genres = Genre::orderBy('name')->get();
        
        return view('books.create', compact('genres'));
    }

    /**
     * PROSES SIMPAN DATA (Logic Utama)
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'title_primary' => 'required|string|max:255',
            'type' => 'required',
            'covers.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048', // Validasi tiap file gambar
            'genre_ids' => 'array' // Pastikan genre dikirim sebagai array ID
        ]);

        // 2. Buat Slug Otomatis kalau kosong
        // Misal judul: "Solo Leveling" -> slug: "solo-leveling"
        $slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->title_primary);
        
        // Cek biar slug gak kembar
        if (Book::where('slug', $slug)->exists()) {
            $slug = $slug . '-' . time();
        }

        // 3. Simpan Data Buku
        $book = Book::create([
            'title_primary' => $request->title_primary,
            'title_secondary' => $request->title_secondary,
            'slug' => $slug,
            'type' => $request->type,
            'author' => $request->author,
            'serialization' => $request->serialization,
            'rating' => $request->rating,
            'total_chapters' => $request->total_chapters,
            'last_read_chapter' => $request->last_read_chapter,
            'status_reading' => $request->status_reading,
            'synopsis' => $request->synopsis,
            'release_date' => $request->release_date,
        ]);

        // 4. Sambungkan Genre (Many-to-Many)
        // $request->genre_ids isinya array [1, 5, 8] dsb.
        if ($request->has('genre_ids')) {
            $book->genres()->attach($request->genre_ids);
        }

        // 5. Handle Upload Cover (Bisa Banyak)
        if ($request->hasFile('covers')) {
            foreach ($request->file('covers') as $index => $image) {
                // Simpan ke folder: storage/app/public/covers
                $path = $image->store('covers', 'public');

                // Simpan ke database
                BookCover::create([
                    'book_id' => $book->id,
                    'image_path' => $path,
                    'is_primary' => $index == 0 ? true : false, // Gambar pertama jadi cover utama
                ]);
            }
        }

        return redirect()->route('books.index')->with('success', 'Manga berhasil ditambahkan ke library!');
    }

    /**
     * HALAMAN DETAIL BUKU
     */
    public function show($slug)
    {
        // Ambil buku berdasarkan slug, lengkap sama Genre, Cover, dan Karakter
        $book = Book::with(['genres', 'covers', 'characters'])->where('slug', $slug)->firstOrFail();

        return view('books.show', compact('book'));
    }

    /**
     * FORM EDIT BUKU
     */
    public function edit(Book $book)
    {
        $genres = Genre::orderBy('name')->get();
        return view('books.edit', compact('book', 'genres'));
    }

    /**
     * PROSES UPDATE DATA
     */
    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title_primary' => 'required|string|max:255',
        ]);

        // Update Data Dasar
        $book->update($request->except(['covers', 'genre_ids'])); // Update semua kecuali cover & genre

        // Update Genre (Pakai sync biar yang nggak dicentang otomatis kehapus)
        if ($request->has('genre_ids')) {
            $book->genres()->sync($request->genre_ids);
        } else {
            $book->genres()->detach(); // Kalau kosong semua, hapus relasi genre
        }

        // Tambah Cover Baru (Kalau ada upload lagi)
        if ($request->hasFile('covers')) {
            foreach ($request->file('covers') as $image) {
                $path = $image->store('covers', 'public');
                BookCover::create([
                    'book_id' => $book->id,
                    'image_path' => $path,
                    'is_primary' => false, // Cover tambahan bukan primary
                ]);
            }
        }

        return redirect()->route('books.show', $book->slug)->with('success', 'Data manga berhasil diupdate!');
    }

    /**
     * HAPUS BUKU
     */
    public function destroy(Book $book)
    {
        // Hapus file fisik gambar covernya dulu biar gak nyampah di server
        foreach ($book->covers as $cover) {
            Storage::disk('public')->delete($cover->image_path);
        }

        // Hapus data buku (otomatis hapus cover & relasi genre di DB karena 'cascade')
        $book->delete();

        return redirect()->route('books.index')->with('success', 'Manga telah dihapus :(');
    }
}