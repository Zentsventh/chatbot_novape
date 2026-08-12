<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Aquí registramos los canales de broadcasting que requieren autorización.
| El usuario solo puede escuchar eventos de su propio tenant.
|
*/

Broadcast::channel('tenant.{tenantId}', function ($user, $tenantId) {
    return (int) $user->tenant_id === (int) $tenantId;
});

Broadcast::channel('team.conversation.{conversationId}', function ($user, $conversationId) {
    // El usuario debe pertenecer a la conversación
    return \App\Models\TeamConversation::find($conversationId)?->participants()->where('user_id', $user->id)->exists();
});
