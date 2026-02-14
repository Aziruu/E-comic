@extends('layouts.app')

@section('title', 'Manage Genres - MangaLib')

@section('content')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Manage Genres</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('books.index') }}">Library</a></li>
                <li class="breadcrumb-item">Genres</li>
            </ul>
        </div>
    </div>

    <div class="main-content">
        <div class="row">
            <div class="col-md-4">
                <div class="card stretch stretch-full">
                    <div class="card-header">
                        <h5 class="card-title">Add New Genre</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('genres.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Genre Name</label>
                                <input type="text" name="name" class="form-control" placeholder="e.g. Dark Fantasy"
                                    required>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="feather-plus me-2"></i> Add Genre
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <i class="feather-info fs-1 mb-2"></i>
                        <p class="mb-0">Genre yang dihapus akan otomatis hilang dari buku yang menggunakannya (tidak
                            merusak data buku).</p>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card stretch stretch-full">
                    <div class="card-header">
                        <h5 class="card-title">Genre List</h5>
                    </div>
                    <div class="card-body custom-card-action p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Used In</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($genres as $genre)
                                        <tr>
                                            <td class="fw-bold text-dark">{{ $genre->name }}</td>
                                            <td>
                                                <span class="badge bg-soft-primary text-primary">
                                                    {{ $genre->books_count }} Books
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <form action="{{ route('genres.destroy', $genre->id) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Hapus genre {{ $genre->name }}?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-light-danger"
                                                        title="Delete">
                                                        <i class="feather-trash-2"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-4 text-muted">Belum ada genre.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
