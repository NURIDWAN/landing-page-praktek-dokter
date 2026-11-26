<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactMessage extends Model
{
    use MassPrunable;

    protected $fillable = [
        'name',
        'phone',
        'message',
        'ip_hash',
        'privacy_agreed_at',
        'read_at',
        'handled_at',
        'handled_by',
        'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'privacy_agreed_at' => 'datetime',
            'read_at' => 'datetime',
            'handled_at' => 'datetime',
        ];
    }

    public function handledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function scopeUnread(Builder $query): Builder
    {
        return $query->whereNull('read_at');
    }

    public function scopeUnhandled(Builder $query): Builder
    {
        return $query->whereNull('handled_at');
    }

    public function prunable(): Builder
    {
        return static::where('created_at', '<=', now()->subMonths(12));
    }
}
