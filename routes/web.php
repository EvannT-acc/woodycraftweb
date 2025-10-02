<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PuzzleController;
use App\Http\Controllers\PanierController;
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
    return view('welcome');
});

Route::get('/about', [PageController::class, 'about'])->name('about');

Route::get('/dashboard', function () {
    $categories = Categorie::with('puzzles')->get();
    return view('dashboard', compact('categories'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Catégorie avec ses puzzles
    Route::get('/categories/{categorie}', function (Categorie $categorie) {
        $categorie->load('puzzles');
        return view('categories.show', compact('categorie'));
    })->name('categories.show');

    // Routes Panier
    Route::get('/panier', [PanierController::class, 'index'])->name('paniers.index');
    Route::post('/panier/add/{puzzle}', [PanierController::class, 'add'])->name('paniers.add');
    Route::delete('/panier/remove/{ligne}', [PanierController::class, 'remove'])->name('paniers.remove');
});

// CRUD puzzles
Route::resource('puzzles', PuzzleController::class)->middleware('auth');

require __DIR__.'/auth.php';
