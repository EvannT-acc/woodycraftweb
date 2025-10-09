<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PuzzleController;
use App\Http\Controllers\PanierController;
use App\Http\Controllers\CheckoutController;
use App\Models\Categorie;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Routes principales du site WoodyCraftWeb
|--------------------------------------------------------------------------
*/

// ---------------------------
// Page d'accueil
// ---------------------------
Route::get('/', function () {
    return view('welcome');
})->name('home');

// ---------------------------
// Tableau de bord (accessible à tous)
// ---------------------------
Route::get('/dashboard', function () {
    $categories = Categorie::with('puzzles')->get();
    return view('dashboard', compact('categories'));
})->name('dashboard');

// ---------------------------
// Catégories accessibles à tous
// ---------------------------
Route::get('/categories/{categorie}', function (Categorie $categorie) {
    $categorie->load('puzzles');
    return view('categories.show', compact('categorie'));
})->name('categories.show');

// ---------------------------
// Puzzles (partiellement protégés)
// ---------------------------

// Liste publique (accessible à tous)
Route::get('/puzzles', [PuzzleController::class, 'index'])->name('puzzles.index');

// Détails publics (accessible à tous, même sans connexion)
Route::get('/puzzles/{puzzle}', [PuzzleController::class, 'show'])->name('puzzles.show');

// Routes protégées — création, modification et suppression réservées aux utilisateurs connectés
Route::middleware('auth')->group(function () {
    Route::get('/puzzles/create', [PuzzleController::class, 'create'])->name('puzzles.create');
    Route::post('/puzzles', [PuzzleController::class, 'store'])->name('puzzles.store');
    Route::get('/puzzles/{puzzle}/edit', [PuzzleController::class, 'edit'])->name('puzzles.edit');
    Route::put('/puzzles/{puzzle}', [PuzzleController::class, 'update'])->name('puzzles.update');
    Route::delete('/puzzles/{puzzle}', [PuzzleController::class, 'destroy'])->name('puzzles.destroy');
});

// ---------------------------
// Panier
// ---------------------------
Route::get('/panier', [PanierController::class, 'index'])->name('paniers.index');
Route::post('/panier/add/{puzzle}', [PanierController::class, 'add'])->name('paniers.add');
Route::delete('/panier/remove/{ligne}', [PanierController::class, 'remove'])->name('paniers.remove');
Route::patch('/panier/update/{ligne}', [PanierController::class, 'update'])->name('paniers.update');

// ---------------------------
// Checkout (connexion obligatoire)
// ---------------------------
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/valider', [CheckoutController::class, 'valider'])->name('checkout.valider');
});

// ---------------------------
// Profil utilisateur (protégé)
// ---------------------------
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ---------------------------
// Authentification Breeze
// ---------------------------
require __DIR__.'/auth.php';
