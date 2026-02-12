<?php



use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuditController;

Route::get('/off-days', [AuditController::class, 'getOffDays']);
Route::get('/available-slots', [AuditController::class, 'getAvailableSlots']);
Route::get('/has-slots', [AuditController::class, 'hasSlots']);
Route::post('/submit', [AuditController::class, 'submit']);