<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\RejectRequest;
use App\Models\Media;
use App\Models\Post;
use App\Models\TeamMember;
use App\Models\User;
use App\Notifications\ModerationResult;
use Illuminate\Http\Request;

class ModerationController extends Controller
{
    /** Table unique type → modèle : aucun switch répété. */
    private const MODELS = [
        'user' => User::class,
        'post' => Post::class,
        'team' => TeamMember::class,
        'media' => Media::class,
    ];

    private const LABELS = [
        'user' => 'inscription',
        'post' => 'post',
        'team' => 'fiche du bureau',
        'media' => 'média',
    ];

    public function index(Request $request)
    {
        $tab = $request->query('tab', 'user');
        if (! array_key_exists($tab, self::MODELS)) {
            $tab = 'user';
        }

        $items = match ($tab) {
            'user' => User::pending()->latest()->paginate(10),
            'post' => Post::pending()->with(['club', 'author'])->latest()->paginate(10),
            'team' => TeamMember::pending()->with(['club', 'proposer'])->latest()->paginate(10),
            'media' => Media::pending()->with(['club', 'uploader'])->latest()->paginate(10),
        };

        $counts = [
            'user' => User::pending()->count(),
            'post' => Post::pending()->count(),
            'team' => TeamMember::pending()->count(),
            'media' => Media::pending()->count(),
        ];

        return view('admin.moderation', [
            'tab' => $tab,
            'items' => $items,
            'counts' => $counts,
            'tabs' => self::LABELS,
        ]);
    }

    public function approve(Request $request, string $type, int $id)
    {
        $modelClass = $this->modelClass($type);
        // Un élément déjà traité ne peut pas être re-décidé par un POST direct.
        $item = $modelClass::where('status', Status::Pending->value)->findOrFail($id);
        $this->decide($item, $type, Status::Approved, null, $request->user());

        return back()->with('status', ucfirst(self::LABELS[$type]) . ' validé.');
    }

    public function reject(RejectRequest $request, string $type, int $id)
    {
        $modelClass = $this->modelClass($type);
        $item = $modelClass::where('status', Status::Pending->value)->findOrFail($id);
        $this->decide($item, $type, Status::Rejected, $request->validated('rejection_reason'), $request->user());

        return back()->with('status', ucfirst(self::LABELS[$type]) . ' refusé.');
    }

    private function modelClass(string $type): string
    {
        abort_unless(array_key_exists($type, self::MODELS), 404);

        return self::MODELS[$type];
    }

    private function decide($item, string $type, Status $status, ?string $reason, User $bde): void
    {
        $item->status = $status;
        $item->rejection_reason = $status === Status::Rejected ? $reason : null;
        $item->validated_by = $bde->id;
        $item->validated_at = now();
        if ($status === Status::Approved && $type === 'post') {
            $item->published_at = now();
        }
        $item->save();

        // Les inscriptions n'envoient pas de notification (le compte est encore inactif).
        if ($type === 'user') {
            return;
        }

        $author = match ($type) {
            'post' => $item->author,
            'team' => $item->proposer,
            'media' => $item->uploader,
        };
        $link = match ($type) {
            'post' => route('manage.posts.edit', $item),
            'team' => route('manage.team.edit', $item),
            'media' => route('manage.media.index'),
        };
        $title = $item->title ?? $item->name ?? 'média';
        $author->notify(new ModerationResult(self::LABELS[$type], $title, $status, $reason, $link));
    }
}
