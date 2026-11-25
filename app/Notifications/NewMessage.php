<?php

namespace App\Notifications;

use App\Models\Conversation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/** Notification interne pour un nouveau message dans un fil. */
class NewMessage extends Notification
{
    use Queueable;

    public function __construct(
        public Conversation $conversation,
        public string $senderName,
        public string $excerpt
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message' => "Nouveau message de {$this->senderName} : " . \Illuminate\Support\Str::limit($this->excerpt, 100),
            'link' => route('conversations.show', $this->conversation),
            'conversation_id' => $this->conversation->id,
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
