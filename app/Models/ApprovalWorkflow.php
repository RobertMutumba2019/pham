<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalWorkflow extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'approval_levels',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'approval_levels' => 'array',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function requisitionApprovals()
    {
        return $this->hasMany(RequisitionApproval::class, 'workflow_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Methods
    public function getNextApprover($requisition_id, $current_level = 1)
    {
        $approval_levels = $this->approval_levels ?? [];
        
        if (!isset($approval_levels[$current_level])) {
            return null;
        }

        $level_config = $approval_levels[$current_level];
        $approver_type = $level_config['approver_type'] ?? 'user';
        
        switch ($approver_type) {
            case 'user':
                return User::find($level_config['approver_id']);
            case 'role':
                return User::where('user_role', $level_config['role_id'])->first();
            case 'department_head':
                // Get department head logic
                return null;
            default:
                return null;
        }
    }

    public function getApprovalLevels()
    {
        return $this->approval_levels ?? [];
    }
}
