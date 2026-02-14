@extends('layouts.app')

@section('title', $book->title_primary . ' - MangaLib')

@section('content')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Manga Details</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('books.index') }}">Library</a></li>
                <li class="breadcrumb-item">{{ $book->title_primary }}</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto">
            <a href="{{ route('books.edit', $book->id) }}" class="btn btn-warning text-dark">
                <i class="feather-edit me-2"></i> Edit Manga
            </a>
        </div>
    </div>

    <div class="main-content">
        <div class="row">
            <div class="col-xl-3 col-lg-4">
                <div class="card stretch stretch-full overflow-hidden">
                    <div class="position-relative" style="height: 450px;">
                        @if ($book->covers->count() > 0)
                            <img id="dynamic-cover" src="{{ asset('storage/' . $book->primary_cover->image_path) }}"
                                alt="Cover" class="w-100 h-100 object-fit-cover"
                                style="object-fit: cover; transition: opacity 0.5s ease-in-out;">

                            @if ($book->covers->count() > 1)
                                <div
                                    class="position-absolute bottom-0 start-0 w-100 p-2 bg-gradient-dark text-white text-center fs-12">
                                    <div class="spinner-grow spinner-grow-sm text-danger me-1" role="status"></div>
                                    Live Preview
                                </div>
                            @endif
                        @else
                            <div class="d-flex align-items-center justify-content-center h-100 bg-light text-muted">
                                <i class="feather-image fs-1"></i>
                            </div>
                        @endif
                    </div>

                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary">
                                <i class="feather-book-open me-2"></i> Read Ch. {{ $book->last_read_chapter ?? 1 }}
                            </button>

                            <div class="p-3 border border-dashed rounded mt-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Status</span>
                                    <span class="fw-bold">{{ $book->status_reading }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Rating</span>
                                    <span class="fw-bold text-warning"><i class="feather-star"></i>
                                        {{ $book->rating ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-9 col-lg-8">
                <div class="card stretch stretch-full">
                    <div class="card-body">
                        <div class="mb-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <h2 class="fw-bold text-dark mb-1">{{ $book->title_primary }}</h2>
                                <span class="badge bg-soft-primary text-primary fs-12">{{ $book->type }}</span>
                            </div>
                            <p class="text-muted fs-16 fst-italic">{{ $book->title_secondary ?? '-' }}</p>

                            <div class="mt-3">
                                @forelse($book->genres as $genre)
                                    <span
                                        class="badge border border-gray-300 text-dark me-1 mb-1">{{ $genre->name }}</span>
                                @empty
                                    <span class="text-muted small">No genres added.</span>
                                @endforelse
                            </div>
                        </div>

                        <h5 class="fw-bold border-bottom pb-2 mb-3">Synopsis</h5>
                        <p class="text-muted" style="line-height: 1.8;">
                            {{ $book->synopsis ?? 'Belum ada sinopsis.' }}
                        </p>

                        <div class="row mt-4">
                            <div class="col-md-6 mb-3">
                                <small class="text-muted d-block mb-1">Author / Artist</small>
                                @forelse($book->authors as $author)
                                    <span class="fw-bold text-dark d-block">• {{ $author->name }}</span>
                                @empty
                                    <span class="text-muted">-</span>
                                @endforelse
                            </div>

                            <div class="col-md-6 mb-3">
                                <small class="text-muted d-block mb-1">Serialization</small>
                                <span class="fw-bold text-primary">{{ $book->serialization->name ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card stretch stretch-full">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Characters</h5>
                        <a href="{{ route('characters.create', $book->id) }}" class="btn btn-sm btn-light-brand">
                            <i class="feather-plus"></i> Add Character
                        </a>
                    </div>
                    <div class="card-body">
                        @if ($book->characters->count() > 0)
                            <div class="row">
                                @foreach ($book->characters as $char)
                                    <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                                        <div
                                            class="d-flex align-items-center gap-3 p-2 border rounded hover-bg-light position-relative">
                                            <div class="position-absolute top-0 end-0 mt-1 me-1 d-flex gap-1">
                                                <a href="{{ route('characters.edit', $char->id) }}"
                                                    class="btn btn-sm p-0 text-muted hover-primary"><i
                                                        class="feather-edit-2"></i></a>
                                                <form action="{{ route('characters.destroy', $char->id) }}" method="POST"
                                                    onsubmit="return confirm('Hapus karakter ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm p-0 text-muted hover-danger"
                                                        style="line-height: 1;">&times;</button>
                                                </form>
                                            </div>
                                            <div class="avatar-image avatar-lg rounded-circle">
                                                <img src="{{ $char->image_path ? asset('storage/' . $char->image_path) : asset('assets/images/avatar/1.png') }}"
                                                    class="img-fluid"
                                                    style="filter: {{ $char->status == 'Deceased' ? 'grayscale(100%)' : 'none' }}">
                                            </div>
                                            <div>
                                                <a href="#" class="fw-bold text-dark d-block">{{ $char->name }}</a>
                                                <span class="fs-11 text-muted">{{ $char->role }}</span>
                                                @if ($char->is_favorite)
                                                    <span class="text-danger ms-1">❤️</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4 text-muted"><i class="feather-users fs-1 mb-2 d-block"></i>Belum
                                ada data tokoh.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const covers = @json($book->covers->pluck('image_path'));
        
        if (covers.length <= 1) return; // Stop kalau cover cuma 1

        const imgElement = document.getElementById('dynamic-cover');
        const storageUrl = "{{ asset('storage') }}/";
        let currentIndex = 0;

        // Ganti gambar tiap 3 detik
        setInterval(() => {
            imgElement.style.opacity = 0.6; // Fade out

            setTimeout(() => {
                currentIndex = (currentIndex + 1) % covers.length;
                imgElement.src = storageUrl + covers[currentIndex];
                imgElement.style.opacity = 1; // Fade in
            }, 250); // Delay transisi halus

        }, 3000); 
    });
</script>
@endpush