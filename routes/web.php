<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PuzzleController;
use App\Http\Controllers\PanierController;
use App\Http\Controllers\CheckoutController;
use App\Models\Categorie;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Routes principales du site WoodyCraftWeb
|
*/

// ---------------------------
// Pages publiques
// ---------------------------
Route::get('/', function () {
    return view('welcome');
});

Route::get('/about', [PageController::class, 'about'])->name('about');

// ---------------------------
// Tableau de bord (protégé)
// ---------------------------
Route::get('/dashboard', function () {
    $categories = Categorie::with('puzzles')->get();
    return view('dashboard', compact('categories'));
})->middleware(['auth', 'verified'])->name('dashboard');

// ---------------------------
// Routes protégées (auth)
// ---------------------------
Route::middleware('auth')->group(function () {

    // Profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Catégorie et puzzles associés
    Route::get('/categories/{categorie}', function (Categorie $categorie) {
        $categorie->load('puzzles');
        return view('categories.show', compact('categorie'));
    })->name('categories.show');

    // Panier
    Route::get('/panier', [PanierController::class, 'index'])->name('paniers.index');
    Route::post('/panier/add/{puzzle}', [PanierController::class, 'add'])->name('paniers.add');
    Route::delete('/panier/remove/{ligne}', [PanierController::class, 'remove'])->name('paniers.remove');
    Route::patch('/panier/update/{ligne}', [PanierController::class, 'update'])->name('paniers.update');

    // Checkout (paiement)
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/valider', [CheckoutController::class, 'valider'])->name('checkout.valider');

    // Paiement PayPal (retours utilisateur)
    Route::get('/paypal/success', [CheckoutController::class, 'paypalSuccess'])
        ->name('paypal.success'); // conserve la session utilisateur
    Route::get('/paypal/cancel', [CheckoutController::class, 'paypalCancel'])
        ->name('paypal.cancel');
});

// Notification IPN PayPal (système) — pas d’auth ici
Route::post('/paypal/ipn', [CheckoutController::class, 'paypalIpn'])->name('paypal.ipn');

// ---------------------------
// CRUD puzzles
// ---------------------------
Route::resource('puzzles', PuzzleController::class)->middleware('auth');

// ---------------------------
// Authentification (Laravel Breeze)
// ---------------------------
require __DIR__.'/auth.php';
