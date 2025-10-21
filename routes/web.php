<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceImportController;
use App\Http\Controllers\GstFilingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Api\MetricsController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\ItrController;
use App\Http\Controllers\GstSettingsController;
use App\Http\Controllers\GstReturnController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SalesInvoiceController;
use App\Http\Controllers\PurchaseInvoiceController;
use App\Http\Controllers\GstReturnAuditController;

/*
|--------------------------------------------------------------------------
| Auth (manual)
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Protected Application Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // API (dashboard widgets)
    Route::prefix('api')->group(function () {
        Route::get('/metrics', [MetricsController::class, 'metrics']);
        Route::get('/gst-summary', [MetricsController::class, 'gstSummary']);
        Route::get('/recent-invoices', [MetricsController::class, 'recentInvoices']);
        Route::get('/itr-summary', [MetricsController::class, 'itrSummary'])->name('api.itr.summary');
        Route::get('/recent-income-expenses', [MetricsController::class, 'recentIncomeExpenses'])->name('api.recent.incexp');
        Route::post('/chat', [ChatController::class, 'ask']);
    });

    // Import invoices (JSON)
    
Route::post('/invoices/import-json', [InvoiceImportController::class, 'store'])
     ->name('invoices.import-json');

    // GST stepper / 3B flow
    Route::get('/gst/stepper', [GstFilingController::class, 'stepper'])->name('gst.stepper');
    Route::post('/gst/summary', [GstFilingController::class, 'summary'])->name('gst.summary');
    Route::post('/gst/generate-gstr3b', [GstFilingController::class, 'generateGstr3b'])->name('gst.generate3b');
    Route::post('/gst/mark-filed', [GstFilingController::class, 'markFiled'])->name('gst.markfiled');

    // Reports & ITR
    Route::get('/reports/gst-summary.pdf', [ReportController::class, 'pdf'])->name('reports.gst.pdf');
    Route::get('/reports/gst-summary.csv', [ReportController::class, 'csv'])->name('reports.gst.csv');
    Route::get('/itr/summary', [ItrController::class, 'summary'])->name('itr.summary');
    Route::get('/itr/export.json', [ItrController::class, 'exportJson'])->name('itr.export.json');

    // Income / Expense / Reminders
    Route::get('/itr/incomes',  [IncomeController::class, 'index'])->name('incomes.index');
    Route::post('/itr/incomes', [IncomeController::class, 'store'])->name('incomes.store');
    Route::get('/itr/expenses',  [ExpenseController::class, 'index'])->name('expenses.index');
    Route::post('/itr/expenses', [ExpenseController::class, 'store'])->name('expenses.store');

    Route::get('/reminders', [ReminderController::class, 'index'])->name('reminders.index');
    Route::post('/reminders', [ReminderController::class, 'store'])->name('reminders.store');

    // Audit (global page)
    Route::get('/audits', [AuditController::class, 'index'])->name('audits.index');

    // GST Settings - Complete CRUD
    Route::get('/gst/settings', [GstSettingsController::class, 'edit'])->name('gst.settings');
    Route::post('/gst/settings', [GstSettingsController::class, 'update'])->name('gst.settings.update');
    Route::get('/gst/settings/show', [GstSettingsController::class, 'show'])->name('gst.settings.show');
    Route::delete('/gst/settings', [GstSettingsController::class, 'destroy'])->name('gst.settings.destroy');

    /*
    |--------------------------------------------------------------------------
    | Phase-4 GST Returns (all types)
    |--------------------------------------------------------------------------
    */
    Route::prefix('gst')->name('gst.')->group(function () {

        // Returns CRUD
        Route::get('/returns', [GstReturnController::class, 'index'])->name('returns.index');
        Route::get('/returns/create', [GstReturnController::class, 'create'])->name('returns.create');
        Route::post('/returns', [GstReturnController::class, 'store'])->name('returns.store');
        Route::get('/returns/{gstReturn}', [GstReturnController::class, 'show'])->name('returns.show'); // <-- FIXED PATH
        Route::get('/returns/{gstReturn}/edit', [GstReturnController::class, 'edit'])->name('returns.edit');
        Route::put('/returns/{gstReturn}', [GstReturnController::class, 'update'])->name('returns.update');
        Route::delete('/returns/{gstReturn}', [GstReturnController::class, 'destroy'])->name('returns.destroy');

        // Actions
        Route::post('/returns/{gstReturn}/prepare', [GstReturnController::class, 'prepare'])->name('returns.prepare');
        Route::get('/returns/{gstReturn}/export', [GstReturnController::class, 'exportJson'])->name('returns.export'); // consistent name
        Route::post('/returns/{gstReturn}/file', [GstReturnController::class, 'fileReturn'])->name('returns.file');

        // Return summary endpoints (alternative paths)
        Route::get('/returns/gstr1', [GstReturnController::class, 'gstr1']);
        Route::get('/returns/gstr3b', [GstReturnController::class, 'gstr3b']);
        Route::get('/returns/cmp08', [GstReturnController::class, 'cmp08']);

        // Audit uploads (tied to returns)
        Route::post('/returns/{gstReturn}/audit', [GstReturnAuditController::class, 'store'])->name('returns.audit');
        Route::get('/returns/audit/{audit}/download', [GstReturnAuditController::class, 'download'])->name('returns.audit.download');
        Route::delete('/returns/audit/{audit}', [GstReturnAuditController::class, 'destroy'])->name('returns.audit.delete');

        // Composition (CMP-08 / GSTR-4)
        Route::get('/composition', [GstReturnController::class, 'cmpDashboard'])->name('cmp.dashboard');
        Route::put('/composition/{rec}', [GstReturnController::class, 'cmpUpdate'])->name('cmp.update');

        // Return Summary APIs
        Route::get('/summary/gstr1', [GstReturnController::class, 'gstr1'])->name('summary.gstr1');
        Route::get('/summary/gstr3b', [GstReturnController::class, 'gstr3b'])->name('summary.gstr3b');
        Route::get('/summary/cmp08', [GstReturnController::class, 'cmp08'])->name('summary.cmp08');
        Route::get('/recommendations', [GstReturnController::class, 'recommendations'])->name('recommendations');
    });

    // Sales Invoices
    Route::prefix('sales')->name('sales.')->group(function () {
        Route::get('/invoices', [SalesInvoiceController::class, 'index'])->name('invoices.index');
        Route::get('/invoices/create', [SalesInvoiceController::class, 'create'])->name('invoices.create');
        Route::post('/invoices', [SalesInvoiceController::class, 'store'])->name('invoices.store');
        Route::get('/invoices/{salesInvoice}', [SalesInvoiceController::class, 'show'])->name('invoices.show');
        Route::get('/invoices/{salesInvoice}/edit', [SalesInvoiceController::class, 'edit'])->name('invoices.edit');
        Route::put('/invoices/{salesInvoice}', [SalesInvoiceController::class, 'update'])->name('invoices.update');
        Route::delete('/invoices/{salesInvoice}', [SalesInvoiceController::class, 'destroy'])->name('invoices.destroy');
    });

    // Purchase Invoices
    Route::prefix('purchases')->name('purchases.')->group(function () {
        Route::get('/invoices', [PurchaseInvoiceController::class, 'index'])->name('invoices.index');
        Route::get('/invoices/create', [PurchaseInvoiceController::class, 'create'])->name('invoices.create');
        Route::post('/invoices', [PurchaseInvoiceController::class, 'store'])->name('invoices.store');
        Route::get('/invoices/{purchaseInvoice}', [PurchaseInvoiceController::class, 'show'])->name('invoices.show');
        Route::get('/invoices/{purchaseInvoice}/edit', [PurchaseInvoiceController::class, 'edit'])->name('invoices.edit');
        Route::put('/invoices/{purchaseInvoice}', [PurchaseInvoiceController::class, 'update'])->name('invoices.update');
        Route::delete('/invoices/{purchaseInvoice}', [PurchaseInvoiceController::class, 'destroy'])->name('invoices.destroy');
    });
});
