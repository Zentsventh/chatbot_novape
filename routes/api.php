<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\WhatsAppWebhookController;
use App\Http\Controllers\Api\InboxController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\DealController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\ChatbotController;
use App\Http\Controllers\Api\CannedResponseController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\SequenceController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\KnowledgeBaseController;

/*
|--------------------------------------------------------------------------
| Webhooks (Público — Sin autenticación, con rate limiting)
|--------------------------------------------------------------------------
| Meta requiere acceso público para verificar y enviar webhooks.
*/
Route::middleware('throttle:120,1')->group(function () {
    // Webhooks de Meta (Facebook Messenger)
    Route::get('/webhooks/messenger', [WebhookController::class, 'verifyMessenger']);
    Route::post('/webhooks/messenger', [WebhookController::class, 'handleMessenger']);

    // Webhooks de Meta (Instagram)
    Route::get('/webhooks/instagram', [WebhookController::class, 'verifyInstagram']);
    Route::post('/webhooks/instagram', [WebhookController::class, 'handleInstagram']);

    // Webhooks de Meta (WhatsApp Cloud API)
    Route::get('/webhooks/whatsapp', [WhatsAppWebhookController::class, 'verify']);
    Route::post('/webhooks/whatsapp', [WhatsAppWebhookController::class, 'handle']);
});

/*
|--------------------------------------------------------------------------
| API Autenticadas (Sanctum con cookie-based auth para SPA)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum'])->group(function () {

    // Perfil
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::put('/profile/password', [ProfileController::class, 'updatePassword']);
    
    // Search
    Route::get('/search', [SearchController::class, 'index']);
    
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);

    // Usuario autenticado
    Route::get('/user', function (Request $request) {
        $user = $request->user();
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role ?? 'tenant_agent',
            'tenant_id' => $user->tenant_id,
        ]);
    });

    // Bandeja Omnicanal (Inbox)
    Route::prefix('inbox')->group(function () {
        Route::get('/conversations', [InboxController::class, 'conversations']);
        Route::get('/conversations/{conversation}/messages', [InboxController::class, 'messages']);
        Route::post('/conversations/{conversation}/messages', [InboxController::class, 'sendMessage']);
        Route::post('/conversations/{conversation}/assign', [InboxController::class, 'assignAgent']);
        Route::post('/conversations/{conversation}/unassign', [InboxController::class, 'unassignAgent']);
        Route::post('/conversations/{conversation}/notes', [InboxController::class, 'addInternalNote']);
    });

    // CRM — Contactos, Deals, Eventos
    Route::apiResource('contacts', ContactController::class);
    Route::post('contacts/{contact}/tags', [TagController::class, 'attachToContact']);
    Route::delete('contacts/{contact}/tags/{tag}', [TagController::class, 'detachFromContact']);
    
    Route::apiResource('deals', DealController::class);
    Route::put('deals/{deal}/stage', [DealController::class, 'updateStage']);
    Route::apiResource('events', EventController::class);

    // Tags & Canned Responses & Sequences
    Route::apiResource('tags', TagController::class);
    Route::apiResource('canned-responses', CannedResponseController::class);
    Route::apiResource('sequences', SequenceController::class);

    // Chatbot Config
    Route::get('/contacts/{contact}', [ContactController::class, 'show']);

    // Team Chat (Chat Interno)
    Route::get('/team-chat/users', [\App\Http\Controllers\Api\TeamChatController::class, 'users']);
    Route::get('/team-chat/conversations', [\App\Http\Controllers\Api\TeamChatController::class, 'index']);
    Route::post('/team-chat/conversations', [\App\Http\Controllers\Api\TeamChatController::class, 'startChat']);
    Route::get('/team-chat/conversations/{conversation}/messages', [\App\Http\Controllers\Api\TeamChatController::class, 'messages']);
    Route::post('/team-chat/conversations/{conversation}/messages', [\App\Http\Controllers\Api\TeamChatController::class, 'sendMessage']);

    Route::get('chatbot/settings', [ChatbotController::class, 'index']);
    Route::put('chatbot/settings', [ChatbotController::class, 'update']);

    // Knowledge Base
    Route::get('chatbot/knowledge', [KnowledgeBaseController::class, 'index']);
    Route::post('chatbot/knowledge/upload', [KnowledgeBaseController::class, 'upload']);
    Route::post('chatbot/knowledge/url', [KnowledgeBaseController::class, 'storeUrl']);
    Route::delete('chatbot/knowledge/{id}', [KnowledgeBaseController::class, 'destroy']);
});
