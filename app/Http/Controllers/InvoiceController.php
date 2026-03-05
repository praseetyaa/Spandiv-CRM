<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Client;
use App\Models\Project;
use App\Models\Subscription;
use App\Models\Activity;
use App\Services\InvoiceService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function __construct(private InvoiceService $invoiceService)
    {
    }

    public function index(Request $request)
    {
        $query = Invoice::with(['client', 'project', 'subscription', 'company']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('invoice_number', 'like', "%{$request->search}%")
                    ->orWhereHas('client', function ($q2) use ($request) {
                        $q2->where('name', 'like', "%{$request->search}%");
                    });
            });
        }
        if ($request->filled('company_id') && auth()->user()->isSuperAdmin()) {
            $query->where('company_id', $request->company_id);
        }

        $invoices = $query->latest()->paginate(15);

        return view('invoices.index', compact('invoices'));
    }

    public function create(Request $request)
    {
        $clients = Client::active()->get();
        $projects = Project::all();
        $subscriptions = Subscription::active()->get();
        $prefill = [
            'client_id' => $request->query('client_id'),
            'project_id' => $request->query('project_id'),
            'item_desc' => $request->query('item_desc'),
            'item_price' => $request->query('item_price'),
        ];
        return view('invoices.create', compact('clients', 'projects', 'subscriptions', 'prefill'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'subscription_id' => 'nullable|exists:subscriptions,id',
            'issue_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'discount' => 'nullable|numeric|min:0',
            'status' => 'required|in:draft,sent,partial,paid,overdue',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $items = $validated['items'];
        unset($validated['items']);

        $this->invoiceService->createInvoice($validated, $items);

        return redirect()->route('invoices.index')->with('success', 'Invoice berhasil dibuat!');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['client', 'project', 'subscription', 'payments', 'items', 'company']);
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $invoice->load('items');
        $clients = Client::active()->get();
        $projects = Project::all();
        $subscriptions = Subscription::all();
        return view('invoices.edit', compact('invoice', 'clients', 'projects', 'subscriptions'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'subscription_id' => 'nullable|exists:subscriptions,id',
            'due_date' => 'required|date',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'discount' => 'nullable|numeric|min:0',
            'status' => 'required|in:draft,sent,partial,paid,overdue',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $items = $validated['items'];
        unset($validated['items']);

        $this->invoiceService->updateInvoice($invoice, $validated, $items);

        return redirect()->route('invoices.show', $invoice)->with('success', 'Invoice berhasil diupdate!');
    }

    public function destroy(Invoice $invoice)
    {
        try {
            $invoice->items()->delete();
            Activity::log('deleted', 'invoices', "Invoice dihapus: {$invoice->invoice_number}", $invoice);
            $invoice->delete();
            return redirect()->route('invoices.index')->with('success', 'Invoice berhasil dihapus!');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('invoices.index')->with('error', 'Tidak bisa menghapus invoice ini karena masih memiliki pembayaran terkait.');
        }
    }

    public function downloadPdf(Invoice $invoice)
    {
        $invoice->load(['client', 'project', 'subscription', 'items', 'company']);

        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("Invoice-{$invoice->invoice_number}.pdf");
    }

    public function generateRecurring()
    {
        $generated = $this->invoiceService->generateRecurringInvoices();
        $count = count($generated);

        return redirect()->route('invoices.index')
            ->with('success', "{$count} invoice recurring berhasil digenerate!");
    }
}
