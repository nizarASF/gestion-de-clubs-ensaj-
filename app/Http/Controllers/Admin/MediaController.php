<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Services\ImageService;

class MediaController extends Controller
{
    public function __construct(private ImageService $images) {}

    public function destroy(Media $media)
    {
        $this->images->delete($media->path, $media->thumbnail_path);
        $media->delete();

        return back()->with('status', 'Média supprimé.');
    }
}
