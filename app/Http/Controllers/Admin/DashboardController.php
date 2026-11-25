<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Post;
use App\Models\TeamMember;
use App\Models\User;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('admin.dashboard', [
            'counts' => [
                'user' => User::pending()->count(),
                'post' => Post::pending()->count(),
                'team' => TeamMember::pending()->count(),
                'media' => Media::pending()->count(),
            ],
        ]);
    }
}
