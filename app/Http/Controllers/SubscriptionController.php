<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\Client;
use App\Models\Service;
use App\Models\Activity;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $query = Subscription::with(['client', 'service']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $subscriptions = $query->latest()->paginate(15);

        return view('subscriptions.index', compact('subscriptions'));
    }

    public function create()
    {
        $clients = Client::active()->get();
        $services = Service::active()->recurring()->get();
        return view('subscriptions.create', compact('clients', 'services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'service_id' => 'required|exists:services,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'billing_cycle' => 'required|in:monthly',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,paused,cancelled',
        ]);

        $subscription = Subscription::create($validated);
        Activity::log('created', 'subscriptions', "Subscription baru: {$subscription->service->name} untuk {$subscription->client->name}", $subscription);

        return redirect()->route('subscriptions.index')->with('success', 'Subscription berhasil ditambahkan!');
    }

    public function show(Subscription $subscription)
    {
        $subscription->load(['client', 'service', 'invoices.payments']);
        return view('subscriptions.show', compact('subscription'));
    }

    public function edit(Subscription $subscription)
    {
        $clients = Client::active()->get();
        $services = Service::active()->recurring()->get();
        return view('subscriptions.edit', compact('subscription', 'clients', 'services'));
    }

    public function update(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'service_id' => 'required|exists:services,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'billing_cycle' => 'required|in:monthly',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,paused,cancelled',
        ]);

        $subscription->update($validated);
        Activity::log('updated', 'subscriptions', "Subscription diupdate", $subscription);

        return redirect()->route('subscriptions.index')->with('success', 'Subscription berhasil diupdate!');
    }

    public function togglePause(Subscription $subscription)
    {
        $newStatus = $subscription->status === 'active' ? 'paused' : 'active';
        $subscription->update(['status' => $newStatus]);
        Activity::log('toggled', 'subscriptions', "Subscription {$newStatus}", $subscription);

        return redirect()->route('subscriptions.index')->with('success', "Subscription berhasil di-{$newStatus}!");
    }

    public function destroy(Subscription $subscription)
    {
        try {
            Activity::log('deleted', 'subscriptions', "Subscription dihapus", $subscription);
            $subscription->delete();
            return redirect()->route('subscriptions.index')->with('success', 'Subscription berhasil dihapus!');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('subscriptions.index')->with('error', 'Tidak bisa menghapus subscription ini karena masih memiliki data terkait.');
        }
    }
}
