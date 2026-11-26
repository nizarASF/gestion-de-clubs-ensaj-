<?php

namespace App\Http\Controllers;

use App\Enums\MessageType;
use App\Enums\Role;
use App\Http\Requests\MessageRequest;
use App\Models\Club;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Notifications\NewMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ConversationController extends Controller
{
    /** Formulaire de contact : un membre ne contacte pas son propre club. */
    public function contact(Club $club): View|RedirectResponse
    {
        abort_unless($club->is_active, 404);
        Gate::authorize('contact', $club);

        $existing = Conversation::where('club_id', $club->id)
            ->where('user_id', Auth::id())
            ->first();
        if ($existing) {
            return redirect()->route('conversations.show', $existing);
        }

        return view('conversations.contact', ['club' => $club]);
    }

    public function store(Club $club, MessageRequest $request): RedirectResponse
    {
        abort_unless($club->is_active, 404);
        Gate::authorize('contact', $club);
        $data = $request->validated();

        $conversation = Conversation::firstOrCreate([
            'club_id' => $club->id,
            'user_id' => Auth::id(),
        ], ['last_message_at' => now()]);

        $message = $this->addMessage($conversation, $data['body'], false, MessageType::from($data['type']));
        $this->notifyRecipients($conversation, $message);

        return redirect()->route('conversations.show', $conversation)
            ->with('status', 'Votre message a été envoyé au club.');
    }

    public function index(): View
    {
        $user = Auth::user();
        $sent = $user->conversations()
            ->with('club')
            ->withCount(['messages as unread_count' => fn ($q) => $q->where('from_club', true)->whereNull('read_at')])
            ->orderByDesc('last_message_at')
            ->get();

        $received = collect();
        if ($user->isClubMember() && $user->club_id) {
            $received = Conversation::where('club_id', $user->club_id)
                ->where('user_id', '!=', $user->id)
                ->with(['user', 'club'])
                ->withCount(['messages as unread_count' => fn ($q) => $q->where('from_club', false)->whereNull('read_at')])
                ->orderByDesc('last_message_at')
                ->get();
        }

        return view('conversations.index', compact('sent', 'received'));
    }

    public function show(Conversation $conversation): View
    {
        Gate::authorize('view', $conversation);

        // Marquer comme lus les messages destinés au lecteur (boîte partagée).
        $isInitiator = $conversation->user_id === Auth::id();
        $conversation->messages()
            ->whereNull('read_at')
            ->where('from_club', $isInitiator)
            ->update(['read_at' => now()]);

        $conversation->load(['club', 'user', 'messages.author']);

        return view('conversations.show', [
            'conversation' => $conversation,
            'isInitiator' => $isInitiator,
        ]);
    }

    public function storeMessage(Conversation $conversation, MessageRequest $request): JsonResponse|RedirectResponse
    {
        Gate::authorize('send', $conversation);
        $data = $request->validated();

        if ($conversation->user_id === Auth::id()) {
            // L'initiateur envoie une question ou une suggestion.
            $message = $this->addMessage($conversation, $data['body'], false, MessageType::from($data['type']));
        } else {
            // Un membre du club répond : pas de type, from_club = true.
            $message = $this->addMessage($conversation, $data['body'], true, null);
        }
        $this->notifyRecipients($conversation, $message);

        $payload = [
            'id' => $message->id,
            'author' => Auth::user()->full_name,
            'datetime' => $message->created_at->format('d/m/Y H:i'),
            'body' => $message->body,
            'type' => $message->type?->label(),
            'from_club' => $message->from_club,
            'mine' => true,
        ];

        if ($request->expectsJson()) {
            return response()->json($payload, 201);
        }

        return redirect()->route('conversations.show', $conversation)->with('status', 'Message envoyé.');
    }

    public function messages(Conversation $conversation, Request $request): JsonResponse
    {
        Gate::authorize('view', $conversation);
        $after = max(0, (int) $request->query('after', 0));
        $isInitiator = $conversation->user_id === Auth::id();

        $messages = $conversation->messages()
            ->with('author')
            ->where('id', '>', $after)
            ->orderBy('id')
            ->get()
            ->map(fn (Message $message) => [
                'id' => $message->id,
                'author' => $message->author->full_name,
                'datetime' => $message->created_at->format('d/m/Y H:i'),
                'body' => $message->body,
                'type' => $message->type?->label(),
                'from_club' => $message->from_club,
                'mine' => $message->author_id === Auth::id(),
                // Un membre ne considère non lues que les questions des étudiants,
                // jamais les réponses écrites par un autre membre du club.
                'unread' => $message->read_at === null
                    && $message->author_id !== Auth::id()
                    && $message->from_club === $isInitiator,
            ]);

        return response()->json($messages);
    }

    private function addMessage(Conversation $conversation, string $body, bool $fromClub, ?MessageType $type): Message
    {
        $message = $conversation->messages()->make(['body' => $body]);
        $message->author_id = Auth::id();
        $message->from_club = $fromClub;
        $message->type = $type;
        $message->save();
        $conversation->last_message_at = now();
        $conversation->save();

        return $message;
    }

    /** Une seule notification non lue par fil et par destinataire. */
    private function notifyRecipients(Conversation $conversation, Message $message): void
    {
        $sender = Auth::user();
        if ($conversation->user_id === $sender->id) {
            // Message d'un étudiant (ou du BDE) : tous les membres du club.
            $recipients = User::where('role', Role::ClubMember->value)
                ->where('club_id', $conversation->club_id)
                ->get();
        } else {
            // Réponse du club : uniquement l'initiateur du fil.
            $recipients = collect([$conversation->user]);
        }

        foreach ($recipients as $recipient) {
            if ($recipient->id === $sender->id) {
                continue;
            }
            $alreadyNotified = DatabaseNotification::where('notifiable_type', User::class)
                ->where('notifiable_id', $recipient->id)
                ->whereNull('read_at')
                ->where('data->conversation_id', $conversation->id)
                ->exists();
            if (! $alreadyNotified) {
                $recipient->notify(new NewMessage($conversation, $sender->full_name, $message->body));
            }
        }
    }
}
