<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Controllers
|--------------------------------------------------------------------------
*/

// Base
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\SalesmanDashboardController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ReportController;

// Admin
use App\Http\Controllers\Admin\AttendanceReportController;
use App\Http\Controllers\Admin\SalesmanController as AdminSalesmanController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\OldCustomerController as AdminOldCustomerController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\PromotionController;

// Salesman
use App\Http\Controllers\Salesman\CustomerController as SalesmanCustomerController;
use App\Http\Controllers\Salesman\OldCustomerController as SalesmanOldCustomerController;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| DASHBOARD REDIRECT (ROLE BASED)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    $role = auth()->user()->role;

    return match ($role) {
        'admin'    => redirect()->route('admin.dashboard'),
        'salesman' => redirect()->route('salesman.dashboard'),
        'it',
        'account',
        'store',
        'office_boy' => redirect()->route('staff.attendance.index'),
        default    => abort(403),
    };
})->middleware('auth')->name('dashboard');


/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Reports
        Route::get('/reports', [ReportController::class, 'adminReport'])->name('reports.index');
        Route::get('/reports/{id}', [ReportController::class, 'show'])->name('reports.show');

        // Customers
        Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/{id}', [AdminCustomerController::class, 'show'])->name('customers.show');
        Route::get('/customers/export/all', [AdminCustomerController::class, 'exportAll'])->name('customers.export.all');
        Route::get('/customers/export/{id}', [AdminCustomerController::class, 'exportSingle'])->name('customers.export.single');
        Route::post('/customers/export/bulk', [AdminCustomerController::class, 'exportBulk'])->name('customers.export.bulk');

        // Salesmen CRUD
        Route::resource('salesmen', AdminSalesmanController::class)->except(['show']);

        /*
        |--------------------------------------------------------------------------
        | ADMIN ATTENDANCE
        |--------------------------------------------------------------------------
        */
        Route::prefix('attendance')->name('attendance.')->group(function () {

            // Index (Month + Staff filter)
            Route::get('/', [AttendanceReportController::class, 'index'])->name('index');

            // View single staff (MONTH FILTER PASSED)
            Route::get('/staff/{id}', [AttendanceReportController::class, 'staffReport'])->name('staff');

            // Mark leave
            Route::post('/staff/{id}/leave', [AttendanceReportController::class, 'markLeave'])->name('leave');

            // Update attendance (admin override)
            Route::post('/update/{attendanceId}', [AttendanceReportController::class, 'updateAttendance'])->name('update');

            // Export Attendance
            Route::get('/export/excel', [AttendanceReportController::class, 'exportExcel'])->name('export.excel');
            Route::get('/export/pdf', [AttendanceReportController::class, 'exportPdf'])->name('export.pdf');
        });
// Promotions (Emails)
Route::post('/promotions/send', [PromotionController::class, 'send'])
    ->name('promotions.send');






        // Staff Management
        Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
        Route::get('/staff/create', [StaffController::class, 'create'])->name('staff.create');
        Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
        Route::get('/staff/{staff}/edit', [StaffController::class, 'edit'])->name('staff.edit');
        Route::put('/staff/{staff}', [StaffController::class, 'update'])->name('staff.update');

        // Old Customers
        Route::get('/old-customers', [AdminOldCustomerController::class, 'index'])->name('old-customers.index');
    });

/*
|--------------------------------------------------------------------------
| SALESMAN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:salesman'])
    ->prefix('salesman')
    ->name('salesman.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [SalesmanDashboardController::class, 'index'])->name('dashboard');

        // Customers
        Route::resource('customers', SalesmanCustomerController::class)->only(['index', 'create', 'store', 'show']);

        // Visits
        Route::resource('visits', VisitController::class)->only(['index', 'create', 'store', 'show']);
        Route::post('/visits/{id}/complete', [VisitController::class, 'complete'])->name('visits.complete');

        // Attendance
        Route::prefix('attendance')->name('attendance.')->group(function () {
            Route::get('/', [AttendanceController::class, 'index'])->name('index');
            Route::post('/clock-in', [AttendanceController::class, 'clockIn'])->name('clockin');
            Route::post('/clock-out', [AttendanceController::class, 'clockOut'])->name('clockout');
            Route::get('/history', [AttendanceController::class, 'history'])->name('history');
        });

        // Reports
        Route::get('/reports', [ReportController::class, 'salesmanReport'])->name('reports.index');

        // Old Customers
        Route::get('/old-customers', [SalesmanOldCustomerController::class, 'index'])->name('old-customers.index');
        Route::get('/old-customers/import', [SalesmanOldCustomerController::class, 'importForm'])->name('old-customers.import.form');
        Route::post('/old-customers/import', [SalesmanOldCustomerController::class, 'import'])->name('old-customers.import');
    });

/*
|--------------------------------------------------------------------------
| IT, ACCOUNTS, STORE, OFFICE BOY (ATTENDANCE ONLY)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:it,account,store,office_boy'])
    ->prefix('staff/attendance')
    ->name('staff.attendance.')
    ->group(function () {

        Route::get('/', [AttendanceController::class, 'index'])->name('index');
        Route::post('/clock-in', [AttendanceController::class, 'clockIn'])->name('clockin');
        Route::post('/clock-out', [AttendanceController::class, 'clockOut'])->name('clockout');
        Route::get('/history', [AttendanceController::class, 'history'])->name('history');

    });

Route::middleware('auth')->get('/attendance/check-work-hours', [AttendanceController::class, 'checkWorkHours']);

