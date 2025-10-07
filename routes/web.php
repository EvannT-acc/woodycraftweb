<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\PuzzleController;
use App\Http\Controllers\PanierController;
use App\Http\Controllers\CheckoutController;
use App\Models\Categorie;
use App\Models\Panier;
use App\Models\LignePanier;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Routes principales du site WoodyCraftWeb
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

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
// Panier (accessible à tous)
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
// Routes PayPal
// ---------------------------
Route::get('/paypal/success', function () {
    $user = Auth::user();

    if ($user) {
        $panier = Panier::where('user_id', $user->id)
            ->where('status', 1)
            ->latest('updated_at')
            ->first();

        if ($panier) {
            DB::transaction(function () use ($panier) {
                LignePanier::where('panier_id', $panier->id)->delete();
                $panier->delete();
            });
        }
    }

    return redirect()->route('dashboard')
        ->with('success', 'Paiement PayPal réussi ! Merci pour votre commande.');
})->middleware('auth')->name('paypal.success');

Route::get('/paypal/cancel', function () {
    return redirect()->route('paniers.index')
        ->with('error', 'Paiement PayPal annulé.');
})->name('paypal.cancel');

Route::post('/paypal/ipn', function () {
    return response('OK', 200);
})->name('paypal.ipn');

// ---------------------------
// CRUD puzzles (protégé)
// ---------------------------
Route::resource('puzzles', PuzzleController::class)->middleware('auth');

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
