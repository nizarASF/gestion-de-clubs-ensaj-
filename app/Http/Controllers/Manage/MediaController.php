<?php

namespace App\Http\Controllers\Manage;

use App\Enums\MediaType;
use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\MediaRequest;
use App\Models\Media;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class MediaController extends Controller
{
    public function __construct(private ImageService $images) {}

    public function index()
    {
        Gate::authorize('viewAny', Media::class);
        $media = Auth::user()->club->media()
            ->with('uploader')
            ->latest()
            ->paginate(15);

        return view('manage.media.index', compact('media'));
    }

    public function create()
    {
        Gate::authorize('create', Media::class);

        return view('manage.media.create');
    }

    public function store(MediaRequest $request)
    {
        Gate::authorize('create', Media::class);
        $file = $request->file('file');
        $mime = $file->getMimeType();
        $isPhoto = str_starts_with($mime, 'image/');

        if ($isPhoto) {
            $stored = $this->images->store($file, 'media', 1600, 400);
            $path = $stored['path'];
            $thumbnail = $stored['thumbnail'];
        } else {
            // Vidéo : nom aléatoire et extension imposée par le MIME vérifié plus haut.
            $path = $this->images->storeVideo($file, 'media');
            $thumbnail = null;
        }

        $club = Auth::user()->club;
        // Les champs techniques sont affectés hors du mass-assignment.
        $media = $club->media()->make(['title' => $request->validated('title')]);
        $media->uploaded_by = Auth::id();
        $media->type = $isPhoto ? MediaType::Photo : MediaType::Video;
        $media->path = $path;
        $media->thumbnail_path = $thumbnail;
        $media->mime_type = $mime;
        $media->size_bytes = $file->getSize();
        $media->status = Status::Pending;
        $media->save();

        return redirect()->route('manage.media.index')->with('status', 'Média envoyé, en attente de validation du BDE.');
    }
}
