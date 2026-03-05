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
use App\Http\Controllers\SystemSettingController;
use App\Http\Controllers\SystemUpdateController;
use App\Http\Controllers\PublicRequirementController;
use App\Http\Controllers\RequirementFieldController;
use App\Http\Controllers\AdminRequirementController;

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Redirect root to dashboard
Route::get('/', function () {
    return redirect('/dashboard');
});

// ── Public Requirement Form (no auth, unique per company) ──
Route::get('/requirements/{formSlug}', [PublicRequirementController::class, 'showForm'])
    ->middleware('throttle:30,1')
    ->name('requirements.form');
Route::post('/requirements/{formSlug}', [PublicRequirementController::class, 'submit'])
    ->middleware('throttle:5,60')
    ->name('requirements.submit');
Route::get('/requirements/{formSlug}/thank-you', [PublicRequirementController::class, 'thankYou'])
    ->name('requirements.thank-you');
Route::get('/api/requirement-fields/{formSlug}/{category}', [PublicRequirementController::class, 'getFieldsByCategory'])
    ->middleware('throttle:60,1')
    ->name('api.requirement-fields');

// Protected Routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Leads
    Route::resource('leads', LeadController::class);
    Route::post('/leads/{lead}/convert', [LeadController::class, 'convert'])->name('leads.convert');

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

    // ── Form Builder & Requirements Admin ────────────────
    Route::resource('requirement-fields', RequirementFieldController::class)->except(['show']);
    Route::post('requirement-fields/reorder', [RequirementFieldController::class, 'reorder'])->name('requirement-fields.reorder');

    Route::get('admin/requirements', [AdminRequirementController::class, 'index'])->name('admin.requirements.index');
    Route::get('admin/requirements/{requirement}', [AdminRequirementController::class, 'show'])->name('admin.requirements.show');
    Route::post('admin/requirements/{requirement}/convert', [AdminRequirementController::class, 'convertToLead'])->name('admin.requirements.convert');
    Route::delete('admin/requirements/{requirement}', [AdminRequirementController::class, 'destroy'])->name('admin.requirements.destroy');

    // User Management (admin & superadmin)
    Route::middleware('role:superadmin,admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
    });

    // Company Management (superadmin only)
    Route::middleware('role:superadmin')->group(function () {
        Route::resource('companies', CompanyController::class)->except(['show']);

        // System Settings
        Route::get('system-settings', [SystemSettingController::class, 'index'])->name('system-settings.index');
        Route::post('system-settings', [SystemSettingController::class, 'update'])->name('system-settings.update');
        Route::post('system-settings/test-email', [SystemSettingController::class, 'testEmail'])->name('system-settings.test-email');
        Route::post('system-settings/maintenance', [SystemSettingController::class, 'toggleMaintenance'])->name('system-settings.maintenance');
        Route::post('system-settings/backup', [SystemSettingController::class, 'runBackup'])->name('system-settings.backup');

        // System Update (AJAX JSON endpoints)
        Route::get('system-update/check', [SystemUpdateController::class, 'checkUpdate'])->name('system-update.check');
        Route::post('system-update/run', [SystemUpdateController::class, 'runUpdate'])->name('system-update.run');
    });
});

