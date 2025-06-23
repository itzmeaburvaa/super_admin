<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
use App\Http\Controllers\ClubController;
use App\Http\Controllers\EventController;
Route::resource('clubs', ClubController::class);
Route::get('/clubs/{id}/profile', [ClubController::class, 'profile'])->name('clubs.profile');


Route::post('/events/store', [EventController::class, 'store'])->name('events.store');
Route::get('/events/{id}/edit', [EventController::class, 'edit'])->name('events.edit');
Route::put('/events/{id}', [EventController::class, 'update'])->name('events.update');
Route::delete('/events/{id}', [EventController::class, 'destroy'])->name('events.destroy');