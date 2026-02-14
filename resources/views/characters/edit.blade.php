@extends('layouts.app')

@section('title', 'Edit Character - ' . $character->name)

@section('content')
<div class="page-header">
    <div class="page-header-left d-flex align-items-center">
        <div class="page-header-title">
            <h5 class="m-b-10">Edit Character</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('books.show', $book->slug) }}">{{ $book->title_primary }}</a></li>
            <li class="breadcrumb-item">Edit {{ $character->name }}</li>
        </ul>
    </div>
</div>

<div class="main-content">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card stretch stretch-full">
                <div class="card-body">
                    <form action="{{ route('characters.update', $character->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label">Nama Karakter</label>
                                    <input type="text" name="name" class="form-control" value="{{ $character->name }}" required>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Role</label>
                                        <select name="role" class="form-select">
                                            <option value="Main Character (MC)" {{ $character->role == 'Main Character (MC)' ? 'selected' : '' }}>Main Character (MC)</option>
                                            <option value="Heroine" {{ $character->role == 'Heroine' ? 'selected' : '' }}>Heroine</option>
                                            <option value="Villain" {{ $character->role == 'Villain' ? 'selected' : '' }}>Villain / Antagonist</option>
                                            <option value="Support" {{ $character->role == 'Support' ? 'selected' : '' }}>Support / Sidekick</option>
                                            <option value="Cameo" {{ $character->role == 'Cameo' ? 'selected' : '' }}>Cameo</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Status (Nasib)</label>
                                        <select name="status" class="form-select">
                                            <option value="Alive" {{ $character->status == 'Alive' ? 'selected' : '' }}>Alive (Hidup)</option>
                                            <option value="Deceased" {{ $character->status == 'Deceased' ? 'selected' : '' }}>Deceased (Mati)</option>
                                            <option value="Sealed" {{ $character->status == 'Sealed' ? 'selected' : '' }}>Sealed (Disegel)</option>
                                            <option value="Unknown" {{ $character->status == 'Unknown' ? 'selected' : '' }}>Unknown</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Deskripsi</label>
                                    <textarea name="description" class="form-control" rows="3">{{ $character->description }}</textarea>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3 text-center">
                                    <label class="form-label d-block">Foto Saat Ini</label>
                                    @if($character->image_path)
                                        <img src="{{ asset('storage/' . $character->image_path) }}" class="img-thumbnail rounded-circle mb-2" style="width: 100px; height: 100px; object-fit: cover;">
                                    @else
                                        <div class="avatar-text avatar-xl rounded-circle bg-gray-200 mx-auto mb-2">?</div>
                                    @endif
                                    
                                    <input type="file" name="image" class="form-control mt-2" accept="image/*">
                                    <small class="text-muted">Upload baru jika ingin mengganti.</small>
                                </div>

                                <div class="p-3 border rounded bg-light">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_favorite" id="favCheck" {{ $character->is_favorite ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="favCheck">Favorite Character? ❤️</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('books.show', $book->slug) }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Character</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection