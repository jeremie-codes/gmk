<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\CongeController;
use App\Http\Controllers\CotationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Routes d'authentification
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Routes protégées
Route::middleware('auth')->group(function () {
    // Dashboard principal
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profil utilisateur
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::delete('/delete-photo', [ProfileController::class, 'deletePhoto'])->name('delete-photo');
    });
    
    // Module Gestion des agents
    Route::prefix('agents')->name('agents.')->group(function () {
        Route::get('/', [AgentController::class, 'index'])->name('index');
        Route::get('/create', [AgentController::class, 'create'])->name('create');
        Route::post('/', [AgentController::class, 'store'])->name('store');
        Route::get('/{agent}', [AgentController::class, 'show'])->name('show');
        Route::get('/{agent}/edit', [AgentController::class, 'edit'])->name('edit');
        Route::put('/{agent}', [AgentController::class, 'update'])->name('update');
        Route::delete('/{agent}', [AgentController::class, 'destroy'])->name('destroy');
        
        // Sous-modules agents
        Route::get('/identification/list', [AgentController::class, 'identification'])->name('identification');
        Route::get('/retraites/list', [AgentController::class, 'retraites'])->name('retraites');
        Route::get('/malades/list', [AgentController::class, 'malades'])->name('malades');
        Route::get('/demissions/list', [AgentController::class, 'demissions'])->name('demissions');
        Route::get('/revocations/list', [AgentController::class, 'revocations'])->name('revocations');
        Route::get('/disponibilites/list', [AgentController::class, 'disponibilites'])->name('disponibilites');
        Route::get('/detachements/list', [AgentController::class, 'detachements'])->name('detachements');
        Route::get('/mutations/list', [AgentController::class, 'mutations'])->name('mutations');
        Route::get('/reintegrations/list', [AgentController::class, 'reintegrations'])->name('reintegrations');
        Route::get('/missions/list', [AgentController::class, 'missions'])->name('missions');
        Route::get('/deces/list', [AgentController::class, 'deces'])->name('deces');
    });
    
    // Module Gestion des présences
    Route::prefix('presences')->name('presences.')->group(function () {
        Route::get('/', [PresenceController::class, 'index'])->name('index');
        Route::get('/daily', [PresenceController::class, 'daily'])->name('daily');
        Route::get('/create', [PresenceController::class, 'create'])->name('create');
        Route::post('/', [PresenceController::class, 'store'])->name('store');
        Route::get('/{presence}/edit', [PresenceController::class, 'edit'])->name('edit');
        Route::put('/{presence}', [PresenceController::class, 'update'])->name('update');
        Route::delete('/{presence}', [PresenceController::class, 'destroy'])->name('destroy');
        
        // Filtres et recherches
        Route::get('/filter', [PresenceController::class, 'filter'])->name('filter');
        Route::get('/export', [PresenceController::class, 'export'])->name('export');
    });
    
    // Module Gestion des congés
    Route::prefix('conges')->name('conges.')->group(function () {
        Route::get('/', [CongeController::class, 'index'])->name('index');
        Route::get('/dashboard', [CongeController::class, 'dashboard'])->name('dashboard');
        Route::get('/create', [CongeController::class, 'create'])->name('create');
        Route::post('/', [CongeController::class, 'store'])->name('store');
        Route::get('/{conge}', [CongeController::class, 'show'])->name('show');
        Route::get('/{conge}/edit', [CongeController::class, 'edit'])->name('edit');
        Route::put('/{conge}', [CongeController::class, 'update'])->name('update');
        Route::delete('/{conge}', [CongeController::class, 'destroy'])->name('destroy');
        
        // Interface agent
        Route::get('/mes-conges/list', [CongeController::class, 'mesConges'])->name('mes-conges');
        
        // Interface d'approbation directeur
        Route::get('/approbation-directeur/list', [CongeController::class, 'approbationDirecteur'])->name('approbation-directeur');
        Route::post('/{conge}/approuver-directeur', [CongeController::class, 'approuverDirecteur'])->name('approuver-directeur');
        
        // Interface de validation DRH
        Route::get('/validation-drh/list', [CongeController::class, 'validationDrh'])->name('validation-drh');
        Route::post('/{conge}/valider-drh', [CongeController::class, 'validerDrh'])->name('valider-drh');
        
        // API pour calcul de solde
        Route::get('/agent/{agent}/solde', [CongeController::class, 'calculerSolde'])->name('calculer-solde');
    });
    
    // Module Cotation des agents
    Route::prefix('cotations')->name('cotations.')->group(function () {
        Route::get('/', [CotationController::class, 'index'])->name('index');
        Route::get('/dashboard', [CotationController::class, 'dashboard'])->name('dashboard');
        Route::get('/create', [CotationController::class, 'create'])->name('create');
        Route::post('/', [CotationController::class, 'store'])->name('store');
        Route::get('/{cotation}', [CotationController::class, 'show'])->name('show');
        Route::get('/{cotation}/edit', [CotationController::class, 'edit'])->name('edit');
        Route::put('/{cotation}', [CotationController::class, 'update'])->name('update');
        Route::delete('/{cotation}', [CotationController::class, 'destroy'])->name('destroy');
        
        // API pour calcul en temps réel
        Route::post('/calculer', [CotationController::class, 'calculer'])->name('calculer');
        
        // Génération automatique
        Route::post('/generer-automatique', [CotationController::class, 'genererAutomatique'])->name('generer-automatique');
    });
    
    // Module Gestion des rôles et permissions
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('/{role}', [RoleController::class, 'show'])->name('show');
        Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('edit');
        Route::put('/{role}', [RoleController::class, 'update'])->name('update');
        
        // Gestion des utilisateurs
        Route::get('/users/list', [RoleController::class, 'users'])->name('users');
        Route::put('/users/{user}/role', [RoleController::class, 'updateUserRole'])->name('update-user-role');
        
        // Matrice des permissions
        Route::get('/permissions/matrix', [RoleController::class, 'permissions'])->name('permissions');
        Route::post('/permissions/update', [RoleController::class, 'updatePermissions'])->name('update-permissions');
    });
});