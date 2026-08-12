<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        $notifications = DB::table('notifications')
            ->where('notifiable_type', get_class($user))
            ->where('notifiable_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->map(function ($notif) {
                return [
                    'id' => $notif->id,
                    'data' => json_decode($notif->data, true),
                    'read_at' => $notif->read_at,
                    'created_at' => $notif->created_at,
                ];
            });

        return response()->json($notifications);
    }

    public function unreadCount(Request $request)
    {
        $user = $request->user();
        
        $count = DB::table('notifications')
            ->where('notifiable_type', get_class($user))
            ->where('notifiable_id', $user->id)
            ->whereNull('read_at')
            ->count();

        return response()->json(['count' => $count]);
    }

    public function markAsRead(Request $request, $id)
    {
        $user = $request->user();
        
        DB::table('notifications')
            ->where('id', $id)
            ->where('notifiable_id', $user->id)
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }
}
