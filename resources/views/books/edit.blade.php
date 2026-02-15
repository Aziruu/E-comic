@extends('layouts.app')

@section('title', 'Edit Manga - ' . $book->title_primary)

@section('content')
<div class="main-content">
    <form action="{{ route('books.update', $book->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm radius-8 mb-4">
                    <div class="card-body p-4">
                        <div class="form-section-title">Informasi Utama</div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Judul Utama <span class="text-danger">*</span></label>
                            <input type="text" name="title_primary" class="form-control form-control-lg" value="{{ old('title_primary', $book->title_primary) }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted fs-13">Judul Alternatif</label>
                                <input type="text" name="title_secondary" class="form-control" value="{{ old('title_secondary', $book->title_secondary) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted fs-13">Series / Universe</label>
                                <input type="text" name="series" class="form-control" value="{{ old('series', $book->series) }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Sinopsis</label>
                            <textarea name="synopsis" class="form-control" rows="5">{{ old('synopsis', $book->synopsis) }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Link Baca (URL)</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-link"></i></span>
                                    <input type="url" name="link_url" class="form-control" value="{{ old('link_url', $book->link_url) }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tanggal Rilis</label>
                                <input type="date" name="release_date" class="form-control" value="{{ $book->release_date ? $book->release_date->format('Y-m-d') : '' }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm radius-8 mb-4">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-2">
                                    <div class="form-section-title mb-0 border-0 p-0">Authors</div>
                                    <button type="button" class="btn btn-sm btn-light text-primary fw-bold"
                                        onclick="openCustomModal('customModalAuthor')">
                                        <i class="fa-solid fa-plus"></i> New
                                    </button>
                                </div>
                                <div class="overflow-auto pe-2" style="max-height: 200px;" id="authorListContainer">
                                    @foreach($authors as $author)
                                        <div class="check-card">
                                            <div class="form-check w-100 mb-0">
                                                <input class="form-check-input" type="checkbox" name="author_ids[]" value="{{ $author->id }}" id="auth{{ $author->id }}"
                                                {{ $book->authors->contains($author->id) ? 'checked' : '' }}>
                                                <label class="form-check-label w-100 cursor-pointer" for="auth{{ $author->id }}">
                                                    {{ $author->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-section-title">Genres</div>
                                <div class="overflow-auto pe-2" style="max-height: 200px;">
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($genres as $genre)
                                            <div class="form-check me-2 mb-2">
                                                <input class="form-check-input" type="checkbox" name="genre_ids[]" value="{{ $genre->id }}" id="gen{{ $genre->id }}"
                                                {{ $book->genres->contains($genre->id) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="gen{{ $genre->id }}">{{ $genre->name }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm radius-8 mb-4">
                    <div class="card-body p-4">
                        <div class="form-section-title">Status & Progress</div>

                        <div class="mb-3">
                            <label class="form-label">Tipe</label>
                            <select name="type" class="form-select">
                                @foreach(['Manga', 'Manhwa', 'Manhua', 'Webtoon'] as $type)
                                    <option value="{{ $type }}" {{ $book->type == $type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label fs-12 text-muted">Status Rilis</label>
                                <select name="status_release" class="form-select text-success fw-bold">
                                    @foreach(['Ongoing', 'Completed', 'Hiatus', 'Cancelled'] as $status)
                                        <option value="{{ $status }}" {{ $book->status_release == $status ? 'selected' : '' }}>{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label fs-12 text-muted">Status Baca</label>
                                <select name="status_reading" class="form-select text-primary fw-bold">
                                    @foreach(['Reading', 'Plan to Read', 'Completed', 'Dropped', 'On Hold'] as $status)
                                        <option value="{{ $status }}" {{ $book->status_reading == $status ? 'selected' : '' }}>{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-flex justify-content-between">
                                Serialization
                                <a href="#" class="text-decoration-none fs-12 fw-bold" 
                                    onclick="openCustomModal('customModalSerial'); return false;">+ Add New</a>
                            </label>
                            <select name="serialization_id" id="serializationSelect" class="form-select">
                                <option value="">-- Pilih Publisher --</option>
                                @foreach($serializations as $serial)
                                    <option value="{{ $serial->id }}" {{ $book->serialization_id == $serial->id ? 'selected' : '' }}>
                                        {{ $serial->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <hr class="border-dashed">

                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Total Ch.</label>
                                <input type="number" name="total_chapters" class="form-control" value="{{ old('total_chapters', $book->total_chapters) }}">
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Last Read</label>
                                <input type="text" name="last_read_chapter" class="form-control" value="{{ old('last_read_chapter', $book->last_read_chapter) }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Rating (0-10)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-warning text-white"><i class="fa-solid fa-star"></i></span>
                                <input type="number" name="rating" class="form-control" step="0.1" min="0" max="10" value="{{ old('rating', $book->rating) }}">
                            </div>
                        </div>

                        <div class="p-3 bg-light rounded radius-8 mt-3 d-flex align-items-center">
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" name="is_favorite" id="favSwitch" style="cursor: pointer; width: 3em; height: 1.5em;"
                                {{ $book->is_favorite ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold ms-3 mt-1" for="favSwitch" style="cursor: pointer;">
                                    Jadikan Favorit? ⭐
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm radius-8">
                    <div class="card-body p-4">
                        <div class="form-section-title">Covers</div>
                        
                        <div class="d-flex gap-2 overflow-auto pb-3 mb-3 border-bottom">
                            @foreach($book->covers as $cover)
                                <div class="position-relative" style="width: 60px; height: 90px; flex-shrink: 0;">
                                    <img src="{{ asset('storage/' . $cover->image_path) }}" class="w-100 h-100 object-fit-cover rounded border">
                                    @if($cover->is_primary)
                                        <span class="position-absolute top-0 start-0 badge bg-primary" style="font-size: 8px;">MAIN</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div class="cover-upload-area position-relative" onclick="document.getElementById('coverInput').click()">
                            <i class="fa-solid fa-cloud-arrow-up fs-1 text-muted mb-2"></i>
                            <p class="mb-0 text-muted fs-13">Tambah Cover Baru</p>
                            <input type="file" name="covers[]" id="coverInput" class="d-none" multiple accept="image/*" onchange="previewImages(this)">
                        </div>
                        <div id="imagePreviewContainer" class="d-flex gap-2 mt-3 overflow-auto pb-2"></div>
                    </div>
                </div>

                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-primary btn-lg fw-bold radius-8">Update Manga</button>
                    <a href="{{ route('books.show', $book->slug) }}" class="btn btn-light radius-8">Batal</a>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="custom-modal-overlay" id="customModalAuthor">
    <div class="custom-modal-box">
        <div class="custom-modal-header">
            <h6>Add Author</h6>
            <button class="custom-modal-close" onclick="closeCustomModal('customModalAuthor')">&times;</button>
        </div>
        <div class="custom-modal-body">
            <input type="text" id="newAuthorName" class="form-control" placeholder="Nama Author...">
            <button type="button" id="btnSaveAuthor" class="btn btn-primary w-100 mt-3 radius-8">Simpan</button>
        </div>
    </div>
</div>

<div class="custom-modal-overlay" id="customModalSerial">
    <div class="custom-modal-box">
        <div class="custom-modal-header">
            <h6>Add Publisher</h6>
            <button class="custom-modal-close" onclick="closeCustomModal('customModalSerial')">&times;</button>
        </div>
        <div class="custom-modal-body">
            <input type="text" id="newSerialName" class="form-control" placeholder="Nama Publisher...">
            <button type="button" id="btnSaveSerial" class="btn btn-primary w-100 mt-3 radius-8">Simpan</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openCustomModal(modalId) {
        document.getElementById(modalId).classList.add('active');
    }

    function closeCustomModal(modalId) {
        document.getElementById(modalId).classList.remove('active');
    }

    document.querySelectorAll('.custom-modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) {
                closeCustomModal(this.id);
            }
        });
    });

    function previewImages(input) {
        const container = document.getElementById('imagePreviewContainer');
        container.innerHTML = '';
        if (input.files) {
            Array.from(input.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'rounded border';
                    img.style.width = '60px';
                    img.style.height = '90px';
                    img.style.objectFit = 'cover';
                    container.appendChild(img);
                }
                reader.readAsDataURL(file);
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('btnSaveAuthor').addEventListener('click', function() {
            const name = document.getElementById('newAuthorName').value.trim();
            
            if (!name) {
                alert('Nama tidak boleh kosong!');
                return;
            }

            const fd = new FormData();
            fd.append('name', name);
            fd.append('_token', '{{ csrf_token() }}');

            fetch('{{ route("quick.author") }}', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => {
                const html = `<div class="check-card" style="background: #f0f8ff; border-color: #25A4FF;">
                    <div class="form-check w-100 mb-0">
                        <input class="form-check-input" type="checkbox" name="author_ids[]" value="${data.id}" id="auth${data.id}" checked>
                        <label class="form-check-label w-100 fw-bold text-primary cursor-pointer" for="auth${data.id}">${data.name}</label>
                    </div></div>`;
                
                document.getElementById('authorListContainer').insertAdjacentHTML('afterbegin', html);
                document.getElementById('newAuthorName').value = '';
                closeCustomModal('customModalAuthor');
            })
            .catch(() => alert('Gagal menyimpan'));
        });

        document.getElementById('btnSaveSerial').addEventListener('click', function() {
            const name = document.getElementById('newSerialName').value.trim();
            
            if (!name) {
                alert('Nama tidak boleh kosong!');
                return;
            }

            const fd = new FormData();
            fd.append('name', name);
            fd.append('_token', '{{ csrf_token() }}');

            fetch('{{ route("quick.serialization") }}', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => {
                const opt = new Option(data.name, data.id, true, true);
                document.getElementById('serializationSelect').add(opt);
                document.getElementById('newSerialName').value = '';
                closeCustomModal('customModalSerial');
            })
            .catch(() => alert('Gagal menyimpan'));
        });
    });
</script>
@endpush