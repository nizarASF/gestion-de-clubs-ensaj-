<?php

namespace App\Models;

use App\Enums\FieldOfStudy;
use App\Enums\Role;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use InvalidArgumentException;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /*
     * Les champs sensibles (role, club_id, status, validated_by) ne sont pas
     * fillables : ils sont toujours écrits par le code serveur.
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'field_of_study',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => Role::class,
            'status' => Status::class,
            'field_of_study' => FieldOfStudy::class,
            'validated_at' => 'datetime',
        ];
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'author_id');
    }

    public function uploadedMedia(): HasMany
    {
        return $this->hasMany(Media::class, 'uploaded_by');
    }

    public function proposedTeamMembers(): HasMany
    {
        return $this->hasMany(TeamMember::class, 'proposed_by');
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class, 'user_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'author_id');
    }

    public function isBde(): bool
    {
        return $this->role === Role::Bde;
    }

    public function isClubMember(): bool
    {
        return $this->role === Role::ClubMember;
    }

    /** Règle de cohérence unique : rôle et club sont toujours modifiés ensemble. */
    public function assignRole(Role $role, ?int $clubId = null): void
    {
        if ($role === Role::ClubMember && $clubId === null) {
            throw new InvalidArgumentException('Un membre de club doit être rattaché à un club.');
        }

        $this->role = $role;
        $this->club_id = $role === Role::ClubMember ? $clubId : null;
        $this->save();
    }

    /**
     * Un seul champ read_at : pour l'auteur du fil on compte les réponses du
     * club, pour un membre on compte les messages reçus par son club.
     */
    public function unreadMessagesCount(): int
    {
        $myThreads = $this->conversations()->pluck('id');
        $count = $myThreads->isEmpty() ? 0 : Message::whereIn('conversation_id', $myThreads)
            ->where('from_club', true)
            ->whereNull('read_at')
            ->count();

        if ($this->isClubMember() && $this->club_id) {
            $clubThreads = Conversation::where('club_id', $this->club_id)
                ->where('user_id', '!=', $this->id)
                ->pluck('id');
            if ($clubThreads->isNotEmpty()) {
                $count += Message::whereIn('conversation_id', $clubThreads)
                    ->where('from_club', false)
                    ->whereNull('read_at')
                    ->count();
            }
        }

        return $count;
    }

    public function scopeApproved(Builder $q): Builder
    {
        return $q->where('status', Status::Approved->value);
    }

    public function scopePending(Builder $q): Builder
    {
        return $q->where('status', Status::Pending->value);
    }
}
