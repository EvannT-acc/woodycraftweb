<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PuzzleController;
use App\Models\Categorie;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/about', [PageController::class, 'about'])->name('about');

Route::get('/dashboard', function () {
    $categories = Categorie::with('puzzles')->get();
    return view('dashboard', compact('categories'));
})->name('dashboard');

Route::get('/categories/{categorie}', function (Categorie $categorie) {
    $categorie->load('puzzles');
    return view('categories.show', compact('categorie'));
})->name('categories.show');

Route::resource('puzzles', PuzzleController::class)->only(['index', 'show']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('puzzles', PuzzleController::class)->except(['index', 'show']);
});

require __DIR__.'/auth.php';
