<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Service;
use App\Services\LeadService;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function __construct(private LeadService $leadService)
    {
    }

    public function index(Request $request)
    {
        $query = Lead::with('service');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('phone', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%");
            });
        }
        if ($request->filled('urgency')) {
            $query->where('urgency_level', $request->urgency);
        }

        $leads = $query->latest()->paginate(15);
        $services = Service::active()->get();
        $pipelineStats = $this->leadService->getPipelineStats();

        return view('leads.index', compact('leads', 'services', 'pipelineStats'));
    }

    public function create()
    {
        $services = Service::active()->get();
        return view('leads.create', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'source' => 'required|string|max:255',
            'service_id' => 'required|exists:services,id',
            'estimated_value' => 'nullable|numeric|min:0',
            'urgency_level' => 'required|in:low,medium,high',
            'status' => 'required|in:new,contacted,proposal_sent,negotiation,closed_won,closed_lost',
            'notes' => 'nullable|string',
        ]);

        $this->leadService->createLead($validated);

        return redirect()->route('leads.index')->with('success', 'Lead berhasil ditambahkan!');
    }

    public function show(Lead $lead)
    {
        $lead->load('service', 'client');
        return view('leads.show', compact('lead'));
    }

    public function edit(Lead $lead)
    {
        $services = Service::active()->get();
        return view('leads.edit', compact('lead', 'services'));
    }

    public function update(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'source' => 'required|string|max:255',
            'service_id' => 'required|exists:services,id',
            'estimated_value' => 'nullable|numeric|min:0',
            'urgency_level' => 'required|in:low,medium,high',
            'status' => 'required|in:new,contacted,proposal_sent,negotiation,closed_won,closed_lost',
            'notes' => 'nullable|string',
        ]);

        $this->leadService->updateLead($lead, $validated);

        return redirect()->route('leads.index')->with('success', 'Lead berhasil diupdate!');
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();
        return redirect()->route('leads.index')->with('success', 'Lead berhasil dihapus!');
    }
}
