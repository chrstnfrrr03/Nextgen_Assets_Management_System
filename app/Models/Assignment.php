<?php
// app/Models/Assignment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = [
        'item_id',
        'user_id',
        'department_id',
        'assigned_at',
        'returned_at'
    ];
       
    protected $casts = [
        'assigned_at' => 'datetime',
        'returned_at' => 'datetime',
    ];
    

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedDepartment()
    {
        return $this->belongsTo(Department::class, 'assigned_department_id');
    }
}