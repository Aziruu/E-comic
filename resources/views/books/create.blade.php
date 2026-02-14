@extends('layouts.app')

@section('title', 'Add New Manga - MangaLib')

@section('content')
<div class="page-header">
    <div class="page-header-left d-flex align-items-center">
        <div class="page-header-title">
            <h5 class="m-b-10">Add New Manga</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('books.index') }}">Library</a></li>
            <li class="breadcrumb-item">Create</li>
        </ul>
    </div>
</div>

<div class="main-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-body">
                    <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-8">
                                <h6 class="fw-bold mb-4">Information</h6>
                                
                                <div class="mb-3">
                                    <label class="form-label">Primary Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title_primary" class="form-control" placeholder="e.g. Solo Leveling" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Secondary Title (Alt/Jp/Kr)</label>
                                    <input type="text" name="title_secondary" class="form-control" placeholder="e.g. Na Honjaman Level Up">
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Author</label>
                                        <input type="text" name="author" class="form-control" placeholder="Author Name">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Serialization/Publisher</label>
                                        <input type="text" name="serialization" class="form-control" placeholder="e.g. Shonen Jump">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Synopsis</label>
                                    <textarea name="synopsis" class="form-control" rows="4" placeholder="Ceritanya tentang apa..."></textarea>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <h6 class="fw-bold mb-4">Details & Status</h6>

                                <div class="mb-3">
                                    <label class="form-label">Type</label>
                                    <select name="type" class="form-select">
                                        <option value="Manga">Manga (Jepang)</option>
                                        <option value="Manhwa">Manhwa (Korea)</option>
                                        <option value="Manhua">Manhua (China)</option>
                                        <option value="Webtoon">Webtoon</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Reading Status</label>
                                    <select name="status_reading" class="form-select">
                                        <option value="Reading">Reading</option>
                                        <option value="Plan to Read">Plan to Read</option>
                                        <option value="Completed">Completed</option>
                                        <option value="Dropped">Dropped</option>
                                        <option value="On Hold">On Hold</option>
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label class="form-label">Total Ch.</label>
                                        <input type="number" name="total_chapters" class="form-control" value="0">
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label">Last Read</label>
                                        <input type="text" name="last_read_chapter" class="form-control" placeholder="Ch. 1">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Personal Rating (1-10)</label>
                                    <input type="number" name="rating" class="form-control" step="0.1" min="0" max="10" placeholder="e.g. 8.5">
                                </div>
                            </div>
                        </div>

                        <hr class="border-dashed my-4">

                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3">Book Covers (Upload Multiple)</h6>
                                <div class="alert alert-soft-primary fs-12">
                                    Gambar pertama yang dipilih akan otomatis jadi <b>Cover Utama</b>.
                                </div>
                                <input type="file" name="covers[]" class="form-control" multiple accept="image/*">
                            </div>

                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3">Genres</h6>
                                <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                                    <div class="d-flex flex-wrap gap-2">
                                        @forelse($genres as $genre)
                                            <div class="form-check me-3">
                                                <input class="form-check-input" type="checkbox" name="genre_ids[]" value="{{ $genre->id }}" id="genre{{ $genre->id }}">
                                                <label class="form-check-label" for="genre{{ $genre->id }}">
                                                    {{ $genre->name }}
                                                </label>
                                            </div>
                                        @empty
                                            <p class="text-muted small">Belum ada genre. Tambahkan di database dulu.</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-end gap-2">
                            <a href="{{ route('books.index') }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save Manga</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection