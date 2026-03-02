<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Subscription;
use App\Models\Activity;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    /**
     * Create invoice with line items.
     */
    public function createInvoice(array $data, array $items = []): Invoice
    {
        return DB::transaction(function () use ($data, $items) {
            $data['invoice_number'] = Invoice::generateInvoiceNumber();

            if (!isset($data['issue_date'])) {
                $data['issue_date'] = now()->toDateString();
            }
            if (!isset($data['due_date'])) {
                $data['due_date'] = now()->addDays(14)->toDateString();
            }
            if (!isset($data['paid_amount'])) {
                $data['paid_amount'] = 0;
            }

            // Calculate totals from items
            $subtotal = 0;
            foreach ($items as $item) {
                $subtotal += ($item['quantity'] ?? 1) * ($item['unit_price'] ?? 0);
            }

            $taxRate = $data['tax_rate'] ?? 11;
            $discount = $data['discount'] ?? 0;
            $taxAmount = round($subtotal * ($taxRate / 100), 2);
            $totalAmount = max(0, $subtotal + $taxAmount - $discount);

            $data['subtotal'] = $subtotal;
            $data['tax_amount'] = $taxAmount;
            $data['total_amount'] = $totalAmount;

            $invoice = Invoice::create($data);

            // Create line items
            foreach ($items as $item) {
                $qty = $item['quantity'] ?? 1;
                $price = $item['unit_price'] ?? 0;
                $invoice->items()->create([
                    'description' => $item['description'] ?? '',
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'amount' => $qty * $price,
                ]);
            }

            Activity::log('created', 'invoices', "Invoice {$invoice->invoice_number} dibuat", $invoice);

            return $invoice;
        });
    }

    /**
     * Update invoice with line items.
     */
    public function updateInvoice(Invoice $invoice, array $data, array $items = []): Invoice
    {
        return DB::transaction(function () use ($invoice, $data, $items) {
            // Calculate totals from items
            $subtotal = 0;
            foreach ($items as $item) {
                $subtotal += ($item['quantity'] ?? 1) * ($item['unit_price'] ?? 0);
            }

            $taxRate = $data['tax_rate'] ?? $invoice->tax_rate;
            $discount = $data['discount'] ?? $invoice->discount;
            $taxAmount = round($subtotal * ($taxRate / 100), 2);
            $totalAmount = max(0, $subtotal + $taxAmount - $discount);

            $data['subtotal'] = $subtotal;
            $data['tax_amount'] = $taxAmount;
            $data['total_amount'] = $totalAmount;

            $invoice->update($data);

            // Replace line items
            $invoice->items()->delete();
            foreach ($items as $item) {
                $qty = $item['quantity'] ?? 1;
                $price = $item['unit_price'] ?? 0;
                $invoice->items()->create([
                    'description' => $item['description'] ?? '',
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'amount' => $qty * $price,
                ]);
            }

            Activity::log('updated', 'invoices', "Invoice {$invoice->invoice_number} diupdate", $invoice);

            return $invoice;
        });
    }

    public function generateRecurringInvoices(): array
    {
        $generated = [];

        $subscriptions = Subscription::active()->get();

        foreach ($subscriptions as $subscription) {
            if ($subscription->needsInvoice()) {
                $invoice = $this->createInvoice([
                    'client_id' => $subscription->client_id,
                    'subscription_id' => $subscription->id,
                    'total_amount' => $subscription->price,
                    'status' => 'sent',
                    'tax_rate' => 11,
                    'notes' => "Invoice otomatis untuk langganan {$subscription->service->name}",
                ], [
                    [
                        'description' => $subscription->service->name . ' - Langganan Bulanan',
                        'quantity' => 1,
                        'unit_price' => $subscription->price,
                    ]
                ]);

                $generated[] = $invoice;
            }
        }

        if (count($generated) > 0) {
            Activity::log('auto_generated', 'invoices', count($generated) . " invoice recurring dibuat otomatis");
        }

        return $generated;
    }

    public function markOverdueInvoices(): int
    {
        $count = Invoice::where('due_date', '<', now())
            ->whereNotIn('status', ['paid', 'draft'])
            ->update(['status' => 'overdue']);

        if ($count > 0) {
            Activity::log('auto_overdue', 'invoices', "{$count} invoice ditandai overdue");
        }

        return $count;
    }
}
