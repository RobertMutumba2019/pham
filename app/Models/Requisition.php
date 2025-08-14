<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requisition extends Model
{
    use HasFactory;

    protected $fillable = [
        'req_number',
        'req_title',
        'req_description',
        'req_priority',
        'req_division',
        'req_ref',
        'req_date_needed',
        'req_justification',
        'req_added_by',
        'req_date_added',
        'req_status',
        'req_hod_id',
        'req_status_id', // New field for workflow status
    ];

    protected $casts = [
        'req_date_added' => 'datetime',
    ];

    // Relationships
    public function items()
    {
        return $this->hasMany(RequisitionItem::class, 'requisition_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'req_added_by');
    }

    public function status()
    {
        return $this->belongsTo(RequisitionStatus::class, 'req_status_id');
    }

    public function approvals()
    {
        return $this->hasMany(RequisitionApproval::class, 'requisition_id');
    }

    // public function attachments()
    // {
    //     return $this->morphMany(Attachment::class, 'attachable');
    // }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->whereHas('status', function($q) use ($status) {
            $q->where('name', $status);
        });
    }

    public function scopePending($query)
    {
        return $this->scopeByStatus($query, 'Pending');
    }

    public function scopeApproved($query)
    {
        return $this->scopeByStatus($query, 'Approved');
    }

    public function scopeRejected($query)
    {
        return $this->scopeByStatus($query, 'Rejected');
    }

    public function scopeDraft($query)
    {
        return $this->scopeByStatus($query, 'Draft');
    }

    // Methods
    public function isPending()
    {
        return $this->status && $this->status->name === 'Pending';
    }

    public function isApproved()
    {
        return $this->status && $this->status->name === 'Approved';
    }

    public function isRejected()
    {
        return $this->status && $this->status->name === 'Rejected';
    }

    public function isDraft()
    {
        return $this->status && $this->status->name === 'Draft';
    }

    public function getStatusColor()
    {
        return $this->status ? $this->status->color : '#000000';
    }

    public function getStatusName()
    {
        return $this->status ? $this->status->name : 'Unknown';
    }
}
