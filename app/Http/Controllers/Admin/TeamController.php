<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use App\Services\ImageService;

class TeamController extends Controller
{
    public function __construct(private ImageService $images) {}

    public function destroy(TeamMember $teamMember)
    {
        $this->images->delete($teamMember->photo_path);
        $teamMember->delete();

        return back()->with('status', 'Fiche du bureau supprimée.');
    }
}
