@extends('layouts.app')

@section('title', 'Library - MangaLib')

@section('content')
    <div class="main-content">

        <div class="header-action-container">
            <form action="{{ route('books.index') }}" method="GET" class="search-box-custom">
                <input type="text" name="search" placeholder="Cari Buku Kamu Yuk!" value="{{ request('search') }}">
                <button type="submit" class="search-icon-btn">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>

            <button type="button" class="btn-action-square" onclick="toggleFilter()" title="Filter & Sort">
                <i class="fa-solid fa-sort"></i>
            </button>

            <a href="{{ route('books.create') }}" class="btn-action-square" title="Tambah Buku">
                <i class="fa-solid fa-plus"></i>
            </a>
        </div>

        <div id="filterPanel" class="filter-collapse">
            <form action="{{ route('books.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="small text-muted mb-1">Genre</label>
                        <select name="genre" class="form-select border-0 bg-light" onchange="this.form.submit()">
                            <option value="">Semua Genre</option>
                            @foreach ($genres as $genre)
                                <option value="{{ $genre->id }}" {{ request('genre') == $genre->id ? 'selected' : '' }}>
                                    {{ $genre->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="small text-muted mb-1">Status</label>
                        <select name="status" class="form-select border-0 bg-light" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            @foreach (['Reading', 'Plan to Read', 'Completed'] as $st)
                                <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>
                                    {{ $st }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <div class="row g-4">
            @forelse($books as $book)
                <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-6">
                    <div class="manga-card">

                        <div class="manga-cover-wrap">
                            <a href="{{ route('books.show', $book->slug) }}">
                                @if ($book->covers->count() > 0)
                                    <img src="{{ asset('storage/' . $book->primary_cover->image_path) }}"
                                        class="lazy-cover" loading="lazy" alt="{{ $book->title_primary }}"
                                        data-covers='@json($book->covers->pluck('image_path'))'>
                                @else
                                    <div class="d-flex align-items-center justify-content-center h-100 text-muted bg-light">
                                        <i class="fa-regular fa-image fs-1 opacity-25"></i>
                                    </div>
                                @endif
                            </a>

                            <div class="badge-rating-box">
                                {{ $book->rating ?? '0.0' }}
                            </div>

                            <div class="badge-bookmark-icon">
                                @if ($book->is_favorite)
                                    <i class="fa-solid fa-bookmark"></i>
                                @else
                                    <i class="fa-regular fa-bookmark text-white opacity-0"></i>
                                @endif
                            </div>

                            <div class="badge-type-pill">
                                @php
                                    $flagCode = match ($book->type) {
                                        'Manga' => 'jp', // Jepang
                                        'Manhwa' => 'kr', // Korea
                                        'Manhua' => 'cn', // China
                                        default => 'un', // United Nations (Global/Unknown)
                                    };
                                @endphp

                                <img src="https://flagcdn.com/20x15/{{ $flagCode }}.png" alt="{{ $flagCode }}"
                                    style="border-radius: 2px; height: 12px; margin-right: 4px;">

                                <span>{{ $book->type }}</span>
                            </div>

                            <div class="badge-status-overlay">
                                <span class="dot-status"></span>
                                <span>{{ $book->status_reading }}</span>
                            </div>
                        </div>

                        <div class="manga-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="w-100 overflow-hidden">
                                    <a href="{{ route('books.show', $book->slug) }}" class="manga-title"
                                        title="{{ $book->title_primary }}">
                                        {{ $book->title_primary }}
                                    </a>
                                    <div class="manga-series" title="{{ $book->series ?? $book->title_secondary }}">
                                        {{ $book->series ?? 'No Series' }}
                                    </div>
                                </div>

                                <div class="dropdown">
                                    <i class="fa-solid fa-ellipsis-vertical menu-dots" data-bs-toggle="dropdown"></i>
                                    <ul class="dropdown-menu dropdown-menu-end custom-dropdown-menu">
                                        <li>
                                            <a class="dropdown-item dropdown-item-custom item-edit"
                                                href="{{ route('books.edit', $book->id) }}">
                                                <i class="fa-regular fa-pen-to-square"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ route('books.destroy', $book->id) }}" method="POST"
                                                onsubmit="return confirm('Hapus buku ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="dropdown-item dropdown-item-custom item-delete w-100">
                                                    <i class="fa-solid fa-trash-can"></i> Hapus
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="manga-footer">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-book-open text-muted"></i>
                                    <span>Ch. {{ $book->total_chapters ?? '-' }}</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <h5 class="text-muted">Tidak ada buku. Yuk tambah baru!</h5>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center mt-5">
            {{ $books->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Script Toggle Filter
        function toggleFilter() {
            const panel = document.getElementById('filterPanel');
            panel.classList.toggle('show');
        }

        // Script Lazy Load & Animasi Cover (Sama seperti sebelumnya)
        document.addEventListener("DOMContentLoaded", function() {
            const covers = document.querySelectorAll('.lazy-cover');
            const storageUrl = "{{ asset('storage') }}/";

            // ... (Copy script IntersectionObserver yang kemarin disini) ...
            // Kalo mau saya tulis ulang full bilang aja ya!
            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.1
            };
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) startAnimation(entry.target);
                    else stopAnimation(entry.target);
                });
            }, observerOptions);

            covers.forEach(img => {
                const images = JSON.parse(img.getAttribute('data-covers'));
                img._covers = images;
                img._currentIndex = 0;
                if (images.length > 1) imageObserver.observe(img);
            });

            function startAnimation(img) {
                if (img._interval) return;
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
                }, Math.floor(Math.random() * 2000));
            }

            function stopAnimation(img) {
                if (img._interval) {
                    clearInterval(img._interval);
                    img._interval = null;
                }
            }
        });
    </script>
@endpush
