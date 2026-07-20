<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CrmController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AIController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : view('auth.login');
})->name('home');

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (All Logged-in Users)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard & Session Control
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    /*
    |--------------------------------------------------------------------------
    | AI Assistant Module
    |--------------------------------------------------------------------------
    */

    Route::prefix('assistant')->name('assistant.')->group(function () {
        Route::get('/', [AIController::class, 'index'])->name('index');
        Route::post('/chat', [AIController::class, 'chat'])->name('chat');
    });

    Route::get('/test-gemini-key', function () {
        return config('services.gemini.key');
    });

    /*
    |--------------------------------------------------------------------------
    | Dynamic Permission-Based Module Routes
    |--------------------------------------------------------------------------
    */

    // CRM Module Permission Check
    Route::middleware('module:crm')->prefix('crm')->name('crm.')->group(function () {
        Route::get('/dashboard', [CrmController::class, 'dashboard'])->name('dashboard');

        // Customers
        Route::resource('customers', CrmController::class)->parameters([
            'customers' => 'customer'
        ])->except(['index']);

        Route::get('customers', [CrmController::class, 'customersIndex'])->name('customers.index');
        Route::get('customers/{customer}/interests', [CrmController::class, 'propertyInterestsIndex'])->name('customers.interests.index');
        Route::post('customers/{customer}/interests', [CrmController::class, 'propertyInterestsStore'])->name('customers.interests.store');

        // Leads
        Route::get('leads', [CrmController::class, 'leadsIndex'])->name('leads.index');
        Route::get('leads/create', [CrmController::class, 'leadsCreate'])->name('leads.create');
        Route::post('leads', [CrmController::class, 'leadsStore'])->name('leads.store');
        Route::get('leads/{lead}', [CrmController::class, 'leadsShow'])->name('leads.show');
        Route::get('leads/{lead}/edit', [CrmController::class, 'leadsEdit'])->name('leads.edit');
        Route::put('leads/{lead}', [CrmController::class, 'leadsUpdate'])->name('leads.update');
        Route::delete('leads/{lead}', [CrmController::class, 'leadsDestroy'])->name('leads.destroy');

        // Follow-ups
        Route::get('follow-ups', [CrmController::class, 'followUpsIndex'])->name('follow-ups.index');
        Route::get('follow-ups/create', [CrmController::class, 'followUpsCreate'])->name('follow-ups.create');
        Route::post('follow-ups', [CrmController::class, 'followUpsStore'])->name('follow-ups.store');
        Route::get('follow-ups/{followUp}/edit', [CrmController::class, 'followUpsEdit'])->name('follow-ups.edit');
        Route::put('follow-ups/{followUp}', [CrmController::class, 'followUpsUpdate'])->name('follow-ups.update');
        Route::delete('follow-ups/{followUp}', [CrmController::class, 'followUpsDestroy'])->name('follow-ups.destroy');

        // Reports
        Route::get('reports', [CrmController::class, 'reports'])->name('reports');
    });

    // Employee Management (HRM) Module Permission Check
    Route::middleware('module:hrm')->prefix('hrm')->name('hrm.')->group(function () {
        Route::get('employees/export', [EmployeeController::class, 'exportCSV'])
            ->name('employees.export');

        Route::resource('employees', EmployeeController::class);

        Route::resource('departments', DepartmentController::class);

        Route::get('attendance', [AttendanceController::class, 'index'])
            ->name('attendance.index');

        Route::post('attendance/mark', [AttendanceController::class, 'mark'])
            ->name('attendance.mark');

        Route::get('attendance/export', [AttendanceController::class, 'exportCSV'])
            ->name('attendance.export');
    });

    // Purchases Module Permission Check
    Route::middleware('module:purchases')->group(function () {
        Route::prefix('purchases')->name('purchases.')->group(function () {
            Route::get('export-csv', [PurchaseController::class, 'exportCsv'])
                ->name('export.csv');

            Route::post('bulk-delete', [PurchaseController::class, 'bulkDelete'])
                ->name('bulk-delete');
        });
        Route::resource('purchases', PurchaseController::class);
    });

    // Vendor Module Permission Check
    Route::middleware('module:vendors')->group(function () {
        Route::prefix('vendors')->name('vendors.')->group(function () {
            Route::post('bulk-delete', [VendorController::class, 'bulkDestroy'])
                ->name('bulk-delete');
        });
        Route::resource('vendors', VendorController::class);
    });

    // Project & Property Module Permission Check
    Route::middleware('module:projects')->prefix('modules')->group(function () {
        Route::resource('projects', ProjectController::class);
        Route::resource('properties', PropertyController::class);
    });

}); // End Auth Middleware Group