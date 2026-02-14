@extends('layouts.app')

@section('title', 'Edit Manga - ' . $book->title_primary)

@section('content')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Edit Manga</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('books.index') }}">Library</a></li>
                <li class="breadcrumb-item">{{ $book->title_primary }}</li>
                <li class="breadcrumb-item">Edit</li>
            </ul>
        </div>
    </div>

    <div class="main-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card stretch stretch-full">
                    <div class="card-body">
                        <form action="{{ route('books.update', $book->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-8">
                                    <h6 class="fw-bold mb-4">Information</h6>

                                    <div class="mb-3">
                                        <label class="form-label">Primary Title <span class="text-danger">*</span></label>
                                        <input type="text" name="title_primary" class="form-control"
                                            value="{{ old('title_primary', $book->title_primary) }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Secondary Title</label>
                                        <input type="text" name="title_secondary" class="form-control"
                                            value="{{ old('title_secondary', $book->title_secondary) }}">
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Authors / Artists</label>
                                            <div class="border rounded p-2" style="max-height: 150px; overflow-y: auto;">
                                                @foreach ($authors as $author)
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="author_ids[]"
                                                            value="{{ $author->id }}" id="auth{{ $author->id }}"
                                                            {{ $book->authors->contains($author->id) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="auth{{ $author->id }}">
                                                            {{ $author->name }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Serialization (Publisher)</label>
                                            <select name="serialization_id" class="form-select">
                                                <option value="">-- Select Publisher --</option>
                                                @foreach ($serializations as $serial)
                                                    <option value="{{ $serial->id }}"
                                                        {{ $book->serialization_id == $serial->id ? 'selected' : '' }}>
                                                        {{ $serial->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Synopsis</label>
                                        <textarea name="synopsis" class="form-control" rows="5">{{ old('synopsis', $book->synopsis) }}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <h6 class="fw-bold mb-4">Details & Status</h6>

                                    <div class="mb-3">
                                        <label class="form-label">Type</label>
                                        <select name="type" class="form-select">
                                            @foreach (['Manga', 'Manhwa', 'Manhua', 'Webtoon'] as $type)
                                                <option value="{{ $type }}"
                                                    {{ $book->type == $type ? 'selected' : '' }}>{{ $type }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Reading Status</label>
                                        <select name="status_reading" class="form-select">
                                            @foreach (['Reading', 'Plan to Read', 'Completed', 'Dropped', 'On Hold'] as $status)
                                                <option value="{{ $status }}"
                                                    {{ $book->status_reading == $status ? 'selected' : '' }}>
                                                    {{ $status }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="row">
                                        <div class="col-6 mb-3">
                                            <label class="form-label">Total Ch.</label>
                                            <input type="number" name="total_chapters" class="form-control"
                                                value="{{ old('total_chapters', $book->total_chapters) }}">
                                        </div>
                                        <div class="col-6 mb-3">
                                            <label class="form-label">Last Read</label>
                                            <input type="text" name="last_read_chapter" class="form-control"
                                                value="{{ old('last_read_chapter', $book->last_read_chapter) }}">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Rating</label>
                                        <input type="number" name="rating" class="form-control" step="0.1"
                                            max="10" value="{{ old('rating', $book->rating) }}">
                                    </div>
                                </div>
                            </div>

                            <hr class="border-dashed my-4">

                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="fw-bold mb-3">Book Covers</h6>
                                    <div class="d-flex gap-2 overflow-auto pb-2 mb-3">
                                        @foreach ($book->covers as $cover)
                                            <div class="position-relative" style="width: 80px; height: 120px;">
                                                <img src="{{ asset('storage/' . $cover->image_path) }}"
                                                    class="w-100 h-100 object-fit-cover rounded border">
                                                @if ($cover->is_primary)
                                                    <span class="position-absolute top-0 start-0 badge bg-primary"
                                                        style="font-size: 8px;">MAIN</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                    <input type="file" name="covers[]" class="form-control" multiple
                                        accept="image/*">
                                </div>

                                <div class="col-md-6">
                                    <h6 class="fw-bold mb-3">Genres</h6>
                                    <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach ($genres as $genre)
                                                <div class="form-check me-3">
                                                    <input class="form-check-input" type="checkbox" name="genre_ids[]"
                                                        value="{{ $genre->id }}" id="genre{{ $genre->id }}"
                                                        {{ $book->genres->contains($genre->id) ? 'checked' : '' }}>
                                                    <label class="form-check-label"
                                                        for="genre{{ $genre->id }}">{{ $genre->name }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 d-flex justify-content-end gap-2">
                                <a href="{{ route('books.show', $book->slug) }}" class="btn btn-light">Cancel</a>
                                <button type="submit" class="btn btn-primary">Update Manga</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
