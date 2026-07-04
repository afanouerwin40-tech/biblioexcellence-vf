<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return back()->with('success', 'Notification marquée comme lue.');
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }

    // Supprimer une notification (AJAX)
    public function destroy($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->delete();

        return response()->json(['success' => true, 'message' => 'Notification supprimée.']);
    }

    // Supprimer toutes les notifications (AJAX)
    public function destroyAll()
    {
        auth()->user()->notifications()->delete();

        return response()->json(['success' => true, 'message' => 'Toutes les notifications supprimées.']);
    }
}
