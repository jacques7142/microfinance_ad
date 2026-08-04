<?php

use App\Http\Controllers\Admin\AgenceController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AgenceDetailController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CompteEpargneController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\SocietaireController;
use App\Http\Controllers\SocietaireCreditController;
use App\Http\Controllers\SocietaireOperationController;
use App\Http\Controllers\SocietairePortalController;
use App\Http\Controllers\TontineController;
use Illuminate\Support\Facades\Route;

// --- Authentification ---
Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
    Route::get('/inscription', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/inscription', [RegisterController::class, 'register'])->name('register.submit');
});
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth:societaire')->group(function () {
    Route::get('/espace-societaire', [SocietairePortalController::class, 'dashboard'])->name('societaire.dashboard');
    Route::get('/demande-credit', [SocietaireCreditController::class, 'create'])->name('societaire.credit.create');
    Route::post('/demande-credit', [SocietaireCreditController::class, 'store'])->name('societaire.credit.store');
    Route::get('/depot', [SocietaireOperationController::class, 'depotForm'])->name('societaire.depot');
    Route::post('/depot', [SocietaireOperationController::class, 'depot'])->name('societaire.depot.store');
    Route::get('/retrait', [SocietaireOperationController::class, 'retraitForm'])->name('societaire.retrait');
    Route::post('/retrait', [SocietaireOperationController::class, 'retrait'])->name('societaire.retrait.store');
    Route::get('/remboursement', [SocietaireOperationController::class, 'remboursementForm'])->name('societaire.remboursement');
    Route::post('/remboursement', [SocietaireOperationController::class, 'rembourser'])->name('societaire.remboursement.store');

    Route::get('/messagerie', [MessageController::class, 'societaireIndex'])->name('societaire.messages');
    Route::post('/messagerie', [MessageController::class, 'societaireSend'])->name('societaire.messages.send');
});

// --- Authentifié : tous rôles internes confondus ---
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil utilisateur
    Route::get('/profil', [ProfilController::class, 'show'])->name('profil.show');
    Route::get('/profil/modifier', [ProfilController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
    Route::post('/profil/photo', [ProfilController::class, 'uploadPhoto'])->name('profil.upload-photo');

    // Messagerie sociétaires : accessible à tout le personnel de la coopérative.
    Route::get('/messages', [MessageController::class, 'staffIndex'])->name('messages.index');
    Route::get('/messages/{societaire}', [MessageController::class, 'staffShow'])->name('messages.show');
    Route::post('/messages/{societaire}', [MessageController::class, 'staffReply'])->name('messages.reply');

    // Détails Agence
    Route::get('/agences/{agence}', [AgenceDetailController::class, 'show'])->name('agences.show');
    Route::get('/api/agence/{agence}', [AgenceDetailController::class, 'modal'])->name('api.agence.modal');

    // Sociétaires : consultable par tous les rôles internes, création réservée
    // aux agents de crédit et gérants (cf. cahier des charges §3.2).
    Route::get('/societaires', [SocietaireController::class, 'index'])->name('societaires.index');
    Route::middleware('role:agent_credit,gerant,administrateur')->group(function () {
        Route::get('/societaires/creer', [SocietaireController::class, 'create'])->name('societaires.create');
        Route::post('/societaires', [SocietaireController::class, 'store'])->name('societaires.store');
    });
    Route::get('/societaires/{societaire}', [SocietaireController::class, 'show'])->name('societaires.show');

    // Crédits : lecture pour tous les rôles concernés, écritures encadrées par rôle.
    Route::get('/credits', [CreditController::class, 'index'])->name('credits.index');
    Route::middleware('role:agent_credit,gerant,administrateur')->group(function () {
        Route::get('/credits/creer', [CreditController::class, 'create'])->name('credits.create');
        Route::post('/credits', [CreditController::class, 'store'])->name('credits.store');
    });
    Route::get('/credits/{credit}', [CreditController::class, 'show'])->name('credits.show');
    Route::post('/credits/{credit}/instruire', [CreditController::class, 'instruire'])
        ->middleware('role:agent_credit')->name('credits.instruire');
    Route::post('/credits/{credit}/valider', [CreditController::class, 'valider'])
        ->middleware('role:gerant')->name('credits.valider');
    Route::post('/credits/{credit}/rejeter', [CreditController::class, 'rejeter'])
        ->middleware('role:gerant')->name('credits.rejeter');

    // Guichet (épargnes) : réservé au caissier.
    Route::middleware('role:caissier')->group(function () {
        Route::get('/guichet', [CompteEpargneController::class, 'index'])->name('epargne.index');
        Route::get('/guichet/societaires/{societaire}/comptes', [CompteEpargneController::class, 'comptes'])->name('epargne.comptes');
        Route::post('/guichet/operation', [CompteEpargneController::class, 'operation'])->name('epargne.operation');
    });

    // Tontine LOGOKU : consultation agent de promotion + validation caissier.
    Route::middleware('role:agent_promotion')->group(function () {
        Route::get('/tontine', [TontineController::class, 'index'])->name('tontine.index');
        Route::post('/tontine/collecter', [TontineController::class, 'collecter'])->name('tontine.collecter');
    });
    Route::post('/tontine/collectes/{collecte}/valider', [TontineController::class, 'valider'])
        ->middleware('role:caissier')->name('tontine.valider');

    // Rapports : comptable, gérant, administrateur.
    Route::middleware('role:comptable,gerant,administrateur')->group(function () {
        Route::get('/rapports', [RapportController::class, 'index'])->name('rapports.index');
        Route::get('/rapports/{rapport}', [RapportController::class, 'show'])->name('rapports.show');
        Route::get('/rapports/{rapport}/modifier', [RapportController::class, 'edit'])->name('rapports.edit');
        Route::put('/rapports/{rapport}', [RapportController::class, 'update'])->name('rapports.update');
        Route::delete('/rapports/{rapport}', [RapportController::class, 'destroy'])->name('rapports.destroy');
        Route::post('/rapports/generer', [RapportController::class, 'generer'])->name('rapports.generer');
    });

    // Administration système : administrateur uniquement.
    Route::prefix('admin')->name('admin.')->middleware('role:administrateur')->group(function () {
        Route::get('/utilisateurs', [UserController::class, 'index'])->name('users.index');
        Route::get('/utilisateurs/creer', [UserController::class, 'create'])->name('users.create');
        Route::post('/utilisateurs', [UserController::class, 'store'])->name('users.store');
        Route::get('/utilisateurs/{user}/modifier', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/utilisateurs/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/utilisateurs/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::post('/utilisateurs/{user}/couleur', [UserController::class, 'updateCouleur'])->name('users.couleur');
        Route::post('/utilisateurs/{user}/toggle-actif', [UserController::class, 'toggleActif'])->name('users.toggle-actif');

        Route::get('/agences', [AgenceController::class, 'index'])->name('agences.index');
        Route::get('/agences/creer', [AgenceController::class, 'create'])->name('agences.create');
        Route::post('/agences', [AgenceController::class, 'store'])->name('agences.store');

        Route::get('/roles', [RolePermissionController::class, 'index'])->name('roles.index');
        Route::put('/roles', [RolePermissionController::class, 'update'])->name('roles.update');
    });
});
