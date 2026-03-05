<?php

namespace App\Http\Controllers;

use App\Models\ClientRequirement;
use Illuminate\Http\Request;

class AdminRequirementController extends Controller
{
    public function index(Request $request)
    {
        $query = ClientRequirement::with('service', 'lead', 'company')
            ->latest();

        // Company scoping
        $user = auth()->user();
        if ($user->isSuperAdmin()) {
            if ($request->filled('company_id')) {
                $query->where('company_id', $request->company_id);
            }
        } else {
            $query->where('company_id', $user->company_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $requirements = $query->paginate(20);

        return view('requirements.index', compact('requirements'));
    }

    public function show(ClientRequirement $requirement)
    {
        $requirement->load('service', 'lead', 'company');

        // Mark as reviewed if still new
        if ($requirement->status === 'new') {
            $requirement->update(['status' => 'reviewed']);
        }

        return view('requirements.show', compact('requirement'));
    }

    public function convertToLead(ClientRequirement $requirement)
    {
        if ($requirement->isConverted()) {
            return back()->with('error', 'Requirement ini sudah dikonversi ke Lead.');
        }

        $lead = $requirement->convertToLead();

        return redirect()->route('leads.show', $lead)->with('success', 'Berhasil dikonversi menjadi Lead #' . $lead->id);
    }

    public function destroy(ClientRequirement $requirement)
    {
        $requirement->delete();

        return redirect()->route('admin.requirements.index')->with('success', 'Requirement berhasil dihapus.');
    }
}
