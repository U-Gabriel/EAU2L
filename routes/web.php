<?php


use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;


use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PlanningController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\StatsController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\AuditController;



Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/about', [App\Http\Controllers\AboutController::class, 'index'])->name('about');
Route::get('/contact', [App\Http\Controllers\ContactController::class, 'index'])->name('contact');
Route::get('/insights', [App\Http\Controllers\BlogController::class, 'index'])->name('blog.index');

// Route pour voir un article complet
Route::get('/insights/{id}', [App\Http\Controllers\BlogController::class, 'show'])->name('blog.show');
Route::post('/newsletter/subscribe', [NewsletterController::class, 'store'])->name('newsletter.store');

// Route pour les mentions légales
Route::get('/mentions-legales', function () {
    return view('mentions-legales');
});

// Route pour la confidentialité
Route::get('/confidentialite', function () {
    return view('confidentialite');
});

// --- SYSTÈME DE VÉRIFICATION OTP ---
// Route pour envoyer le mail avec le code
Route::post('/send-otp', [ContactController::class, 'sendOtp'])->name('otp.send');

// Route pour vérifier si le code saisi par l'utilisateur est correct
Route::post('/verify-otp', [ContactController::class, 'verifyOtp'])->name('otp.verify');

// --- AUTHENTIFICATION ---
$secretPath = 'hlqzfhjzm546FG65ERF';

// Redirection si on tape l'URL par défaut
Route::get('/login', function () use ($secretPath) {
    return redirect()->route('admin.login');
})->name('login');

// Ta page de connexion
Route::get($secretPath . '/admin/connexion', function () {
    if (Auth::check()) {
        return redirect()->route('admin.dashboard');
    }

    return view('admin.login'); 
})->name('admin.login');

Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// --- ZONE ADMIN UNIQUE ---
Route::middleware(['auth'])->prefix($secretPath . '/admin')->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::post('/users/store', [UserController::class, 'store'])->name('admin.users.store');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    // Dans ton groupe de routes admin :
    Route::get('/planning', [PlanningController::class, 'index'])->name('admin.planning.index');

    Route::get('/modifications_pages', [PageController::class, 'index'])->name('admin.modifications');
    Route::post('/modifications_pages/block/{id}', [PageController::class, 'updateBlock'])->name('admin.block.update');



    Route::post('/admin/block/update/{id}', [AuditController::class, 'updateBlock'])->name('admin.block.updateMail');

    // Route spécifique pour les logos
    Route::post('/modifications_pages/logo/{id}', [PageController::class, 'updateLogo'])->name('admin.logo.update');

    // Route dédiée pour les couleurs
    Route::post('/admin/block/update-color/{id}', [PageController::class, 'updateColor'])->name('admin.block.updateColor');

    // Liste des articles
    Route::get('/modifications_pages/blog', [PageController::class, 'blogIndex'])->name('admin.blog.index');

    // Formulaire de création
    Route::get('/modifications_pages/blog/create', [PageController::class, 'blogCreate'])->name('admin.blog.create');

    Route::post('/admin/blog/newsletter/{id}', [PageController::class, 'sendNewsletter'])->name('admin.blog.newsletter');

    Route::get('/modifications_pages/blog/edit/{id}', [PageController::class, 'blogEdit'])->name('admin.blog.edit');

    Route::put('/modifications_pages/blog/update/{id}', [PageController::class, 'update'])->name('admin.blog.update');
    
    Route::delete('/modifications_pages/blog/delete/{id}', [PageController::class, 'blogDestroy'])->name('admin.blog.destroy');

    // Action de sauvegarde
    Route::post('/modifications_pages/blog/store', [PageController::class, 'blogStore'])->name('admin.blog.store');

    // Action pour l'upload d'images via Quill (AJAX)
    Route::post('/modifications_pages/blog/upload-image', [PageController::class, 'blogUploadImage'])->name('admin.blog.upload_image');

    Route::post('/calendar/store', [PlanningController::class, 'storeCalendar'])->name('admin.calendar.store');
    Route::delete('/calendar/destroy/{id}', [PlanningController::class, 'destroyCalendar'])->name('admin.calendar.destroy');

    Route::get('/stats', [StatsController::class, 'index'])->name('admin.stats');
});