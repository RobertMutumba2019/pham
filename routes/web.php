<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RequisitionController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\RequisitionItemController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\AccessRightController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\ApprovalMatrixController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\DelegationController;
use App\Http\Controllers\ApprovalGroupController;
use App\Http\Controllers\AreaOfficeController;
use App\Http\Controllers\HodController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\RejectedCopyMasterController;
use App\Http\Controllers\TrailOfUserController;
use App\Http\Controllers\ApprovalOrderController;
use App\Http\Controllers\ReportController;


Route::get('/', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login'])->name('users.login');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::resource('users', UserController::class);
Route::resource('departments', DepartmentController::class);
Route::resource('requisitions', RequisitionController::class);
Route::resource('requisition-items', RequisitionItemController::class);
Route::resource('user-roles', UserRoleController::class);
Route::resource('access-rights', AccessRightController::class);
Route::resource('items', ItemController::class);
Route::resource('branches', BranchController::class);
Route::resource('designations', DesignationController::class);
Route::resource('districts', DistrictController::class);
Route::resource('groups', GroupController::class);
Route::resource('approval-matrices', ApprovalMatrixController::class);
Route::resource('approval-groups', ApprovalGroupController::class);
Route::resource('sections', SectionController::class);
Route::resource('delegations', DelegationController::class);
Route::resource('area-offices', AreaOfficeController::class);
Route::resource('hods', HodController::class);
Route::resource('attachments', AttachmentController::class);
Route::resource('comments', CommentController::class);
Route::resource('rejected-copy-masters', RejectedCopyMasterController::class);
Route::resource('trail-of-users', TrailOfUserController::class);
Route::resource('approval-orders', ApprovalOrderController::class);

// Report routes
Route::get('/reports/serial-number-finder', [ReportController::class, 'serialNumberFinder'])->name('reports.serial_number_finder');
Route::get('/reports/summary-list', [ReportController::class, 'summaryList'])->name('reports.summary_list');
Route::get('/reports/territory-vehicle-request-and-return', [ReportController::class, 'territoryVehicleRequestAndReturn'])->name('reports.territory_vehicle_request_and_return');
Route::get('/reports/export-users', [ReportController::class, 'exportUsers'])->name('reports.export_users');

// Forgot password and reset routes
Route::get('forgot-password', [UserController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('forgot-password', [UserController::class, 'sendResetLink'])->name('password.email');
Route::get('reset-password/{token}', [UserController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [UserController::class, 'resetPassword'])->name('password.update');

Route::get('/change-password', [UserController::class, 'showChangePasswordForm'])->name('users.change-password.form');
Route::post('/change-password', [UserController::class, 'changePassword'])->name('users.change-password.update');
