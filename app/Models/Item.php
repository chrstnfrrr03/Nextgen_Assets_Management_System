<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Item extends Model
{
    protected $fillable = [
        'name',
        'sku',
        'category_id',
        'supplier_id',
        'department_id',
        'asset_tag',
        'serial_number',
        'quantity',
        'status',
        'location',
        'purchase_date',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'quantity' => 'integer',
    ];

    public const STATUS_AVAILABLE = 'available';
    public const STATUS_ASSIGNED = 'assigned';
    public const STATUS_MAINTENANCE = 'maintenance';
    public const STATUS_RETIRED = 'retired';

    /* ================= ReactJs ================= */
protected $appends = ['is_low_stock'];

protected $hidden = [
    'created_at',
    'updated_at',
];

public function getIsLowStockAttribute(): bool
{
    return $this->isLowStock();
}




    /* ================= RELATIONSHIPS ================= */

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    public function activeAssignment(): HasOne
    {
        return $this->hasOne(Assignment::class)
            ->whereNull('returned_at')
            ->latestOfMany('assigned_at');
    }

    public function assetLogs(): HasMany
    {
        return $this->hasMany(AssetLog::class);
    }

    /* ================= STATUS HELPERS ================= */

    public function isAvailable(): bool
    {
        return $this->status === self::STATUS_AVAILABLE;
    }

    public function isAssigned(): bool
    {
        return $this->status === self::STATUS_ASSIGNED;
    }

    public function isMaintenance(): bool
    {
        return $this->status === self::STATUS_MAINTENANCE;
    }

    public function isRetired(): bool
    {
        return $this->status === self::STATUS_RETIRED;
    }

    public function hasActiveAssignment(): bool
    {
        return $this->activeAssignment()->exists();
    }

    public function isLowStock(int $threshold = 3): bool
    {
        return $this->quantity <= $threshold;
    }

    /* ================= AUTOMATION ================= */

    public function syncAutomatedStatus(): void
{
    if ($this->status === self::STATUS_RETIRED) {
        return;
    }

    if ($this->quantity < 0) {
        $this->quantity = 0;
    }

    $newStatus = self::STATUS_AVAILABLE;

    if ($this->quantity <= 0) {
        $newStatus = self::STATUS_MAINTENANCE;
    } elseif ($this->activeAssignment()->exists()) {
        $newStatus = self::STATUS_ASSIGNED;
    }

    if ($this->status !== $newStatus) {
        $this->status = $newStatus;
        $this->quantity = max(0, $this->quantity);
        $this->save();
    }
}
}