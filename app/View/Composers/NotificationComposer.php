<?php

namespace App\View\Composers;

use App\Models\Invoice;
use App\Models\Lead;
use App\Models\Subscription;
use Illuminate\View\View;

class NotificationComposer
{
    public function compose(View $view): void
    {
        $alerts = [];

        // 1. Invoice Overdue
        $overdueInvoices = Invoice::overdue()->with('client')->get();
        foreach ($overdueInvoices as $inv) {
            $days = $inv->due_date->diffInDays(now());
            $alerts[] = [
                'type' => 'warning',
                'icon' => '💰',
                'message' => "Invoice {$inv->invoice_number} untuk {$inv->client->name} sudah overdue {$days} hari",
                'url' => route('invoices.show', $inv),
                'priority' => 1,
            ];
        }

        // 2. Subscription Expiring (within 7 days)
        $expiringSubs = Subscription::active()
            ->whereNotNull('end_date')
            ->where('end_date', '<=', now()->addDays(7))
            ->where('end_date', '>=', now())
            ->with(['client', 'service'])
            ->get();
        foreach ($expiringSubs as $sub) {
            $days = now()->diffInDays($sub->end_date);
            $label = $days == 0 ? 'hari ini' : "dalam {$days} hari";
            $alerts[] = [
                'type' => 'info',
                'icon' => '🔔',
                'message' => "Subscription {$sub->service->name} untuk {$sub->client->name} akan berakhir {$label}",
                'url' => route('subscriptions.show', $sub),
                'priority' => 2,
            ];
        }

        // 3. Lead Follow-up Reminder (active leads not followed up in 3+ days)
        $staleLeads = Lead::active()
            ->where(function ($q) {
                $q->whereNull('last_follow_up')
                    ->orWhere('last_follow_up', '<', now()->subDays(3));
            })
            ->where('updated_at', '<', now()->subDays(3))
            ->limit(5)
            ->get();
        foreach ($staleLeads as $lead) {
            $days = $lead->last_follow_up
                ? $lead->last_follow_up->diffInDays(now())
                : $lead->created_at->diffInDays(now());
            $alerts[] = [
                'type' => 'reminder',
                'icon' => '📋',
                'message' => "Lead {$lead->name} belum di-follow up selama {$days} hari",
                'url' => route('leads.show', $lead),
                'priority' => 3,
            ];
        }

        // Sort by priority
        usort($alerts, fn($a, $b) => $a['priority'] <=> $b['priority']);

        $view->with('systemAlerts', $alerts);
    }
}
