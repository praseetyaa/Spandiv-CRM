<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\UserController;

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Redirect root to dashboard
Route::get('/', function () {
    return redirect('/dashboard');
});

// Protected Routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Leads
    Route::resource('leads', LeadController::class);

    // Clients
    Route::resource('clients', ClientController::class);

    // Services
    Route::resource('services', ServiceController::class)->except(['show']);

    // Projects
    Route::resource('projects', ProjectController::class);
    Route::post('/projects/{project}/milestones', [ProjectController::class, 'storeMilestone'])->name('projects.milestones.store');
    Route::patch('/projects/{project}/milestones/{milestone}/toggle', [ProjectController::class, 'toggleMilestone'])->name('projects.milestones.toggle');
    Route::delete('/projects/{project}/milestones/{milestone}', [ProjectController::class, 'destroyMilestone'])->name('projects.milestones.destroy');

    // Subscriptions
    Route::resource('subscriptions', SubscriptionController::class);
    Route::patch('/subscriptions/{subscription}/toggle-pause', [SubscriptionController::class, 'togglePause'])->name('subscriptions.toggle-pause');

    // Invoices
    Route::resource('invoices', InvoiceController::class);
    Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'downloadPdf'])->name('invoices.pdf');
    Route::post('/invoices/generate-recurring', [InvoiceController::class, 'generateRecurring'])->name('invoices.generate-recurring');

    // Payments
    Route::resource('payments', PaymentController::class)->except(['edit', 'update']);

    // Activities
    Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');

    // User Management (admin & superadmin)
    Route::middleware('role:superadmin,admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
    });

    // Company Management (superadmin only)
    Route::middleware('role:superadmin')->group(function () {
        Route::resource('companies', CompanyController::class)->except(['show']);
    });
});
