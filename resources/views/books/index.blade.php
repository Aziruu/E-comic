@extends('layouts.app')

@section('title', 'Library - MangaLib')

@section('content')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Library</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item">Books</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto">
            <a href="{{ route('books.create') }}" class="btn btn-primary">
                <i class="feather-plus me-2"></i> Add New Manga
            </a>
        </div>
    </div>
    <div class="main-content">
        <div class="row">
            @if (session('success'))
                <div class="col-12 mb-4">
                    <div class="alert alert-success">{{ session('success') }}</div>
                </div>
            @endif

            @forelse($books as $book)
                <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-6 mb-4">
                    <div class="card stretch stretch-full">
                        <div class="position-relative" style="height: 300px; overflow: hidden;">
                            @if ($book->covers->count() > 0)
                                <img src="{{ asset('storage/' . $book->primary_cover->image_path) }}"
                                    class="card-img-top w-100 h-100 object-fit-cover auto-cycle-cover"
                                    style="object-fit: cover; transition: opacity 0.5s ease-in-out;"
                                    alt="{{ $book->title_primary }}" data-covers='@json($book->covers->pluck('image_path'))'
                                    data-interval="{{ rand(3000, 6000) }}">
                            @else
                                <div
                                    class="d-flex align-items-center justify-content-center bg-gray-200 h-100 w-100 text-muted">
                                    <div class="text-center">
                                        <i class="feather-image fs-1"></i><br>No Cover
                                    </div>
                                </div>
                            @endif

                            <span class="badge bg-primary position-absolute top-0 end-0 m-3">{{ $book->type }}</span>

                            <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-3">
                                <i class="feather-star me-1"></i> {{ $book->rating ?? '-' }}
                            </span>
                        </div>

                        <div class="card-body">
                            <h5 class="text-truncate-1-line mb-1">
                                <a href="{{ route('books.show', $book->slug) }}"
                                    class="text-dark">{{ $book->title_primary }}</a>
                            </h5>
                            <p class="text-muted fs-12 text-truncate-1-line mb-3">{{ $book->title_secondary ?? '-' }}</p>

                            <div class="d-flex justify-content-between align-items-center">
                                <div class="fs-12">
                                    <i class="feather-book-open me-1"></i> Ch. {{ $book->last_read_chapter ?? 0 }} /
                                    {{ $book->total_chapters }}
                                </div>

                                @php
                                    $statusColor = match ($book->status_reading) {
                                        'Reading' => 'success',
                                        'Plan to Read' => 'warning',
                                        'Completed' => 'primary',
                                        'Dropped' => 'danger',
                                        default => 'secondary',
                                    };
                                @endphp
                                <span class="badge bg-soft-{{ $statusColor }} text-{{ $statusColor }}">
                                    {{ $book->status_reading }}
                                </span>
                            </div>
                        </div>

                        <div class="card-footer d-flex justify-content-between">
                            <a href="{{ route('books.edit', $book->id) }}" class="btn btn-sm btn-light-brand">Edit</a>
                            <form action="{{ route('books.destroy', $book->id) }}" method="POST"
                                onsubmit="return confirm('Yakin mau hapus manga ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light-danger"><i
                                        class="feather-trash-2"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <img src="{{ asset('assets/images/no-data.svg') }}" alt="Empty" style="width: 150px; opacity: 0.5;">
                    <h4 class="mt-3 text-muted">Library Masih Kosong</h4>
                    <p>Ayo mulai tambahkan koleksi manga kamu!</p>
                    <a href="{{ route('books.create') }}" class="btn btn-primary mt-2">Tambah Manga Pertama</a>
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
            const covers = document.querySelectorAll('.auto-cycle-cover');
            const storageUrl = "{{ asset('storage') }}/";

            covers.forEach(img => {
                // Ambil data cover dari attribute
                const images = JSON.parse(img.getAttribute('data-covers'));

                // Kalau cover cuma 1, gak usah animasi
                if (images.length <= 1) return;

                // Ambil interval random biar gak gerak barengan
                const intervalTime = parseInt(img.getAttribute('data-interval'));
                let currentIndex = 0;

                setInterval(() => {
                    // Efek kedip dikit pas ganti
                    img.style.opacity = 0.7;

                    setTimeout(() => {
                        currentIndex = (currentIndex + 1) % images.length;
                        img.src = storageUrl + images[currentIndex];
                        img.style.opacity = 1;
                    }, 200);

                }, intervalTime);
            });
        });
    </script>
@endpush
