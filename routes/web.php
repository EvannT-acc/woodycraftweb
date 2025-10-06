<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PuzzleController;
use App\Http\Controllers\PanierController;
use App\Models\Categorie;
use App\Http\Controllers\CheckoutController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Routes principales de ton site WoodyCraftWeb
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
    Route::patch('/panier/update/{ligne}', [PanierController::class, 'update'])->name('paniers.update');

    // Routes Checkout (paiement)
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/valider', [CheckoutController::class, 'valider'])->name('checkout.valider');
});

// ---------------------------
// Routes PayPal
// ---------------------------

// ✅ Succès du paiement PayPal
Route::get('/paypal/success', function () {
    return redirect()->route('dashboard')->with('success', 'Paiement PayPal réussi ! Merci pour votre commande.');
})->name('paypal.success');

// ❌ Annulation du paiement PayPal
Route::get('/paypal/cancel', function () {
    return redirect()->route('paniers.index')->with('error', 'Paiement PayPal annulé.');
})->name('paypal.cancel');

// (Optionnel) Notification IPN PayPal
Route::post('/paypal/ipn', function () {
    return response('OK', 200);
})->name('paypal.ipn');

// ---------------------------
// CRUD puzzles
// ---------------------------
Route::resource('puzzles', PuzzleController::class)->middleware('auth');

// Authentification Laravel Breeze
require __DIR__.'/auth.php';
