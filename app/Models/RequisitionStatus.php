<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequisitionStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'color',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Relationships
    public function requisitions()
    {
        return $this->hasMany(Requisition::class, 'req_status_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // Static methods for common statuses
    public static function draft()
    {
        return static::where('name', 'Draft')->first();
    }

    public static function pending()
    {
        return static::where('name', 'Pending')->first();
    }

    public static function approved()
    {
        return static::where('name', 'Approved')->first();
    }

    public static function rejected()
    {
        return static::where('name', 'Rejected')->first();
    }

    public static function completed()
    {
        return static::where('name', 'Completed')->first();
    }
}
