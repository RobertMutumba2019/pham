<?php

namespace App\Services;

use App\Models\Requisition;
use App\Models\RequisitionItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Notifications\RequisitionSubmitted;

class RequisitionService
{
    /**
     * Create a new requisition (draft or forward)
     *
     * @param User $user
     * @param array $data
     * @return array
     */
    public function createRequisition(User $user, array $data)
    {
        DB::beginTransaction();
        try {
            $ref = $user->id . time();
            $status = $data['action'] === 'draft' ? -1 : 1;
            $requisitionNumber = $data['action'] === 'forward' ? $this->generateRequisitionNumber() : null;

            $requisition = Requisition::create([
                'req_number' => $requisitionNumber,
                'req_title' => $data['req_title'],
                'req_division' => $data['req_division'],
                'req_ref' => $ref,
                'req_added_by' => $user->id,
                'req_date_added' => now(),
                'req_status' => $status,
                // Add HOD and delegate logic as needed
            ]);

            foreach ($data['items'] as $item) {
                RequisitionItem::create([
                    'ri_code' => $item['ri_code'],
                    'ri_quantity' => $item['ri_quantity'],
                    'ri_uom' => $item['ri_uom'],
                    'ri_description' => $item['ri_description'],
                    'ri_ref' => $ref,
                ]);
            }

            // Send notification if forwarded
            if ($data['action'] === 'forward') {
                // Find HOD or next approver (implement your own logic)
                $hod = $this->findHod($user);
                if ($hod) {
                    Notification::send($hod, new RequisitionSubmitted($requisition));
                }
            }

            DB::commit();
            return ['requisition' => $requisition];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Generate a new requisition number (implement logic as needed)
     */
    protected function generateRequisitionNumber()
    {
        $dm = date('y') . date('m');
        $suffix = "RQN" . $dm;
        $last = Requisition::whereNotNull('req_number')
            ->where('req_number', 'like', $suffix . '%')
            ->orderByDesc('id')
            ->first();
        $next = 1;
        if ($last && preg_match('/RQN\d{4}(\d+)/', $last->req_number, $matches)) {
            $next = intval($matches[1]) + 1;
        }
        return $suffix . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Find the HOD for the user (implement your own logic)
     */
    protected function findHod(User $user)
    {
        // Find the user's department
        $departmentId = $user->user_department_id ?? null;
        if (!$departmentId) {
            return null;
        }
        // Find the HOD record for this department
        $hodRecord = \App\Models\Hod::where('hod_dept_id', $departmentId)->latest()->first();
        if ($hodRecord && $hodRecord->user) {
            return $hodRecord->user;
        }
        return null;
    }
} 