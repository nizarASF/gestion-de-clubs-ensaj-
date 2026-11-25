<?php

namespace App\Models;

use App\Enums\MediaType;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Media extends Model
{
    use HasFactory;

    // Seul le titre vient directement du formulaire.
    protected $fillable = ['title'];

    protected function casts(): array
    {
        return [
            'type' => MediaType::class,
            'status' => Status::class,
            'validated_at' => 'datetime',
        ];
    }

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
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
