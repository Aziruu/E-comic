@extends('layouts.app')

@section('title', 'Add Character - ' . $book->title_primary)

@section('content')
<div class="page-header">
    <div class="page-header-left d-flex align-items-center">
        <div class="page-header-title">
            <h5 class="m-b-10">Add Character</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('books.show', $book->slug) }}">{{ $book->title_primary }}</a></li>
            <li class="breadcrumb-item">New Character</li>
        </ul>
    </div>
</div>

<div class="main-content">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card stretch stretch-full">
                <div class="card-body">
                    <h5 class="mb-4">Tokoh Baru untuk: <span class="text-primary">{{ $book->title_primary }}</span></h5>

                    <form action="{{ route('characters.store', $book->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label">Nama Karakter</label>
                                    <input type="text" name="name" class="form-control" placeholder="e.g. Sung Jin-Woo" required>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Role</label>
                                        <select name="role" class="form-select">
                                            <option value="Main Character (MC)">Main Character (MC)</option>
                                            <option value="Heroine">Heroine</option>
                                            <option value="Villain">Villain / Antagonist</option>
                                            <option value="Support">Support / Sidekick</option>
                                            <option value="Cameo">Cameo</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Status (Nasib)</label>
                                        <select name="status" class="form-select">
                                            <option value="Alive" class="text-success">Alive (Hidup)</option>
                                            <option value="Deceased" class="text-danger">Deceased (Mati)</option>
                                            <option value="Sealed">Sealed (Disegel)</option>
                                            <option value="Unknown">Unknown</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Deskripsi Singkat / Kekuatan</label>
                                    <textarea name="description" class="form-control" rows="3" placeholder="Contoh: Necromancer, Shadow Monarch..."></textarea>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Foto (Portrait)</label>
                                    <input type="file" name="image" class="form-control" accept="image/*">
                                    <div class="form-text">Disarankan rasio 1:1 (Kotak)</div>
                                </div>

                                <div class="p-3 border rounded bg-light">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_favorite" id="favCheck">
                                        <label class="form-check-label fw-bold" for="favCheck">Favorite Character? ❤️</label>
                                    </div>
                                    <small class="text-muted d-block mt-1">Centang jika ini waifu/husbando/favorit Azil.</small>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('books.show', $book->slug) }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save Character</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection