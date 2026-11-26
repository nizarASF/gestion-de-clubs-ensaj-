<?php

namespace App\Http\Controllers\Manage;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user();
        $club = $user->club;

        // Une seule requête groupée pour les compteurs de posts.
        $raw = $club->posts()->selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status');
        $posts = [];
        foreach (Status::cases() as $status) {
            $posts[$status->value] = (int) ($raw[$status->value] ?? 0);
        }

        return view('manage.dashboard', [
            'club' => $club,
            'posts' => $posts,
            'mediaPending' => $club->media()->where('status', Status::Pending->value)->count(),
            'mediaRejected' => $club->media()->where('status', Status::Rejected->value)->count(),
            'teamPending' => $club->teamMembers()->where('status', Status::Pending->value)->count(),
            'unread' => $user->unreadMessagesCount(),
        ]);
    }
}
