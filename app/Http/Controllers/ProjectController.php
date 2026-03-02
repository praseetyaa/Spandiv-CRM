<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\Client;
use App\Models\Service;
use App\Models\Activity;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::with(['client', 'service']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        $projects = $query->latest()->paginate(15);

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        $clients = Client::active()->get();
        $services = Service::active()->oneTime()->get();
        return view('projects.create', compact('clients', 'services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'service_id' => 'required|exists:services,id',
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'deadline' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:brief,dp_paid,on_progress,revision,waiting_client,completed',
            'description' => 'nullable|string',
        ]);

        $project = Project::create($validated);
        Activity::log('created', 'projects', "Project baru: {$project->title}", $project);

        return redirect()->route('projects.index')->with('success', 'Project berhasil ditambahkan!');
    }

    public function show(Project $project)
    {
        $project->load(['client', 'service', 'milestones', 'invoices.payments']);
        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        $clients = Client::active()->get();
        $services = Service::active()->oneTime()->get();
        return view('projects.edit', compact('project', 'clients', 'services'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'service_id' => 'required|exists:services,id',
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'deadline' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:brief,dp_paid,on_progress,revision,waiting_client,completed',
            'progress_percentage' => 'nullable|integer|min:0|max:100',
            'description' => 'nullable|string',
        ]);

        $oldStatus = $project->status;
        $project->update($validated);

        // Update lifetime value when project completed
        if ($oldStatus !== 'completed' && $project->status === 'completed') {
            $project->client->recalculateLifetimeValue();
            Activity::log('completed', 'projects', "Project selesai: {$project->title}", $project);
        } else {
            Activity::log('updated', 'projects', "Project diupdate: {$project->title}", $project);
        }

        return redirect()->route('projects.index')->with('success', 'Project berhasil diupdate!');
    }

    public function destroy(Project $project)
    {
        try {
            Activity::log('deleted', 'projects', "Project dihapus: {$project->title}", $project);
            $project->delete();
            return redirect()->route('projects.index')->with('success', 'Project berhasil dihapus!');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('projects.index')->with('error', 'Tidak bisa menghapus project ini karena masih memiliki data terkait.');
        }
    }

    // Milestone management
    public function storeMilestone(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'required|date',
        ]);

        $milestone = $project->milestones()->create($validated);
        Activity::log('created', 'milestones', "Milestone baru: {$milestone->title}", $milestone);

        return redirect()->route('projects.show', $project)->with('success', 'Milestone berhasil ditambahkan!');
    }

    public function toggleMilestone(Project $project, ProjectMilestone $milestone)
    {
        $milestone->update([
            'status' => $milestone->status === 'pending' ? 'done' : 'pending',
        ]);

        // Recalculate project progress
        $project->update([
            'progress_percentage' => $project->calculateProgressFromMilestones(),
        ]);

        Activity::log('updated', 'milestones', "Milestone {$milestone->title}: {$milestone->status}", $milestone);

        return redirect()->route('projects.show', $project)->with('success', 'Milestone berhasil diupdate!');
    }

    public function destroyMilestone(Project $project, ProjectMilestone $milestone)
    {
        $milestone->delete();
        $project->update([
            'progress_percentage' => $project->calculateProgressFromMilestones(),
        ]);

        return redirect()->route('projects.show', $project)->with('success', 'Milestone berhasil dihapus!');
    }
}
