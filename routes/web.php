<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

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
