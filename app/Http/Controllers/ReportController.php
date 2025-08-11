<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requisition;
use App\Models\User;
use App\Models\Department;
use App\Models\Branch;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\UsersExport;
use App\Exports\RequisitionsExport;
use Carbon\Carbon;

class ReportController extends Controller
{
    // Serial Number Finder Report - Enhanced with multiple table search
    public function serialNumberFinder(Request $request)
    {
        $search = $request->input('search');
        $searchType = $request->input('search_type', 'c'); // c=contains, e=ends, s=starts, p=exact
        $results = [];
        
        if ($search) {
            $searchCondition = $this->getSearchCondition($searchType, $search);
            
            // Search in requisitions
            $requisitions = DB::table('requisitions as r')
                ->leftJoin('users as u', 'r.req_added_by', '=', 'u.id')
                ->leftJoin('departments as d', 'u.user_department_id', '=', 'd.id')
                ->select('r.req_id as id', 'r.req_number as number', 'r.req_date_added as date_added', 
                        'u.user_name', 'u.user_surname', 'd.dept_name', 
                        DB::raw("'requisition' as type"), DB::raw("'requisition/view-requisition/' as link_prefix"))
                ->whereRaw("r.req_number $searchCondition")
                ->orWhereRaw("r.req_title $searchCondition")
                ->get();
            
            $results = $requisitions->toArray();
        }
        
        return view('reports.serial_number_finder', compact('results', 'search', 'searchType'));
    }
    
    private function getSearchCondition($type, $search)
    {
        switch ($type) {
            case 'c': return "LIKE '%$search%'";
            case 'e': return "LIKE '%$search'";
            case 's': return "LIKE '$search%'";
            case 'p': return "= '$search'";
            default: return "LIKE '%$search%'";
        }
    }

    // Summary List Report - Enhanced with filters and export
    public function summaryList(Request $request)
    {
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $department = $request->input('department');
        $status = $request->input('status');
        $export = $request->input('export');
        
        $query = DB::table('requisitions as r')
            ->leftJoin('users as u', 'r.req_added_by', '=', 'u.id')
            ->leftJoin('departments as d', 'u.user_department_id', '=', 'd.id')
            ->leftJoin('requisition_statuses as rs', 'r.req_status_id', '=', 'rs.id')
            ->select('r.*', 'u.user_name', 'u.user_surname', 'd.dept_name', 'rs.status_name');
        
        // Apply filters
        if ($dateFrom) {
            $query->where('r.req_date_added', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->where('r.req_date_added', '<=', $dateTo);
        }
        if ($department) {
            $query->where('d.id', $department);
        }
        if ($status) {
            $query->where('r.req_status_id', $status);
        }
        
        $requisitions = $query->paginate(50);
        $departments = Department::all();
        
        // Handle export
        if ($export === 'excel') {
            return Excel::download(new RequisitionsExport($query->get()), 'summary_list.xlsx');
        }
        if ($export === 'pdf') {
            $data = $query->get();
            $pdf = Pdf::loadView('reports.summary_list_pdf', compact('data'));
            return $pdf->download('summary_list.pdf');
        }
        
        return view('reports.summary_list', compact('requisitions', 'departments', 'dateFrom', 'dateTo', 'department', 'status'));
    }

    // Territory Vehicle Request & Return Report
    public function territoryVehicleRequestAndReturn(Request $request)
    {
        // Get vehicle requests with user information
        $vehicleRequests = DB::table('vehicle_requests')
            ->leftJoin('users', 'vehicle_requests.requested_by', '=', 'users.id')
            ->select('vehicle_requests.*', 'users.user_name', 'users.user_surname')
            ->paginate(20);
            
        // Get vehicle returns with user information
        $vehicleReturns = DB::table('vehicle_returns')
            ->leftJoin('users as returned_by_user', 'vehicle_returns.returned_by', '=', 'returned_by_user.id')
            ->leftJoin('users as received_by_user', 'vehicle_returns.received_by', '=', 'received_by_user.id')
            ->select('vehicle_returns.*', 
                    'returned_by_user.user_name as returned_by_name', 
                    'returned_by_user.user_surname as returned_by_surname',
                    'received_by_user.user_name as received_by_name', 
                    'received_by_user.user_surname as received_by_surname')
            ->paginate(20);
            
        return view('reports.territory_vehicle_request_and_return', compact('vehicleRequests', 'vehicleReturns'));
    }

    // User Export (Excel/PDF)
    public function exportUsers(Request $request)
    {
        $format = $request->input('format', 'excel');
        
        if ($format === 'pdf') {
            $users = User::with('department')->get();
            $pdf = Pdf::loadView('reports.users_pdf', compact('users'));
            return $pdf->download('users.pdf');
        }
        
        return Excel::download(new UsersExport, 'users.xlsx');
    }
    
    // Advanced Reporting Dashboard
    public function dashboard(Request $request)
    {
        $stats = [
            'total_requisitions' => Requisition::count(),
            'pending_approvals' => Requisition::where('req_status_id', 1)->count(),
            'approved_requisitions' => Requisition::where('req_status_id', 2)->count(),
            'rejected_requisitions' => Requisition::where('req_status_id', 3)->count(),
            'total_users' => User::count(),
            'total_departments' => Department::count(),
        ];
        
        // Monthly requisition trends
        $monthlyTrends = DB::table('requisitions')
            ->select(DB::raw('YEAR(req_date_added) as year'), 
                    DB::raw('MONTH(req_date_added) as month'), 
                    DB::raw('COUNT(*) as count'))
            ->where('req_date_added', '>=', Carbon::now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();
        
        return view('reports.dashboard', compact('stats', 'monthlyTrends'));
    }
    
    // Department-wise Report
    public function departmentReport(Request $request)
    {
        $departmentId = $request->input('department_id');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        
        $query = DB::table('requisitions as r')
            ->leftJoin('users as u', 'r.req_added_by', '=', 'u.id')
            ->leftJoin('departments as d', 'u.user_department_id', '=', 'd.id')
            ->select('d.dept_name', DB::raw('COUNT(*) as total_requisitions'),
                    DB::raw('SUM(CASE WHEN r.req_status_id = 2 THEN 1 ELSE 0 END) as approved'),
                    DB::raw('SUM(CASE WHEN r.req_status_id = 3 THEN 1 ELSE 0 END) as rejected'),
                    DB::raw('SUM(CASE WHEN r.req_status_id = 1 THEN 1 ELSE 0 END) as pending'))
            ->groupBy('d.id', 'd.dept_name');
        
        if ($departmentId) {
            $query->where('d.id', $departmentId);
        }
        if ($dateFrom) {
            $query->where('r.req_date_added', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->where('r.req_date_added', '<=', $dateTo);
        }
        
        $departmentStats = $query->get();
        $departments = Department::all();
        
        return view('reports.department_report', compact('departmentStats', 'departments', 'departmentId', 'dateFrom', 'dateTo'));
    }
} 