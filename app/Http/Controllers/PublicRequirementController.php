<?php

namespace App\Http\Controllers;

use App\Models\ClientRequirement;
use App\Models\Company;
use App\Models\RequirementField;
use App\Models\Service;
use Illuminate\Http\Request;

class PublicRequirementController extends Controller
{
    /**
     * Show the public requirement form for a specific company.
     */
    public function showForm(string $formSlug)
    {
        $company = Company::where('form_slug', $formSlug)->firstOrFail();
        $services = Service::where('company_id', $company->id)->active()->orderBy('category')->orderBy('name')->get();

        return view('public.requirement-form', compact('company', 'services', 'formSlug'));
    }

    /**
     * Return fields for a given service category (AJAX).
     */
    public function getFieldsByCategory(string $formSlug, string $category)
    {
        $company = Company::where('form_slug', $formSlug)->firstOrFail();

        $fields = RequirementField::where(function ($q) use ($company) {
            $q->where('company_id', $company->id)->orWhereNull('company_id');
        })
            ->active()
            ->forCategory($category)
            ->ordered()
            ->get();

        return response()->json($fields);
    }

    /**
     * Handle form submission.
     */
    public function submit(Request $request, string $formSlug)
    {
        $company = Company::where('form_slug', $formSlug)->firstOrFail();

        // ── Honeypot check ──────────────────────
        if ($request->filled('website_url_confirm')) {
            return redirect()->route('requirements.thank-you', $formSlug);
        }

        // ── Validate base fields ────────────────
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'phone' => 'required|string|max:30',
            'email' => 'nullable|email|max:255',
            'service_id' => 'required|exists:services,id',
            'budget_range' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:2000',
        ]);

        // ── Sanitize text inputs ────────────────
        $validated['name'] = strip_tags(trim($validated['name']));
        $validated['company_name'] = $validated['company_name'] ? strip_tags(trim($validated['company_name'])) : null;
        $validated['phone'] = strip_tags(trim($validated['phone']));
        $validated['email'] = $validated['email'] ? strip_tags(trim($validated['email'])) : null;
        $validated['notes'] = $validated['notes'] ? strip_tags(trim($validated['notes'])) : null;

        // ── Collect dynamic requirement fields ──
        $service = Service::findOrFail($validated['service_id']);

        $fields = RequirementField::where(function ($q) use ($company) {
            $q->where('company_id', $company->id)->orWhereNull('company_id');
        })
            ->active()
            ->forCategory($service->category)
            ->ordered()
            ->get();

        $requirements = [];

        foreach ($fields as $field) {
            $value = $request->input('req_' . $field->field_name);

            if ($field->is_required && empty($value)) {
                return back()->withInput()->withErrors([
                    'req_' . $field->field_name => "{$field->field_label} wajib diisi."
                ]);
            }

            if ($value !== null) {
                if (is_array($value)) {
                    $value = array_map(fn($v) => strip_tags(trim($v)), $value);
                } else {
                    $value = strip_tags(trim($value));
                }
                $requirements[$field->field_name] = $value;
            }
        }

        // ── Store ───────────────────────────────
        $clientReq = ClientRequirement::create([
            'company_id' => $company->id,
            'name' => $validated['name'],
            'company_name' => $validated['company_name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'service_id' => $validated['service_id'],
            'budget_range' => $validated['budget_range'],
            'requirements' => $requirements,
            'notes' => $validated['notes'],
            'status' => 'new',
            'ip_address' => $request->ip(),
        ]);

        // ── Auto-create Lead ────────────────────
        $clientReq->convertToLead();

        return redirect()->route('requirements.thank-you', $formSlug);
    }

    /**
     * Show thank you page after submission.
     */
    public function thankYou(string $formSlug)
    {
        $company = Company::where('form_slug', $formSlug)->firstOrFail();

        return view('public.thank-you', compact('company', 'formSlug'));
    }
}
