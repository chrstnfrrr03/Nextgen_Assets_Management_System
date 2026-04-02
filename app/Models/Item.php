<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    /**
     * =============================
     * MASS ASSIGNABLE
     * =============================
     */
    protected $fillable = [
        'part_no',
        'brand',
        'part_name',
        'description',
        'category_id',
        'supplier_id',
        'asset_tag',
        'serial_number',
        'status',
        'assigned_to',
        'location',
        'purchase_date',
        'quantity'
    ];

    /**
     * =============================
     * DEFAULT VALUES
     * =============================
     */
    protected $attributes = [
        'status' => 'available',
        'quantity' => 1,
    ];

    /**
     * =============================
     * RELATIONSHIPS
     * =============================
     */

    // Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // LEGACY (keep for compatibility)
    public function user()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // MAIN RELATION (CRITICAL)
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    // ACTIVE ASSIGNMENTS ONLY
    public function activeAssignments()
    {
        return $this->hasMany(Assignment::class)
            ->whereNull('returned_at');
    }

    /**
     * =============================
     * INVENTORY LOGIC (CORE SYSTEM)
     * =============================
     */

    // TOTAL ASSIGNED (ACTIVE ONLY)
    public function totalAssigned()
    {
        return (int) $this->activeAssignments()->sum('quantity');
    }

    // AVAILABLE STOCK
    public function availableQuantity()
    {
        return max(0, (int) $this->quantity - $this->totalAssigned());
    }

    /**
     * =============================
     * STATUS (REAL SYSTEM LOGIC)
     * =============================
     */

    public function getComputedStatusAttribute()
    {
        $assigned = $this->totalAssigned();
        $available = $this->availableQuantity();

        if ($available <= 0) {
            return 'out_of_stock';
        }

        if ($assigned > 0) {
            return 'partially_assigned';
        }

        return 'available';
    }

    public function getStatusLabelAttribute()
    {
        return match ($this->computed_status) {
            'out_of_stock' => 'Out of Stock',
            'partially_assigned' => 'Partially Assigned',
            default => 'Available',
        };
    }
}