<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use App\Services\InvoiceService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardService $dashboardService,
        private InvoiceService $invoiceService
    ) {
    }

    public function index()
    {
        // Mark overdue invoices
        $this->invoiceService->markOverdueInvoices();

        $stats = $this->dashboardService->getStats();
        $revenueChart = $this->dashboardService->revenueChart(6);
        $topClients = $this->dashboardService->topPayingClients(5);
        $leadSources = $this->dashboardService->leadSourcePerformance();
        $revenuePerCategory = $this->dashboardService->revenuePerCategory();

        return view('dashboard', compact(
            'stats',
            'revenueChart',
            'topClients',
            'leadSources',
            'revenuePerCategory'
        ));
    }
}
