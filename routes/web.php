<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\UtilisateurController;
use App\Http\Controllers\Admin\EmailTemplateController;
use App\Http\Controllers\Admin\RequiredDocumentController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Models\RequiredDocument;
use App\Http\Controllers\Demandeur\DemandeController;
use App\Http\Controllers\Demandeur\DashboardController;
use App\Http\Controllers\RH\DashboardController as RHDashboardController;
use App\Http\Controllers\Service\DashboardController as ServiceDashboardController;
use App\Http\Controllers\Service\CahierChargeController;
use App\Http\Controllers\Service\EntretienController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DebugController;
use App\Models\DemandeStage;
use App\Models\ServiceUCA;
use App\Models\User;

Route::get('/', function () {
    return view('welcome');
})->name('accueil');

// Routes d'authentification
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/demandeur/demandes/{demandeStage}/cahier/telechargement', [DemandeController::class, 'downloadCahier'])
    ->middleware('signed')
    ->name('demandeur.demandes.cahier.public');

// Route de debug (temporaire)
Route::get('/debug/fix-service-user-id', [DebugController::class, 'fixServiceUserId']);

// Routes protégées
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [AuthController::class, 'showVerifyNotice'])
        ->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();

        return redirect()->route('demandeur.dashboard')->with('success', 'Adresse email verifiee avec succes.');
    })->middleware('signed')->name('verification.verify');
    Route::post('/email/verification-notification', [AuthController::class, 'sendEmailVerificationNotification'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('/demandes/{demandeStage}/cv', [RHDashboardController::class, 'cv'])
        ->name('demandes.cv');

    // Admin (routes et ressources liées à l'administration)
    Route::middleware('role:admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/dashboard', function () {
                $stats = [
                    'users_total' => User::count(),
                    'users_admin' => User::where('role', 'admin')->count(),
                    'users_rh' => User::where('role', 'rh')->count(),
                    'users_service' => User::where('role', 'service')->count(),
                    'users_demandeur' => User::where('role', 'demandeur')->count(),
                    'services_total' => ServiceUCA::count(),
                    'services_actifs' => ServiceUCA::where('actif', true)->count(),
                    'demandes_total' => DemandeStage::count(),
                    'demandes_soumises' => DemandeStage::where('statut', 'soumise')->count(),
                    'demandes_acceptees' => DemandeStage::whereIn('statut', ['acceptee_rh', 'affectee_service'])->count(),
                    'demandes_refusees' => DemandeStage::where('statut', 'refusee_rh')->count(),
                    'documents_total' => RequiredDocument::count(),
                    'documents_actifs' => RequiredDocument::where('is_active', true)->count(),
                ];

                $recentUsers = User::orderBy('created_at', 'desc')->limit(5)->get();
                $recentDemandes = DemandeStage::with(['user', 'service'])
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();
                $requiredDocuments = RequiredDocument::where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('name')
                    ->get();

                return view('admin.dashboard', compact('stats', 'recentUsers', 'recentDemandes', 'requiredDocuments'));
            })->name('dashboard');

            Route::resource('services', ServiceController::class)
                ->parameters(['services' => 'serviceUCA']);
            Route::resource('utilisateurs', UtilisateurController::class)
                ->parameters(['utilisateurs' => 'user']);
            Route::resource('email-templates', EmailTemplateController::class)
                ->except(['show'])
                ->parameters(['email-templates' => 'emailTemplate']);
            Route::resource('required-documents', RequiredDocumentController::class)
                ->except(['show'])
                ->parameters(['required-documents' => 'requiredDocument']);
            Route::get('audit-logs', [AuditLogController::class, 'index'])
                ->name('audit-logs.index');
    });

    // RH
    Route::get('/rh/dashboard', [RHDashboardController::class, 'index'])
        ->name('rh.dashboard');

    Route::get('/rh/export', [RHDashboardController::class, 'export'])
        ->name('rh.export');

    Route::get('/rh/demandes', [RHDashboardController::class, 'demandes'])
        ->name('rh.demandes');

    Route::get('/rh/demandes/index', [RHDashboardController::class, 'demandes'])
        ->name('rh.demandes.index');

    Route::get('/rh/demandes/{demandeStage}', [RHDashboardController::class, 'show'])
        ->name('rh.demandes.show');

    Route::get('/rh/demandes/{demandeStage}/cv', [RHDashboardController::class, 'cv'])
        ->name('rh.demandes.cv');

    Route::post('/rh/demandes/{demandeStage}/accepter', [RHDashboardController::class, 'accepter'])
        ->name('rh.demandes.accepter');

    Route::post('/rh/demandes/{demandeStage}/refuser', [RHDashboardController::class, 'refuser'])
        ->name('rh.demandes.refuser');

    // Service
    Route::middleware('role:service')->group(function () {
        Route::get('/service/dashboard', [ServiceDashboardController::class, 'index'])
            ->name('service.dashboard');
        Route::get('/service/demandes', [ServiceDashboardController::class, 'demandes'])
            ->name('service.demandes');
        Route::get('/service/demandes/{demandeStage}', [ServiceDashboardController::class, 'show'])
            ->name('service.demandes.show');
        Route::post('/service/demandes/{demandeStage}/accepter', [ServiceDashboardController::class, 'accepter'])
            ->name('service.demandes.accepter');
        Route::post('/service/demandes/{demandeStage}/refuser', [ServiceDashboardController::class, 'refuser'])
            ->name('service.demandes.refuser');
        Route::post('/service/demandes/{demandeStage}/planifier-entretien', [ServiceDashboardController::class, 'planifierEntretien'])
            ->name('service.demandes.planifier-entretien');
        Route::post('/service/demandes/{demandeStage}/entretien-realise', [ServiceDashboardController::class, 'marquerEntretienRealise'])
            ->name('service.demandes.entretien-realise');
        Route::post('/service/demandes/{demandeStage}/renseigner-sujet', [ServiceDashboardController::class, 'renseignerSujet'])
            ->name('service.demandes.renseigner-sujet');
        Route::post('/service/demandes/{demandeStage}/partager-cahier', [ServiceDashboardController::class, 'partagerCahier'])
            ->name('service.demandes.partager-cahier');
        Route::post('/service/demandes/{demandeStage}/cloturer', [ServiceDashboardController::class, 'cloturer'])
            ->name('service.demandes.cloturer');
        Route::get('/service/demandes/{demandeStage}/cahier', [ServiceDashboardController::class, 'downloadCahier'])
            ->name('service.demandes.cahier');
    });

    // Demandeur
    Route::get('/demandeur/dashboard', [DashboardController::class, 'index'])
        ->middleware('verified')
        ->name('demandeur.dashboard');

    Route::prefix('demandeur')
        ->name('demandeur.')
        ->middleware('verified')
        ->group(function () {
            Route::resource('demandes', DemandeController::class, ['except' => ['show']]);
            Route::get('demandes/{demandeStage}', [DemandeController::class, 'show'])
                ->name('demandes.show');
            Route::get('demandes/{demandeStage}/cahier', [DemandeController::class, 'downloadCahier'])
                ->name('demandes.cahier');
        });
        
});
