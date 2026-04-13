<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class AssetLog extends Model
{
    public const ACTION_CREATED = 'created';
    public const ACTION_UPDATED = 'updated';
    public const ACTION_DELETED = 'deleted';
    public const ACTION_ASSIGNED = 'assigned';
    public const ACTION_RETURNED = 'returned';
    public const ACTION_STOCK_IN = 'stock_in';
    public const ACTION_STOCK_OUT = 'stock_out';

    protected $fillable = [
        'item_id',
        'user_id',
        'action',
        'notes',
    ];

    public static function log(int $itemId, string $action, ?string $notes = null): void
    {
        self::create([
            'item_id' => $itemId,
            'user_id' => Auth::id() ?? 1,
            'action' => $action,
            'notes' => $notes,
        ]);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}