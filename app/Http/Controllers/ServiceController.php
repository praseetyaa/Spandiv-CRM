<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Activity;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::withCount(['leads', 'projects', 'subscriptions']);

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $services = $query->latest()->paginate(15);

        return view('services.index', compact('services'));
    }

    public function create()
    {
        return view('services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:website,branding,social_media,invitation',
            'base_price' => 'required|numeric|min:0',
            'billing_type' => 'required|in:one_time,recurring',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $service = Service::create($validated);
        Activity::log('created', 'services', "Service baru: {$service->name}", $service);

        return redirect()->route('services.index')->with('success', 'Service berhasil ditambahkan!');
    }

    public function edit(Service $service)
    {
        return view('services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:website,branding,social_media,invitation',
            'base_price' => 'required|numeric|min:0',
            'billing_type' => 'required|in:one_time,recurring',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $service->update($validated);
        Activity::log('updated', 'services', "Service diupdate: {$service->name}", $service);

        return redirect()->route('services.index')->with('success', 'Service berhasil diupdate!');
    }

    public function destroy(Service $service)
    {
        try {
            Activity::log('deleted', 'services', "Service dihapus: {$service->name}", $service);
            $service->delete();
            return redirect()->route('services.index')->with('success', 'Service berhasil dihapus!');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('services.index')->with('error', 'Tidak bisa menghapus layanan ini karena masih digunakan oleh lead, project, atau subscription.');
        }
    }
}
