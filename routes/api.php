<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\WhatsAppWebhookController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Webhooks de Meta (Facebook Messenger)
Route::get('/webhooks/messenger', [WebhookController::class, 'verifyMessenger']);
Route::post('/webhooks/messenger', [WebhookController::class, 'handleMessenger']);

// Webhooks de Meta (WhatsApp Cloud API)
Route::get('/webhooks/whatsapp', [WhatsAppWebhookController::class, 'verify']);
Route::post('/webhooks/whatsapp', [WhatsAppWebhookController::class, 'handle']);

