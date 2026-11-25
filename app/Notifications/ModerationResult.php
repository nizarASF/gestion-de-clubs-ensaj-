<?php

namespace App\Notifications;

use App\Enums\Status;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/** Notification interne envoyée à l'auteur après validation ou refus. */
class ModerationResult extends Notification
{
    use Queueable;

    public function __construct(
        public string $subject,
        public string $title,
        public Status $status,
        public ?string $reason,
        public string $link
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $verb = $this->status === Status::Approved ? 'validé' : 'refusé';
        $message = "Votre {$this->subject} « {$this->title} » a été {$verb}.";
        if ($this->status === Status::Rejected && $this->reason) {
            $message .= ' Motif : ' . $this->reason;
        }

        return [
            'message' => $message,
            'link' => $this->link,
            'status' => $this->status->value,
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
