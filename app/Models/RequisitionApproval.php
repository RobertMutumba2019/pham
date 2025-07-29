<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequisitionApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'requisition_id',
        'approver_id',
        'workflow_id',
        'approval_level',
        'status',
        'comments',
        'approved_at',
        'rejected_at',
        'delegated_to',
        'delegated_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'delegated_at' => 'datetime',
    ];

    // Relationships
    public function requisition()
    {
        return $this->belongsTo(Requisition::class, 'requisition_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function workflow()
    {
        return $this->belongsTo(ApprovalWorkflow::class, 'workflow_id');
    }

    public function delegatedTo()
    {
        return $this->belongsTo(User::class, 'delegated_to');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeDelegated($query)
    {
        return $query->where('status', 'delegated');
    }

    public function scopeByLevel($query, $level)
    {
        return $query->where('approval_level', $level);
    }

    // Methods
    public function approve($comments = null)
    {
        $this->update([
            'status' => 'approved',
            'comments' => $comments,
            'approved_at' => now(),
        ]);
    }

    public function reject($comments = null)
    {
        $this->update([
            'status' => 'rejected',
            'comments' => $comments,
            'rejected_at' => now(),
        ]);
    }

    public function delegate($delegate_id, $comments = null)
    {
        $this->update([
            'status' => 'delegated',
            'delegated_to' => $delegate_id,
            'comments' => $comments,
            'delegated_at' => now(),
        ]);
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function isDelegated()
    {
        return $this->status === 'delegated';
    }
}
