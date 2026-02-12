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



Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/about', [App\Http\Controllers\AboutController::class, 'index'])->name('about');
Route::get('/contact', [App\Http\Controllers\ContactController::class, 'index'])->name('contact');

// Route pour les mentions légales
Route::get('/mentions-legales', function () {
    return view('mentions-legales');
});

// Route pour la confidentialité
Route::get('/confidentialite', function () {
    return view('confidentialite');
});


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

    Route::post('/calendar/store', [PlanningController::class, 'storeCalendar'])->name('admin.calendar.store');
    Route::delete('/calendar/destroy/{id}', [PlanningController::class, 'destroyCalendar'])->name('admin.calendar.destroy');

    Route::get('/stats', [StatsController::class, 'index'])->name('admin.stats');
});