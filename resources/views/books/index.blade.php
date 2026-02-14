@extends('layouts.app')

@section('title', 'Library - MangaLib')

@section('content')
    <div class="main-content">

        <div class="search-container">
            <form action="{{ route('books.index') }}" method="GET">
                <div class="row g-3 align-items-center">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0 ps-3">
                                <i class="fa-solid fa-magnifying-glass text-muted"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-start-0 py-2"
                                placeholder="Cari Judul, Series..." value="{{ request('search') }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <select name="genre" class="form-select py-2" onchange="this.form.submit()">
                            <option value="">-- Semua Genre --</option>
                            @foreach ($genres as $genre)
                                <option value="{{ $genre->id }}" {{ request('genre') == $genre->id ? 'selected' : '' }}>
                                    {{ $genre->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select name="status" class="form-select py-2" onchange="this.form.submit()">
                            <option value="">-- Status --</option>
                            @foreach (['Reading', 'Plan to Read', 'Completed'] as $st)
                                <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>
                                    {{ $st }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 d-flex gap-2 justify-content-end">
                        <a href="{{ route('books.index') }}" class="btn btn-light py-2 text-muted fw-bold"
                            title="Reset Filter">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>

                        <a href="{{ route('books.create') }}" class="btn btn-primary py-2 fw-bold w-100 shadow-sm">
                            <i class="fa-solid fa-plus me-2"></i> Add Book
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="row">
            @forelse($books as $book)
                <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-6 mb-4">
                    <div class="card card-book h-100 shadow-sm">
                        <div class="position-relative" style="height: 320px; overflow: hidden; background: #f0f0f0;">
                            <a href="{{ route('books.show', $book->slug) }}" class="d-block w-100 h-100">
                                @if ($book->covers->count() > 0)
                                    <img src="{{ asset('storage/' . $book->primary_cover->image_path) }}"
                                        class="w-100 h-100 object-fit-cover lazy-cover"
                                        style="object-fit: cover; transition: opacity 0.3s;"
                                        alt="{{ $book->title_primary }}" loading="lazy"
                                        data-covers='@json($book->covers->pluck('image_path'))'>
                                @else
                                    <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                        <i class="fa-regular fa-image fs-1 opacity-50"></i>
                                    </div>
                                @endif
                            </a>

                            <span class="badge-rating-index">
                                <i class="fa-solid fa-star me-1"></i> {{ $book->rating ?? '-' }}
                            </span>
                            <span class="badge-type">{{ $book->type }}</span>

                            @if ($book->is_favorite)
                                <div class="position-absolute bottom-0 right-0 m-3 text-white fs-4"
                                    style="right: 10px; text-shadow: 0 2px 4px rgba(0,0,0,0.5);">
                                    <i class="fa-solid fa-bookmark" style="color: var(--c-blue);"></i>
                                </div>
                            @endif
                        </div>

                        <div class="card-body p-3 d-flex flex-column">
                            <h6 class="fw-bold mb-1 text-truncate-2-line" style="min-height: 40px;">
                                <a href="{{ route('books.show', $book->slug) }}" class="text-dark text-decoration-none">
                                    {{ $book->title_primary }}
                                </a>
                            </h6>
                            <small class="text-muted d-block mb-2 text-truncate">{{ $book->series ?? 'No Series' }}</small>

                            <div class="mt-auto d-flex justify-content-between align-items-center fs-12 fw-semibold">
                                @php
                                    $statusColor = match ($book->status_reading) {
                                        'Reading' => 'var(--c-green-1)',
                                        'Completed' => 'var(--c-blue)',
                                        'Dropped' => 'var(--c-red-villain)',
                                        default => 'var(--c-yellow-btn)',
                                    };
                                @endphp
                                <div class="d-flex align-items-center text-dark">
                                    <span class="status-dot" style="background-color: {{ $statusColor }}"></span>
                                    {{ $book->status_reading }}
                                </div>

                                <div class="text-muted">
                                    <i class="fa-solid fa-book-open me-1"></i> Ch. {{ $book->last_read_chapter ?? 0 }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="mb-3"><i class="fa-solid fa-box-open fs-1 text-muted opacity-25"
                            style="font-size: 4rem;"></i></div>
                    <h5 class="text-muted">Tidak ada manga yang ditemukan.</h5>
                    <a href="{{ route('books.create') }}" class="btn btn-primary mt-3">Tambah Manga Baru</a>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $books->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const covers = document.querySelectorAll('.lazy-cover');
            const storageUrl = "{{ asset('storage') }}/";

            // Logic IntersectionObserver (Hemat RAM)
            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.1
            };

            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        startAnimation(entry.target);
                    } else {
                        stopAnimation(entry.target);
                    }
                });
            }, observerOptions);

            covers.forEach(img => {
                const images = JSON.parse(img.getAttribute('data-covers'));
                img._covers = images;
                img._currentIndex = 0;
                img._interval = null;

                if (images.length > 1) {
                    imageObserver.observe(img);
                }
            });

            function startAnimation(img) {
                if (img._interval) return;
                // Delay random biar gak gerak barengan
                const randomDelay = Math.floor(Math.random() * 2000);

                setTimeout(() => {
                    const intervalTime = Math.floor(Math.random() * (6000 - 3000 + 1) + 3000);
                    img._interval = setInterval(() => {
                        img.style.opacity = 0.7;
                        setTimeout(() => {
                            img._currentIndex = (img._currentIndex + 1) % img._covers
                                .length;
                            img.src = storageUrl + img._covers[img._currentIndex];
                            img.style.opacity = 1;
                        }, 200);
                    }, intervalTime);
                }, randomDelay);
            }

            function stopAnimation(img) {
                if (img._interval) {
                    clearInterval(img._interval);
                    img._interval = null;
                }
            }

            document.addEventListener("visibilitychange", function() {
                if (document.hidden) {
                    covers.forEach(img => stopAnimation(img));
                }
            });
        });
    </script>
@endpush
