<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Activity;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::with('company');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('business_name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%");
            });
        }
        if ($request->filled('status')) {
            $query->where('client_status', $request->status);
        }
        if ($request->filled('industry')) {
            $query->where('industry', $request->industry);
        }
        if ($request->filled('company_id') && auth()->user()->isSuperAdmin()) {
            $query->where('company_id', $request->company_id);
        }

        $clients = $query->withCount(['projects', 'subscriptions', 'invoices'])
            ->latest()
            ->paginate(15);

        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'business_name' => 'required|string|max:255',
            'industry' => 'required|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'client_status' => 'required|in:active,inactive',
        ]);

        $client = Client::create($validated);
        Activity::log('created', 'clients', "Client baru: {$client->name}", $client);

        return redirect()->route('clients.index')->with('success', 'Client berhasil ditambahkan!');
    }

    public function show(Client $client)
    {
        $client->load(['projects.service', 'subscriptions.service', 'invoices.payments']);
        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'business_name' => 'required|string|max:255',
            'industry' => 'required|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'client_status' => 'required|in:active,inactive',
        ]);

        $client->update($validated);
        Activity::log('updated', 'clients', "Client diupdate: {$client->name}", $client);

        return redirect()->route('clients.index')->with('success', 'Client berhasil diupdate!');
    }

    public function destroy(Client $client)
    {
        try {
            Activity::log('deleted', 'clients', "Client dihapus: {$client->name}", $client);
            $client->delete();
            return redirect()->route('clients.index')->with('success', 'Client berhasil dihapus!');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('clients.index')->with('error', 'Tidak bisa menghapus client ini karena masih memiliki project, invoice, atau subscription terkait.');
        }
    }
}
