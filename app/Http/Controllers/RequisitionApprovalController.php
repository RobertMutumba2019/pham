<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requisition;
use App\Models\RequisitionApproval;
use App\Models\RequisitionStatus;
use App\Services\RequisitionWorkflowService;
use Illuminate\Support\Facades\Auth;

class RequisitionApprovalController extends Controller
{
    protected $workflowService;

    public function __construct(RequisitionWorkflowService $workflowService)
    {
        $this->workflowService = $workflowService;
    }

    /**
     * Display a listing of pending approvals for the current user
     */
    public function index(Request $request)
    {
        $user_id = Auth::id();
        $pendingApprovals = $this->workflowService->getPendingApprovalsForUser($user_id);
        
        return view('requisition-approvals.index', compact('pendingApprovals'));
    }

    /**
     * Show the form for creating a new approval
     */
    public function create()
    {
        // This would typically be handled by the workflow service
        return redirect()->route('requisition-approvals.index');
    }

    /**
     * Store a newly created approval
     */
    public function store(Request $request)
    {
        // This would typically be handled by the workflow service
        return redirect()->route('requisition-approvals.index');
    }

    /**
     * Display the specified approval
     */
    public function show(RequisitionApproval $requisitionApproval)
    {
        $approval = $requisitionApproval->load(['requisition', 'approver', 'workflow']);
        return view('requisition-approvals.show', compact('approval'));
    }

    /**
     * Show the form for editing the specified approval
     */
    public function edit(RequisitionApproval $requisitionApproval)
    {
        return view('requisition-approvals.edit', compact('requisitionApproval'));
    }

    /**
     * Update the specified approval
     */
    public function update(Request $request, RequisitionApproval $requisitionApproval)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,delegate',
            'comments' => 'nullable|string|max:1000',
            'delegate_id' => 'required_if:action,delegate|exists:users,id',
        ]);

        $user_id = Auth::id();
        $comments = $request->input('comments');

        try {
            switch ($request->action) {
                case 'approve':
                    $this->workflowService->approveRequisition(
                        $requisitionApproval->requisition, 
                        $user_id, 
                        $comments
                    );
                    $message = 'Requisition approved successfully';
                    break;

                case 'reject':
                    $this->workflowService->rejectRequisition(
                        $requisitionApproval->requisition, 
                        $user_id, 
                        $comments
                    );
                    $message = 'Requisition rejected successfully';
                    break;

                case 'delegate':
                    $this->workflowService->delegateApproval(
                        $requisitionApproval->requisition, 
                        $user_id, 
                        $request->delegate_id, 
                        $comments
                    );
                    $message = 'Approval delegated successfully';
                    break;
            }

            return redirect()->route('requisition-approvals.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified approval
     */
    public function destroy(RequisitionApproval $requisitionApproval)
    {
        // Typically approvals shouldn't be deleted, just updated
        return redirect()->route('requisition-approvals.index');
    }

    /**
     * Approve a requisition
     */
    public function approve(Request $request, RequisitionApproval $requisitionApproval)
    {
        $request->validate([
            'comments' => 'nullable|string|max:1000',
        ]);

        try {
            $this->workflowService->approveRequisition(
                $requisitionApproval->requisition, 
                Auth::id(), 
                $request->comments
            );

            return redirect()->route('requisition-approvals.index')
                ->with('success', 'Requisition approved successfully');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Reject a requisition
     */
    public function reject(Request $request, RequisitionApproval $requisitionApproval)
    {
        $request->validate([
            'comments' => 'nullable|string|max:1000',
        ]);

        try {
            $this->workflowService->rejectRequisition(
                $requisitionApproval->requisition, 
                Auth::id(), 
                $request->comments
            );

            return redirect()->route('requisition-approvals.index')
                ->with('success', 'Requisition rejected successfully');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Delegate approval to another user
     */
    public function delegate(Request $request, RequisitionApproval $requisitionApproval)
    {
        $request->validate([
            'delegate_id' => 'required|exists:users,id',
            'comments' => 'nullable|string|max:1000',
        ]);

        try {
            $this->workflowService->delegateApproval(
                $requisitionApproval->requisition, 
                Auth::id(), 
                $request->delegate_id, 
                $request->comments
            );

            return redirect()->route('requisition-approvals.index')
                ->with('success', 'Approval delegated successfully');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Get approval history for a requisition
     */
    public function history(Requisition $requisition)
    {
        $approvalHistory = $this->workflowService->getApprovalHistory($requisition);
        return view('requisition-approvals.history', compact('requisition', 'approvalHistory'));
    }
}
