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
        $query = Lead::with(['service', 'company', 'client']);

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
        if ($request->filled('company_id') && auth()->user()->isSuperAdmin()) {
            $query->where('company_id', $request->company_id);
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

    public function convert(Request $request, Lead $lead)
    {
        // Validate that lead hasn't been converted yet
        if ($lead->client) {
            return redirect()->route('leads.show', $lead)
                ->with('error', 'Lead ini sudah dikonversi menjadi client!');
        }

        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_phone' => 'required|string|max:20',
            'client_email' => 'nullable|email|max:255',
            'business_name' => 'required|string|max:255',
            'industry' => 'required|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            // Project fields
            'create_project' => 'nullable|boolean',
            'project_title' => 'required_if:create_project,1|nullable|string|max:255',
            'project_start_date' => 'required_if:create_project,1|nullable|date',
            'project_deadline' => 'required_if:create_project,1|nullable|date|after_or_equal:project_start_date',
            'project_description' => 'nullable|string',
            // Subscription fields
            'create_subscription' => 'nullable|boolean',
            'billing_cycle' => 'required_if:create_subscription,1|nullable|in:monthly,quarterly,yearly',
            'subscription_start_date' => 'required_if:create_subscription,1|nullable|date',
            'subscription_end_date' => 'required_if:create_subscription,1|nullable|date|after:subscription_start_date',
        ]);

        $result = $this->leadService->convertLead($lead, $validated);

        $message = "Lead berhasil dikonversi menjadi Client: {$result['client']->name}";
        if ($result['project']) {
            $message .= " + Project: {$result['project']->title}";
        }
        if ($result['subscription']) {
            $message .= " + Subscription aktif";
        }

        return redirect()->route('clients.show', $result['client'])
            ->with('success', $message);
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();
        return redirect()->route('leads.index')->with('success', 'Lead berhasil dihapus!');
    }
}
