<?php

namespace App\Services;

use App\Models\Requisition;
use App\Models\RequisitionApproval;
use App\Models\ApprovalWorkflow;
use App\Models\User;
use App\Models\RequisitionStatus;
use Illuminate\Support\Facades\DB;

class RequisitionWorkflowService
{
    /**
     * Submit a requisition for approval
     */
    public function submitForApproval(Requisition $requisition, $workflow_id = null)
    {
        DB::transaction(function () use ($requisition, $workflow_id) {
            // Update requisition status to pending
            $pendingStatus = RequisitionStatus::pending();
            $requisition->update(['req_status_id' => $pendingStatus->id]);

            // Get or create workflow
            $workflow = $workflow_id ? ApprovalWorkflow::find($workflow_id) : $this->getDefaultWorkflow();

            if (!$workflow) {
                throw new \Exception('No approval workflow found');
            }

            // Create approval records for each level
            $this->createApprovalRecords($requisition, $workflow);
        });
    }

    /**
     * Approve a requisition
     */
    public function approveRequisition(Requisition $requisition, $approver_id, $comments = null)
    {
        DB::transaction(function () use ($requisition, $approver_id, $comments) {
            $approval = RequisitionApproval::where('requisition_id', $requisition->id)
                ->where('approver_id', $approver_id)
                ->where('status', 'pending')
                ->first();

            if (!$approval) {
                throw new \Exception('No pending approval found for this user');
            }

            $approval->approve($comments);

            // Check if this is the final approval
            if ($this->isFinalApproval($requisition)) {
                $approvedStatus = RequisitionStatus::approved();
                $requisition->update(['req_status_id' => $approvedStatus->id]);
            } else {
                // Move to next approval level
                $this->moveToNextApprovalLevel($requisition);
            }
        });
    }

    /**
     * Reject a requisition
     */
    public function rejectRequisition(Requisition $requisition, $rejector_id, $comments = null)
    {
        DB::transaction(function () use ($requisition, $rejector_id, $comments) {
            $approval = RequisitionApproval::where('requisition_id', $requisition->id)
                ->where('approver_id', $rejector_id)
                ->where('status', 'pending')
                ->first();

            if (!$approval) {
                throw new \Exception('No pending approval found for this user');
            }

            $approval->reject($comments);

            // Update requisition status to rejected
            $rejectedStatus = RequisitionStatus::rejected();
            $requisition->update(['req_status_id' => $rejectedStatus->id]);
        });
    }

    /**
     * Delegate approval to another user
     */
    public function delegateApproval(Requisition $requisition, $approver_id, $delegate_id, $comments = null)
    {
        DB::transaction(function () use ($requisition, $approver_id, $delegate_id, $comments) {
            $approval = RequisitionApproval::where('requisition_id', $requisition->id)
                ->where('approver_id', $approver_id)
                ->where('status', 'pending')
                ->first();

            if (!$approval) {
                throw new \Exception('No pending approval found for this user');
            }

            $approval->delegate($delegate_id, $comments);

            // Create new approval record for delegate
            RequisitionApproval::create([
                'requisition_id' => $requisition->id,
                'approver_id' => $delegate_id,
                'workflow_id' => $approval->workflow_id,
                'approval_level' => $approval->approval_level,
                'status' => 'pending',
            ]);
        });
    }

    /**
     * Get the next approver for a requisition
     */
    public function getNextApprover(Requisition $requisition)
    {
        $pendingApproval = RequisitionApproval::where('requisition_id', $requisition->id)
            ->where('status', 'pending')
            ->orderBy('approval_level')
            ->first();

        return $pendingApproval ? $pendingApproval->approver : null;
    }

    /**
     * Get approval history for a requisition
     */
    public function getApprovalHistory(Requisition $requisition)
    {
        return RequisitionApproval::where('requisition_id', $requisition->id)
            ->with(['approver', 'workflow', 'delegatedTo'])
            ->orderBy('approval_level')
            ->orderBy('created_at')
            ->get();
    }

    /**
     * Check if requisition is fully approved
     */
    public function isFullyApproved(Requisition $requisition)
    {
        $pendingApprovals = RequisitionApproval::where('requisition_id', $requisition->id)
            ->where('status', 'pending')
            ->count();

        return $pendingApprovals === 0;
    }

    /**
     * Get pending approvals for a user
     */
    public function getPendingApprovalsForUser($user_id)
    {
        return RequisitionApproval::where('approver_id', $user_id)
            ->where('status', 'pending')
            ->with(['requisition', 'workflow'])
            ->get();
    }

    /**
     * Create approval records for a requisition
     */
    private function createApprovalRecords(Requisition $requisition, ApprovalWorkflow $workflow)
    {
        $approval_levels = $workflow->getApprovalLevels();

        foreach ($approval_levels as $level => $config) {
            $approver = $this->getApproverForLevel($config);
            
            if ($approver) {
                RequisitionApproval::create([
                    'requisition_id' => $requisition->id,
                    'approver_id' => $approver->id,
                    'workflow_id' => $workflow->id,
                    'approval_level' => $level,
                    'status' => 'pending',
                ]);
            }
        }
    }

    /**
     * Get approver for a specific level
     */
    private function getApproverForLevel($config)
    {
        $approver_type = $config['approver_type'] ?? 'user';
        
        switch ($approver_type) {
            case 'user':
                return User::find($config['approver_id']);
            case 'role':
                return User::where('user_role', $config['role_id'])->first();
            case 'department_head':
                // Implement department head logic
                return null;
            default:
                return null;
        }
    }

    /**
     * Get default workflow
     */
    private function getDefaultWorkflow()
    {
        return ApprovalWorkflow::active()->byType('requisition')->first();
    }

    /**
     * Check if this is the final approval
     */
    private function isFinalApproval(Requisition $requisition)
    {
        $pendingApprovals = RequisitionApproval::where('requisition_id', $requisition->id)
            ->where('status', 'pending')
            ->count();

        return $pendingApprovals <= 1;
    }

    /**
     * Move to next approval level
     */
    private function moveToNextApprovalLevel(Requisition $requisition)
    {
        // This would trigger notifications to the next approver
        // Implementation depends on notification system
    }
} 