<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requisition;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;

class ReportController extends Controller
{
    // Serial Number Finder Report
    public function serialNumberFinder(Request $request)
    {
        // Example: search requisitions by serial/number
        $search = $request->input('search');
        $requisitions = Requisition::query();
        if ($search) {
            $requisitions->where('req_number', 'like', "%$search%")
                ->orWhere('req_title', 'like', "%$search%")
                ->orWhere('req_ref', 'like', "%$search%")
                ->orWhere('req_added_by', 'like', "%$search%")
                ->orWhere('req_date_added', 'like', "%$search%")
                ;
        }
        $requisitions = $requisitions->paginate(20);
        return view('reports.serial_number_finder', compact('requisitions', 'search'));
    }

    // Summary List Report
    public function summaryList(Request $request)
    {
        // Example: summary of requisitions
        $summary = Requisition::select(DB::raw('count(*) as total, req_status'))
            ->groupBy('req_status')
            ->get();
        return view('reports.summary_list', compact('summary'));
    }

    // Territory Vehicle Request & Return Report
    public function territoryVehicleRequestAndReturn(Request $request)
    {
        // Placeholder: implement actual logic based on your schema
        $vehicleRequests = DB::table('vehicle_requests')->paginate(20);
        $vehicleReturns = DB::table('vehicle_returns')->paginate(20);
        return view('reports.territory_vehicle_request_and_return', compact('vehicleRequests', 'vehicleReturns'));
    }

    // User Export (Excel)
    public function exportUsers(Request $request)
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }
} 