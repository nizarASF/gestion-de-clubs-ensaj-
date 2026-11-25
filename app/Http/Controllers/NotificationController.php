<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = $request->user()->notifications()->paginate(10);

        return view('notifications.index', compact('notifications'));
    }

    /** Marque comme lue puis suit le lien stocké dans la notification. */
    public function read(Request $request, string $id)
    {
        $notification = DatabaseNotification::findOrFail($id);
        abort_unless($notification->notifiable_id === $request->user()->id, 403);
        $notification->markAsRead();

        return redirect($notification->data['link'] ?? route('notifications.index'));
    }
}
