<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Club;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /** Une seule vue pour les fiches approuvées de toutes les années. */
    public function __invoke(Club $club, Request $request)
    {
        abort_unless($club->is_active, 404);

        $years = $club->teamMembers()
            ->approved()
            ->distinct()
            ->orderByDesc('academic_year')
            ->pluck('academic_year');

        $year = (string) $request->query('year', '');
        if (! $years->contains($year)) {
            $year = (string) $years->first();
        }

        $members = $club->teamMembers()
            ->approved()
            ->when($year !== '', fn ($q) => $q->where('academic_year', $year))
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('public.clubs.team', compact('club', 'years', 'year', 'members'));
    }
}
