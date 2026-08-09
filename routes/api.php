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

// Bandeja Omnicanal (Inbox) API
Route::get('/inbox/conversations', [\App\Http\Controllers\Api\InboxController::class, 'conversations']);
Route::get('/inbox/conversations/{conversation}/messages', [\App\Http\Controllers\Api\InboxController::class, 'messages']);
Route::post('/inbox/conversations/{conversation}/messages', [\App\Http\Controllers\Api\InboxController::class, 'sendMessage']);
Route::post('/inbox/conversations/{conversation}/assign', [\App\Http\Controllers\Api\InboxController::class, 'assignAgent']);
Route::post('/inbox/conversations/{conversation}/unassign', [\App\Http\Controllers\Api\InboxController::class, 'unassignAgent']);
Route::post('/inbox/conversations/{conversation}/notes', [\App\Http\Controllers\Api\InboxController::class, 'addInternalNote']);

// Nuevos Módulos API
Route::apiResource('contacts', \App\Http\Controllers\Api\ContactController::class);
Route::apiResource('deals', \App\Http\Controllers\Api\DealController::class);
Route::put('deals/{deal}/stage', [\App\Http\Controllers\Api\DealController::class, 'updateStage']);
Route::apiResource('events', \App\Http\Controllers\Api\EventController::class);
Route::get('chatbot/settings', [\App\Http\Controllers\Api\ChatbotController::class, 'index']);
Route::put('chatbot/settings', [\App\Http\Controllers\Api\ChatbotController::class, 'update']);

Route::get('chatbot/knowledge', [\App\Http\Controllers\KnowledgeBaseController::class, 'index']);
Route::post('chatbot/knowledge/upload', [\App\Http\Controllers\KnowledgeBaseController::class, 'upload']);
Route::post('chatbot/knowledge/url', [\App\Http\Controllers\KnowledgeBaseController::class, 'storeUrl']);
Route::delete('chatbot/knowledge/{id}', [\App\Http\Controllers\KnowledgeBaseController::class, 'destroy']);
