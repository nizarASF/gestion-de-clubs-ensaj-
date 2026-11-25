<?php

namespace App\Http\Controllers\Manage;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\TeamMemberRequest;
use App\Models\TeamMember;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TeamController extends Controller
{
    public function __construct(private ImageService $images) {}

    public function index()
    {
        Gate::authorize('viewAny', TeamMember::class);
        $members = Auth::user()->club->teamMembers()
            ->latest()
            ->paginate(15);

        return view('manage.team.index', compact('members'));
    }

    public function create()
    {
        Gate::authorize('create', TeamMember::class);

        return view('manage.team.create');
    }

    public function store(TeamMemberRequest $request)
    {
        Gate::authorize('create', TeamMember::class);
        $data = $request->validated();

        $member = Auth::user()->club->teamMembers()->make([
            'name' => $data['name'],
            'position' => $data['position'],
            'academic_year' => $data['academic_year'],
            'sort_order' => $data['sort_order'] ?? 0,
        ]);
        $member->proposed_by = Auth::id();
        $member->status = Status::Pending;
        if ($request->hasFile('photo')) {
            $member->photo_path = $this->images->storeSquare($request->file('photo'), 'team', 400)['path'];
        }
        $member->save();

        return redirect()->route('manage.team.index')->with('status', 'Fiche proposée, en attente de validation.');
    }

    public function edit(int $teamMember)
    {
        $member = $this->myMember($teamMember);
        Gate::authorize('update', $member);

        return view('manage.team.edit', compact('member'));
    }

    public function update(TeamMemberRequest $request, int $teamMember)
    {
        $member = $this->myMember($teamMember);
        Gate::authorize('update', $member);
        $data = $request->validated();

        $member->fill([
            'name' => $data['name'],
            'position' => $data['position'],
            'academic_year' => $data['academic_year'],
            'sort_order' => $data['sort_order'] ?? $member->sort_order,
        ]);
        if ($request->hasFile('photo')) {
            $this->images->delete($member->photo_path);
            $member->photo_path = $this->images->storeSquare($request->file('photo'), 'team', 400)['path'];
        }
        // Une fiche refusée repasse en attente dès qu'on la modifie.
        if ($member->status === Status::Rejected) {
            $member->status = Status::Pending;
            $member->rejection_reason = null;
        }
        $member->save();

        return redirect()->route('manage.team.index')->with('status', 'Fiche mise à jour.');
    }

    private function myMember(int $id): TeamMember
    {
        $club = Auth::user()->club;
        abort_if(! $club, 403);

        $member = TeamMember::findOrFail($id);
        abort_unless($member->club_id === $club->id, 403);

        return $member;
    }
}
