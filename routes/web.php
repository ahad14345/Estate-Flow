<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CrmController;
use App\Http\Controllers\ProjectController;
use App\http\Controllers\PropertyController;
use App\Http\Controllers\PurchaseController;
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return view('auth.login');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::prefix('crm')->name('crm.')->group(function () {
        Route::get('/dashboard', [CrmController::class, 'dashboard'])->name('dashboard');
        Route::get('/customers', [CrmController::class, 'customersIndex'])->name('customers.index');
        Route::get('/customers/create', [CrmController::class, 'customersCreate'])->name('customers.create');
        Route::post('/customers', [CrmController::class, 'customersStore'])->name('customers.store');
        Route::get('/customers/{customer}', [CrmController::class, 'customersShow'])->name('customers.show');
        Route::get('/customers/{customer}/edit', [CrmController::class, 'customersEdit'])->name('customers.edit');
        Route::put('/customers/{customer}', [CrmController::class, 'customersUpdate'])->name('customers.update');
        Route::delete('/customers/{customer}', [CrmController::class, 'customersDestroy'])->name('customers.destroy');

        Route::get('/customers/{customer}/interests', [CrmController::class, 'propertyInterestsIndex'])->name('customers.interests.index');
        Route::post('/customers/{customer}/interests', [CrmController::class, 'propertyInterestsStore'])->name('customers.interests.store');

        Route::get('/leads', [CrmController::class, 'leadsIndex'])->name('leads.index');
        Route::get('/leads/create', [CrmController::class, 'leadsCreate'])->name('leads.create');
        Route::post('/leads', [CrmController::class, 'leadsStore'])->name('leads.store');
        Route::get('/leads/{lead}', [CrmController::class, 'leadsShow'])->name('leads.show');
        Route::get('/leads/{lead}/edit', [CrmController::class, 'leadsEdit'])->name('leads.edit');
        Route::put('/leads/{lead}', [CrmController::class, 'leadsUpdate'])->name('leads.update');
        Route::delete('/leads/{lead}', [CrmController::class, 'leadsDestroy'])->name('leads.destroy');

        Route::get('/follow-ups', [CrmController::class, 'followUpsIndex'])->name('follow-ups.index');
        Route::get('/follow-ups/create', [CrmController::class, 'followUpsCreate'])->name('follow-ups.create');
        Route::post('/follow-ups', [CrmController::class, 'followUpsStore'])->name('follow-ups.store');
        Route::get('/follow-ups/{followUp}/edit', [CrmController::class, 'followUpsEdit'])->name('follow-ups.edit');
        Route::put('/follow-ups/{followUp}', [CrmController::class, 'followUpsUpdate'])->name('follow-ups.update');
        Route::delete('/follow-ups/{followUp}', [CrmController::class, 'followUpsDestroy'])->name('follow-ups.destroy');

        Route::get('/reports', [CrmController::class, 'reports'])->name('reports');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
Route::prefix('modules')->group(function () {
    Route::resource('projects', ProjectController::class);
    Route::resource('properties', PropertyController::class);
});
Route::middleware(['auth'])->group(function () {
    Route::get('purchases/export-csv', [PurchaseController::class, 'exportCsv'])->name('purchases.export.csv');
    Route::post('purchases/bulk-delete', [PurchaseController::class, 'bulkDelete'])->name('purchases.bulk-delete');

    Route::resource('purchases', PurchaseController::class);
});