<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\CharacterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
});

Route::resource('books', BookController::class);

Route::get('/books/{book}/characters/create', [CharacterController::class, 'create'])->name('characters.create');
Route::post('/books/{book}/characters', [CharacterController::class, 'store'])->name('characters.store');
Route::delete('/characters/{character}', [CharacterController::class, 'destroy'])->name('characters.destroy');
Route::get('/characters/{character}/edit', [CharacterController::class, 'edit'])->name('characters.edit');
Route::put('/characters/{character}', [CharacterController::class, 'update'])->name('characters.update');
