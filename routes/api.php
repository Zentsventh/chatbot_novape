<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebhookController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Webhooks de Meta (Facebook Messenger, Instagram, WhatsApp)
Route::get('/webhooks/messenger', [WebhookController::class, 'verifyMessenger']);
Route::post('/webhooks/messenger', [WebhookController::class, 'handleMessenger']);
