<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Genre;
use App\Models\BookCover;
use App\Models\Author;
use App\Models\Serialization;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index()
    {
        // Kita ambil buku + cover utamanya aja biar ringan
        // Paginasi 12 buku per halaman
        $query = Book::with('covers');

        $books = Book::with('covers')->latest()->paginate(12);
        $genres = Genre::orderBy('name')->get();

        return view('books.index', compact('books', 'genres'));
    }

    /**
     * FORM TAMBAH BUKU BARU
     */
    public function create()
    {
        // Kita butuh data Genre buat dipilih di checkbox/select2
        $genres = Genre::orderBy('name')->get();
        $authors = Author::orderBy('name')->get();
        $serializations = Serialization::orderBy('name')->get();

        return view('books.create', compact('genres', 'authors', 'serializations'));
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
            'link_url' => 'nullable|url',
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
            'serialization_id' => $request->serialization_id,
            'series' => $request->series,
            'status_release' => $request->status_release ?? 'Ongoing',
            'link_url' => $request->link_url,
            'is_favorite' => $request->has('is_favorite'),
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

        if ($request->has('author_ids')) {
            $book->authors()->attach($request->author_ids);
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
        $authors = Author::orderBy('name')->get();
        $serializations = Serialization::orderBy('name')->get();

        return view('books.edit', compact('book', 'genres', 'authors', 'serializations'));
    }

    /**
     * PROSES UPDATE DATA
     */
    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title_primary' => 'required|string|max:255',
            'link_url' => 'nullable|url',
        ]);

        // Update Data Dasar
        $data = $request->except(['covers', 'genre_ids', 'author_ids']);
        $data['is_favorite'] = $request->has('is_favorite');

        $book->update($data);

        // Sync Genre
        $book->genres()->sync($request->genre_ids ?? []);

        // Sync Authors
        $book->authors()->sync($request->author_ids ?? []);

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

        foreach ($book->characters as $char) {
            if ($char->image_path) {
                Storage::disk('public')->delete($char->image_path);
            }
        }

        // Hapus data buku (otomatis hapus cover & relasi genre di DB karena 'cascade')
        $book->delete();

        return redirect()->route('books.index')->with('success', 'Manga telah dihapus :(');
    }
}
