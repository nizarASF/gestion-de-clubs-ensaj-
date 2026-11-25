<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamMember extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'position', 'academic_year', 'sort_order'];

    protected function casts(): array
    {
        return ['status' => Status::class, 'validated_at' => 'datetime'];
    }

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    public function proposer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'proposed_by');
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
