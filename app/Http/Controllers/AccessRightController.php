<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccessRight;
use App\Models\UserRole;

class AccessRightController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = UserRole::orderBy('ur_name')->get();
        $pages = [
            'USERS', 'USER ROLES AND PRIVILEGES', 'PENDING APPROVALS', 'AUDIT TRAIL', 'SETTINGS',
            'APPROVED REQUISITIONS', 'REQUISITION', 'USER ROLE', 'STORES', 'APPROVAL LEVELS',
            'APPROVAL MATRIX', 'GROUPS', 'APPROVALGROUP', 'DESIGNATION', 'BRANCHES', 'DEPARTMENTS', 'ALL REQUISITIONS'
        ];
        $controls = ['ar_a' => 'Add', 'ar_v' => 'View', 'ar_e' => 'Edit', 'ar_p' => 'Print', 'ar_i' => 'Import', 'ar_x' => 'Export'];
        $accessRights = AccessRight::all()->groupBy(['ar_role_id', 'ar_page']);
        return view('access_rights.index', compact('roles', 'pages', 'controls', 'accessRights'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($roleId)
    {
        $role = UserRole::findOrFail($roleId);
        $pages = [
            'USERS', 'USER ROLES AND PRIVILEGES', 'PENDING APPROVALS', 'AUDIT TRAIL', 'SETTINGS',
            'APPROVED REQUISITIONS', 'REQUISITION', 'USER ROLE', 'STORES', 'APPROVAL LEVELS',
            'APPROVAL MATRIX', 'GROUPS', 'APPROVALGROUP', 'DESIGNATION', 'BRANCHES', 'DEPARTMENTS', 'ALL REQUISITIONS'
        ];
        $controls = ['ar_a' => 'Add', 'ar_v' => 'View', 'ar_e' => 'Edit', 'ar_p' => 'Print', 'ar_i' => 'Import', 'ar_x' => 'Export'];
        $accessRights = AccessRight::where('ar_role_id', $roleId)->get()->keyBy('ar_page');
        return view('access_rights.edit', compact('role', 'pages', 'controls', 'accessRights'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $roleId)
    {
        $pages = $request->input('pages', []);
        foreach ($pages as $page => $rights) {
            AccessRight::updateOrCreate(
                ['ar_role_id' => $roleId, 'ar_page' => $page],
                [
                    'ar_a' => isset($rights['ar_a']),
                    'ar_v' => isset($rights['ar_v']),
                    'ar_e' => isset($rights['ar_e']),
                    'ar_p' => isset($rights['ar_p']),
                    'ar_i' => isset($rights['ar_i']),
                    'ar_x' => isset($rights['ar_x']),
                ]
            );
        }
        return redirect()->route('access-rights.index')->with('status', 'Privileges updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
