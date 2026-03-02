<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\Client;
use App\Models\Project;
use App\Models\Subscription;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Service;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getStats(): array
    {
        return [
            'total_leads_this_month' => $this->totalLeadsThisMonth(),
            'conversion_rate' => $this->conversionRate(),
            'active_projects' => Project::active()->count(),
            'active_subscriptions' => Subscription::active()->count(),
            'monthly_revenue' => $this->monthlyRevenue(),
            'mrr' => $this->monthlyRecurringRevenue(),
            'overdue_invoices' => Invoice::overdue()->count(),
            'total_clients' => Client::active()->count(),
        ];
    }

    public function totalLeadsThisMonth(): int
    {
        return Lead::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
    }

    public function conversionRate(): float
    {
        $totalLeads = Lead::count();
        if ($totalLeads === 0)
            return 0;

        $wonLeads = Lead::won()->count();
        return round(($wonLeads / $totalLeads) * 100, 1);
    }

    public function monthlyRevenue(): float
    {
        return Payment::whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->sum('amount');
    }

    public function monthlyRecurringRevenue(): float
    {
        return Subscription::active()->sum('price');
    }

    public function revenuePerService(): array
    {
        return Service::select('services.name', 'services.category')
            ->selectRaw('COALESCE(SUM(invoices.paid_amount), 0) as total_revenue')
            ->leftJoin('projects', 'services.id', '=', 'projects.service_id')
            ->leftJoin('invoices', 'projects.id', '=', 'invoices.project_id')
            ->groupBy('services.id', 'services.name', 'services.category')
            ->orderByDesc('total_revenue')
            ->get()
            ->toArray();
    }

    public function revenuePerCategory(): array
    {
        return DB::table('services')
            ->select('services.category')
            ->selectRaw('COALESCE(SUM(payments.amount), 0) as total_revenue')
            ->leftJoin('projects', 'services.id', '=', 'projects.service_id')
            ->leftJoin('invoices', 'projects.id', '=', 'invoices.project_id')
            ->leftJoin('payments', 'invoices.id', '=', 'payments.invoice_id')
            ->groupBy('services.category')
            ->get()
            ->toArray();
    }

    public function topPayingClients(int $limit = 5): array
    {
        return Client::orderByDesc('total_lifetime_value')
            ->take($limit)
            ->get()
            ->toArray();
    }

    public function leadSourcePerformance(): array
    {
        return Lead::select('source')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(CASE WHEN status = "closed_won" THEN 1 ELSE 0 END) as won')
            ->groupBy('source')
            ->get()
            ->toArray();
    }

    public function revenueChart(int $months = 6): array
    {
        $data = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $revenue = Payment::whereMonth('payment_date', $date->month)
                ->whereYear('payment_date', $date->year)
                ->sum('amount');

            $data[] = [
                'month' => $date->format('M Y'),
                'revenue' => $revenue,
            ];
        }
        return $data;
    }
}
