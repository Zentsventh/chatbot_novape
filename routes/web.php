<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Rutas Públicas (Sin autenticación)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('inbox.index');
    }
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Rutas Protegidas (Requieren autenticación)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/inbox', function () {
        return view('inbox.index');
    })->name('inbox.index');

    Route::get('/contactos', function () {
        return view('contacts.index');
    })->name('contactos.index');

    Route::get('/crm', function () {
        return view('crm.index');
    })->name('crm.index');

    Route::get('/chatbot', function () {
        return view('chatbot.index');
    })->name('chatbot.index');

    Route::get('/calendario', function () {
        return view('calendar.index');
    })->name('calendar.index');

    Route::get('/chat-interno', function () {
        return view('internal-chat.index');
    })->name('internal-chat.index');

    Route::get('/configuracion', function () {
        return view('settings.index');
    })->name('settings.index');

    // Gestión de Equipo
    Route::get('/equipo', [\App\Http\Controllers\TeamManagementController::class, 'index'])->name('settings.team.index');
    Route::post('/equipo', [\App\Http\Controllers\TeamManagementController::class, 'store'])->name('settings.team.store');
    Route::delete('/equipo/{user}', [\App\Http\Controllers\TeamManagementController::class, 'destroy'])->name('settings.team.destroy');
});
