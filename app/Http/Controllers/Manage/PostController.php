<?php

namespace App\Http\Controllers\Manage;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    public function __construct(private ImageService $images) {}

    public function index()
    {
        Gate::authorize('viewAny', Post::class);
        // La requête part du club de l'utilisateur, jamais d'un find($id) brut.
        $posts = Auth::user()->club->posts()
            ->with('images')
            ->latest()
            ->paginate(15);

        return view('manage.posts.index', compact('posts'));
    }

    public function create()
    {
        Gate::authorize('create', Post::class);

        return view('manage.posts.create');
    }

    public function store(PostRequest $request)
    {
        Gate::authorize('create', Post::class);
        $data = $request->validated();

        $post = Auth::user()->club->posts()->make([
            'title' => $data['title'],
            'body' => $data['body'],
        ]);
        $post->author_id = Auth::id();
        $post->status = Status::Draft;
        $post->save();

        $this->storeImages($post, $request->file('images', []));

        return redirect()->route('manage.posts.index')->with('status', 'Brouillon créé. Vous pouvez le soumettre au BDE.');
    }

    public function edit(int $post)
    {
        $post = $this->myPost($post);
        Gate::authorize('update', $post);

        return view('manage.posts.edit', compact('post'));
    }

    public function update(PostRequest $request, int $post)
    {
        $post = $this->myPost($post);
        Gate::authorize('update', $post);
        $data = $request->validated();

        $remove = array_intersect($data['remove_images'] ?? [], $post->images->pluck('id')->all());
        $imagesToRemove = $post->images->whereIn('id', $remove);
        $newImages = $request->file('images', []);

        // On vérifie d'abord : une erreur ne doit pas supprimer d'images existantes.
        if ($post->images()->count() - $imagesToRemove->count() + count($newImages) > 5) {
            return back()->withErrors(['images' => 'Un post ne peut pas contenir plus de 5 images.']);
        }

        // Le statut n'est pas touché : une modification directe ne remet pas en attente.
        $post->title = $data['title'];
        $post->body = $data['body'];
        $post->save();

        foreach ($imagesToRemove as $image) {
            $this->images->delete($image->path, $image->thumbnail_path);
            $image->delete();
        }

        $this->storeImages($post, $newImages, $post->images()->count());

        return redirect()->route('manage.posts.index')->with('status', 'Post mis à jour.');
    }

    public function submit(int $post)
    {
        $post = $this->myPost($post);
        Gate::authorize('submit', $post);

        if (! in_array($post->status, [Status::Draft, Status::Rejected], true)) {
            return back()->with('error', 'Ce post ne peut plus être soumis.');
        }

        $post->status = Status::Pending;
        $post->rejection_reason = null;
        $post->save();

        return redirect()->route('manage.posts.index')->with('status', 'Post soumis au BDE.');
    }

    private function myPost(int $id): Post
    {
        $club = Auth::user()->club;
        abort_if(! $club, 403);

        // On trouve la ressource puis on contrôle son club : 403 et non 404
        // si elle existe mais appartient à un autre club.
        $post = Post::with('images')->findOrFail($id);
        abort_unless($post->club_id === $club->id, 403);

        return $post;
    }

    private function storeImages(Post $post, array $files, int $startOrder = 0): void
    {
        foreach ($files as $index => $file) {
            $stored = $this->images->store($file, 'posts', 1600, 400);
            $post->images()->create([
                'path' => $stored['path'],
                'thumbnail_path' => $stored['thumbnail'],
                'sort_order' => $startOrder + $index,
            ]);
        }
    }
}
