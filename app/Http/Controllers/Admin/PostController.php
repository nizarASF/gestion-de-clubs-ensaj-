<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Club;
use App\Models\Post;
use App\Services\ImageService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct(private ImageService $images) {}

    public function index(Request $request)
    {
        $clubId = $request->query('club_id');
        $status = $request->query('status');

        $posts = Post::with(['club', 'author', 'images'])
            ->when($clubId, fn ($q) => $q->where('club_id', (int) $clubId))
            ->when(in_array($status, ['draft', 'pending', 'approved', 'rejected'], true), fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.posts.index', [
            'posts' => $posts,
            'clubs' => Club::orderBy('name')->get(),
            'clubId' => $clubId,
            'status' => $status,
        ]);
    }

    public function create()
    {
        return view('admin.posts.create', ['clubs' => Club::active()->orderBy('name')->get()]);
    }

    public function store(PostRequest $request)
    {
        $data = $request->validated();
        $club = Club::findOrFail($data['club_id']);

        $post = $club->posts()->make([
            'title' => $data['title'],
            'body' => $data['body'],
        ]);
        $post->author_id = $request->user()->id;
        $post->status = Status::Approved;
        $post->published_at = now();
        $post->validated_by = $request->user()->id;
        $post->validated_at = now();
        $post->save();

        foreach ($request->file('images', []) as $index => $file) {
            $stored = $this->images->store($file, 'posts', 1600, 400);
            $post->images()->create([
                'path' => $stored['path'],
                'thumbnail_path' => $stored['thumbnail'],
                'sort_order' => $index,
            ]);
        }

        return redirect()->route('admin.posts.index')->with('status', 'Post publié.');
    }

    public function destroy(Post $post)
    {
        foreach ($post->images as $image) {
            $this->images->delete($image->path, $image->thumbnail_path);
        }
        $post->delete();

        return back()->with('status', 'Post supprimé.');
    }
}
