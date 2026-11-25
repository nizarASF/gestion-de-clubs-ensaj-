<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Club;

class ClubController extends Controller
{
    /** Un seul gabarit sert les 11 clubs. */
    public function show(Club $club)
    {
        abort_unless($club->is_active, 404);

        $media = $club->media()->approved()->latest()->get();
        $posts = $club->posts()->approved()
            ->with(['images', 'author'])
            ->orderByDesc('published_at')
            ->paginate(10);

        return view('public.clubs.show', compact('club', 'media', 'posts'));
    }
}
