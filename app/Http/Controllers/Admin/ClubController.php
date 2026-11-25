<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClubRequest;
use App\Models\Club;
use App\Services\ImageService;
use Illuminate\Http\Request;

class ClubController extends Controller
{
    public function __construct(private ImageService $images) {}

    public function index()
    {
        $clubs = Club::orderBy('name')->paginate(15);

        return view('admin.clubs.index', compact('clubs'));
    }

    public function create()
    {
        return view('admin.clubs.create', ['club' => new Club()]);
    }

    public function store(ClubRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);
        if ($request->hasFile('logo')) {
            $data['logo_path'] = $this->images->store($request->file('logo'), 'logos', 400)['path'];
        }
        unset($data['logo']);
        Club::create($data);

        return redirect()->route('admin.clubs.index')->with('status', 'Club créé.');
    }

    public function edit(Club $club)
    {
        return view('admin.clubs.edit', compact('club'));
    }

    public function update(ClubRequest $request, Club $club)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
        if ($request->hasFile('logo')) {
            $data['logo_path'] = $this->images->store($request->file('logo'), 'logos', 400)['path'];
            $this->images->delete($club->logo_path);
        }
        unset($data['logo']);
        $club->update($data);

        return redirect()->route('admin.clubs.index')->with('status', 'Club mis à jour.');
    }
}
