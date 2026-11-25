<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    // club_id, author_id et status sont fixés par les contrôleurs, jamais par un formulaire.
    protected $fillable = ['title', 'body'];

    protected function casts(): array
    {
        return [
            'status' => Status::class,
            'published_at' => 'datetime',
            'validated_at' => 'datetime',
        ];
    }

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(PostImage::class)->orderBy('sort_order');
    }

    public function scopeApproved(Builder $q): Builder
    {
        // Les requêtes SQL attendent la valeur brute de l'enum, pas l'objet PHP.
        return $q->where('status', Status::Approved->value);
    }

    public function scopePending(Builder $q): Builder
    {
        return $q->where('status', Status::Pending->value);
    }
}
