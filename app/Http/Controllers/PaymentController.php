<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Activity;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['invoice.client']);

        if ($request->filled('method')) {
            $query->where('method', $request->method);
        }

        $payments = $query->latest()->paginate(15);

        return view('payments.index', compact('payments'));
    }

    public function create(Request $request)
    {
        $invoices = Invoice::unpaid()->with('client')->get();
        $selectedInvoice = $request->invoice_id ? Invoice::find($request->invoice_id) : null;
        return view('payments.create', compact('invoices', 'selectedInvoice'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:1',
            'method' => 'required|in:transfer,cash,e-wallet',
            'payment_date' => 'required|date',
            'proof_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'notes' => 'nullable|string',
        ]);

        if ($request->hasFile('proof_file')) {
            $validated['proof_file'] = $request->file('proof_file')
                ->store('payment-proofs', 'public');
        }

        $payment = Payment::create($validated);
        Activity::log('created', 'payments', "Pembayaran Rp " . number_format($payment->amount) . " untuk {$payment->invoice->invoice_number}", $payment);

        return redirect()->route('payments.index')->with('success', 'Pembayaran berhasil dicatat!');
    }

    public function show(Payment $payment)
    {
        $payment->load(['invoice.client']);
        return view('payments.show', compact('payment'));
    }

    public function destroy(Payment $payment)
    {
        $invoice = $payment->invoice;
        Activity::log('deleted', 'payments', "Pembayaran dihapus: {$invoice->invoice_number}", $payment);
        $payment->delete();

        // Recalculate invoice status
        $invoice->recalculateStatus();

        return redirect()->route('payments.index')->with('success', 'Pembayaran berhasil dihapus!');
    }
}
