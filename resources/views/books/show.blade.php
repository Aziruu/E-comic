@extends('layouts.app')

@section('title', $book->title_primary . ' - MangaLib')

@section('content')

    <div class="main-content">
        <div class="row">

            <div class="col-xl-3 col-lg-4 mb-4">
                <div class="card border-0 radius-8 mb-3 overflow-hidden shadow-sm">
                    <div class="position-relative" style="height: 400px;">
                        @if ($book->covers->count() > 0)
                            <img id="dynamic-cover" src="{{ asset('storage/' . $book->primary_cover->image_path) }}"
                                alt="Cover" class="w-100 h-100 object-fit-cover"
                                style="object-fit: cover; transition: opacity 0.5s ease-in-out;">
                        @else
                            <div class="d-flex align-items-center justify-content-center h-100 bg-light text-muted">
                                <i class="fa-solid fa-image fs-1"></i>
                            </div>
                        @endif
                    </div>
                </div>

                <a href="{{ $book->link_url ?? '#' }}" target="_blank" class="btn btn-baca mb-3 shadow-sm">
                    <i class="fa-solid fa-book-open me-2"></i> Baca Buku
                </a>

                <a href="{{ route('books.edit', $book->id) }}" class="btn btn-edit-custom shadow-sm">
                    <i class="fa-regular fa-pen-to-square me-2"></i> Edit Buku
                </a>
            </div>

            <div class="col-xl-9 col-lg-8">

                <div class="card border-0 radius-8 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h2 class="fw-bold text-dark mb-0">{{ $book->title_primary }}</h2>
                                <h5 class="fw-bold mb-3" style="color: var(--c-grey-text);">
                                    {{ $book->title_secondary ?? '' }}</h5>
                            </div>
                            <div class="icon-bookmark">
                                @if ($book->is_favorite)
                                    <i class="fa-solid fa-bookmark"></i>
                                @else
                                    <i class="fa-regular fa-bookmark"></i>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3 fs-13 fw-bold text-dark">
                            <div class="col-md-7">
                                <div class="row mb-1">
                                    <div class="col-4">Status</div>
                                    <div class="col-8">: {{ $book->status_release ?? '-' }}</div>
                                </div>

                                <div class="row mb-1">
                                    <div class="col-4">Type</div>
                                    <div class="col-8">: {{ $book->type }}</div>
                                </div>

                                @if ($book->series)
                                    <div class="row mb-1">
                                        <div class="col-4">Series</div>
                                        <div class="col-8">: {{ $book->series }}</div>
                                    </div>
                                @endif

                                @if ($book->authors->count() > 0)
                                    <div class="row mb-1">
                                        <div class="col-4">Author</div>
                                        <div class="col-8">:
                                            @foreach ($book->authors as $author)
                                                {{ $author->name }}@if (!$loop->last)
                                                    ,
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if ($book->serialization)
                                    <div class="row mb-1">
                                        <div class="col-4">Serialization</div>
                                        <div class="col-8">: {{ $book->serialization->name }}</div>
                                    </div>
                                @endif

                                <div class="row mb-1">
                                    <div class="col-4">Rating</div>
                                    <div class="col-8 d-flex align-items-center gap-2">
                                        : {{ $book->rating }}
                                        <div>
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= round($book->rating / 2))
                                                    <i class="fa-solid fa-star star-filled"></i>
                                                @else
                                                    <i class="fa-solid fa-star star-empty"></i>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                </div>

                                @if ($book->release_date)
                                    <div class="row mb-1">
                                        <div class="col-4">Rilis</div>
                                        <div class="col-8">: {{ $book->release_date->format('d M Y') }}</div>
                                    </div>
                                @endif

                                <div class="row mb-1">
                                    <div class="col-4">Created Date</div>
                                    <div class="col-8">: {{ $book->created_at->format('d, M Y') }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2 mb-4">
                            @forelse($book->genres as $genre)
                                <span class="badge badge-genre">{{ $genre->name }}</span>
                            @empty
                                <span class="text-muted fs-12">No Genre</span>
                            @endforelse
                        </div>

                        <div>
                            <h6 class="fw-bold">Sinopsis :</h6>
                            <p class="text-muted text-justify fs-13" style="line-height: 1.6;">
                                {{ $book->synopsis ?? 'Tidak ada sinopsis.' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="radius-8 overflow-hidden shadow-sm">
                    <div class="char-header">
                        <h5 class="text-white fw-bold mb-0">Characters</h5>
                        <a href="{{ route('characters.create', $book->id) }}" class="btn-add-char shadow-sm">
                            <i class="fa-solid fa-plus"></i>
                        </a>
                    </div>

                    <div class="char-card-body">
                        @forelse($book->characters as $char)
                            <div class="char-item shadow-sm">
                                <div style="width: 80px; height: 80px; flex-shrink: 0;">
                                    <img src="{{ $char->image_path ? asset('storage/' . $char->image_path) : asset('assets/images/avatar/1.png') }}"
                                        class="w-100 h-100 object-fit-cover rounded"
                                        style="filter: {{ $char->status == 'Deceased' ? 'grayscale(100%)' : 'none' }}">
                                </div>

                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <span class="fw-bold text-dark fs-14">{{ $char->name }}</span>
                                            <span class="mx-1">|</span>

                                            @php
                                                $roleClass = match ($char->role) {
                                                    'Main Character (MC)' => 'char-role-mc',
                                                    'Heroine' => 'char-role-heroine',
                                                    'Villain' => 'char-role-villain',
                                                    default => 'char-role-normal',
                                                };
                                                $roleName = str_replace('Main Character (MC)', 'MC', $char->role);
                                            @endphp
                                            <span class="{{ $roleClass }} fs-13">{{ $roleName }}</span>
                                        </div>

                                        <span
                                            class="{{ $char->status == 'Alive' ? 'badge-status-alive' : 'badge-status-dead' }}">
                                            {{ $char->status == 'Alive' ? 'Hidup' : 'Mati' }}
                                        </span>
                                    </div>

                                    <p class="mb-0 mt-1 fs-12 text-truncate-2-line" style="color: var(--c-grey-desc);">
                                        {{ $char->description ?? 'Tidak ada deskripsi.' }}
                                    </p>

                                    <div class="d-flex justify-content-end align-items-center mt-1">
                                        <div class="d-flex gap-2 align-items-center">
                                            <a href="{{ route('characters.edit', $char->id) }}" class="text-muted fs-12"><i
                                                    class="fa-solid fa-pen"></i></a>

                                            @if ($char->is_favorite)
                                                <i class="fa-solid fa-heart icon-fav-char"></i>
                                            @else
                                                <i class="fa-regular fa-heart" style="color: #e0e0e0;"></i>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-white py-4">
                                Belum ada karakter yang ditambahkan.
                            </div>
                        @endforelse
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

            if (covers.length <= 1) return;

            const imgElement = document.getElementById('dynamic-cover');
            const storageUrl = "{{ asset('storage') }}/";
            let currentIndex = 0;

            setInterval(() => {
                imgElement.style.opacity = 0.6;
                setTimeout(() => {
                    currentIndex = (currentIndex + 1) % covers.length;
                    imgElement.src = storageUrl + covers[currentIndex];
                    imgElement.style.opacity = 1;
                }, 250);
            }, 4000);
        });
    </script>
@endpush
