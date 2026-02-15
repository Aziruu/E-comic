@extends('layouts.app')

@section('title', 'Tambah Manga Baru')

@section('content')
    <div class="main-content">
        <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm radius-8 mb-4">
                        <div class="card-body p-4">
                            <div class="form-section-title">Informasi Utama</div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Judul Utama <span class="text-danger">*</span></label>
                                <input type="text" name="title_primary" class="form-control form-control-lg"
                                    placeholder="Contoh: Solo Leveling" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted fs-13">Judul Alternatif (Jepang/Korea)</label>
                                    <input type="text" name="title_secondary" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted fs-13">Series / Universe</label>
                                    <input type="text" name="series" class="form-control"
                                        placeholder="Contoh: Fate Series">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Sinopsis</label>
                                <textarea name="synopsis" class="form-control" rows="5" placeholder="Ceritakan sedikit tentang manga ini..."></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Link Baca (URL)</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-link"></i></span>
                                        <input type="url" name="link_url" class="form-control"
                                            placeholder="https://...">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Tanggal Rilis</label>
                                    <input type="date" name="release_date" class="form-control">
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
                                        @foreach ($authors as $author)
                                            <div class="check-card">
                                                <div class="form-check w-100 mb-0">
                                                    <input class="form-check-input" type="checkbox" name="author_ids[]"
                                                        value="{{ $author->id }}" id="auth{{ $author->id }}">
                                                    <label class="form-check-label w-100 cursor-pointer"
                                                        for="auth{{ $author->id }}">
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
                                            @foreach ($genres as $genre)
                                                <div class="form-check me-2 mb-2">
                                                    <input class="form-check-input" type="checkbox" name="genre_ids[]"
                                                        value="{{ $genre->id }}" id="gen{{ $genre->id }}">
                                                    <label class="form-check-label"
                                                        for="gen{{ $genre->id }}">{{ $genre->name }}</label>
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
                                    <option value="Manga">Manga (JP)</option>
                                    <option value="Manhwa">Manhwa (KR)</option>
                                    <option value="Manhua">Manhua (CN)</option>
                                    <option value="Webtoon">Webtoon</option>
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="form-label fs-12 text-muted">Status Rilis</label>
                                    <select name="status_release" class="form-select text-success fw-bold">
                                        <option value="Ongoing">Ongoing</option>
                                        <option value="Completed">Completed</option>
                                        <option value="Hiatus">Hiatus</option>
                                        <option value="Cancelled">Cancelled</option>
                                    </select>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label fs-12 text-muted">Status Baca</label>
                                    <select name="status_reading" class="form-select text-primary fw-bold">
                                        <option value="Reading">Reading</option>
                                        <option value="Plan to Read">Plan</option>
                                        <option value="Completed">Done</option>
                                        <option value="Dropped">Drop</option>
                                        <option value="On Hold">Hold</option>
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
                                    @foreach ($serializations as $serial)
                                        <option value="{{ $serial->id }}">{{ $serial->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <hr class="border-dashed">

                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="form-label">Total Ch.</label>
                                    <input type="number" name="total_chapters" class="form-control" value="0">
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Last Read</label>
                                    <input type="text" name="last_read_chapter" class="form-control"
                                        placeholder="Ch. 1">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Rating (0-10)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-warning text-white"><i
                                            class="fa-solid fa-star"></i></span>
                                    <input type="number" name="rating" class="form-control" step="0.1"
                                        min="0" max="10" placeholder="0.0">
                                </div>
                            </div>

                            <div class="p-3 bg-light rounded radius-8 mt-3 d-flex align-items-center">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" name="is_favorite" id="favSwitch"
                                        style="cursor: pointer; width: 3em; height: 1.5em;">
                                    <label class="form-check-label fw-bold ms-3 mt-1" for="favSwitch"
                                        style="cursor: pointer;">
                                        Jadikan Favorit? ⭐
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm radius-8">
                        <div class="card-body p-4">
                            <div class="form-section-title">Upload Covers</div>
                            <div class="cover-upload-area position-relative"
                                onclick="document.getElementById('coverInput').click()">
                                <i class="fa-solid fa-cloud-arrow-up fs-1 text-muted mb-2"></i>
                                <p class="mb-0 text-muted fs-13">Klik untuk upload gambar</p>
                                <small class="text-muted" style="font-size: 10px;">Bisa pilih banyak sekaligus</small>
                                <input type="file" name="covers[]" id="coverInput" class="d-none" multiple
                                    accept="image/*" onchange="previewImages(this)">
                            </div>
                            <div id="imagePreviewContainer" class="d-flex gap-2 mt-3 overflow-auto pb-2"></div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold radius-8">Simpan Manga</button>
                        <a href="{{ route('books.index') }}" class="btn btn-light radius-8">Batal</a>
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