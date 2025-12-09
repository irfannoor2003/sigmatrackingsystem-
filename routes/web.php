<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\SalesmanDashboardController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Admin\SalesmanController as AdminSalesmanController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Salesman\CustomerController as SalesmanCustomerController;

// --------------------------------------------------
// PUBLIC
// --------------------------------------------------
Route::get('/', function () {
    return view('welcome');
});

// Dashboard auto redirect
Route::get('/dashboard', function () {
    $user = auth()->user();

    return $user->role === 'admin'
        ? redirect()->route('admin.dashboard')
        : redirect()->route('salesman.dashboard');
})->middleware('auth')->name('dashboard');

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


// ======================================================
// ADMIN ROUTES
// ======================================================
Route::middleware(['auth','role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Reports (Admin Only)
    Route::get('/reports', [ReportController::class, 'adminReport'])->name('reports.index');
    Route::get('/reports/{id}', [ReportController::class, 'show'])->name('reports.show');

    // Customers
    Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{id}', [AdminCustomerController::class, 'show'])->name('customers.show');

    // Salesmen
    Route::get('/salesmen', [AdminSalesmanController::class, 'index'])->name('salesmen.index');
    Route::get('/salesmen/create', [AdminSalesmanController::class, 'create'])->name('salesmen.create');
    Route::post('/salesmen/store', [AdminSalesmanController::class, 'store'])->name('salesmen.store');
});


// ======================================================
// SALESMAN ROUTES
// ======================================================
Route::middleware(['auth','role:salesman'])
    ->prefix('salesman')
    ->name('salesman.')
    ->group(function () {

    Route::get('/dashboard', [SalesmanDashboardController::class, 'index'])->name('dashboard');

    // Customers
    Route::get('/customers', [SalesmanCustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/create', [SalesmanCustomerController::class, 'create'])->name('customers.create');
    Route::post('/customers', [SalesmanCustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{id}', [SalesmanCustomerController::class, 'show'])->name('customers.show');

    // Visits
    Route::get('/visits', [VisitController::class, 'index'])->name('visits.index');
    Route::get('/visits/create', [VisitController::class, 'create'])->name('visits.create');
    Route::post('/visits', [VisitController::class, 'store'])->name('visits.store');
    Route::post('/visits/{id}/complete', [VisitController::class, 'complete'])->name('visits.complete');
    Route::get('/visits/{id}', [VisitController::class, 'show'])->name('visits.show');

    // Attendance
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/clock-in', [AttendanceController::class, 'clockIn'])->name('attendance.clockin');
    Route::post('/attendance/clock-out', [AttendanceController::class, 'clockOut'])->name('attendance.clockout');

    // Reports
    Route::get('/reports', [ReportController::class, 'salesmanReport'])->name('reports.index');
});
