<?php

namespace App\Http\Controllers;

use App\Models\RequirementField;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RequirementFieldController extends Controller
{
    public function index(Request $request)
    {
        $query = RequirementField::ordered();

        // Company scoping
        $user = auth()->user();
        if ($user->isSuperAdmin()) {
            if ($request->filled('company_id')) {
                $query->where('company_id', $request->company_id);
            }
        } else {
            $query->where(function ($q) use ($user) {
                $q->where('company_id', $user->company_id)->orWhereNull('company_id');
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('service_category', $request->category);
        }

        $fields = $query->get()->groupBy('service_category');

        // Get dynamic categories from services
        $categories = $this->getServiceCategories();

        return view('requirement-fields.index', compact('fields', 'categories'));
    }

    public function create()
    {
        $categories = $this->getServiceCategories();

        return view('requirement-fields.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_category' => 'required|string|max:50',
            'field_label' => 'required|string|max:255',
            'field_type' => 'required|in:text,textarea,select,checkbox,radio,date,number',
            'field_options' => 'nullable|string',
            'placeholder' => 'nullable|string|max:255',
            'is_required' => 'boolean',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['field_name'] = Str::slug($validated['field_label'], '_');
        $validated['is_required'] = $request->boolean('is_required');
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        // Parse options from comma-separated text
        if (!empty($validated['field_options'])) {
            $validated['field_options'] = array_map('trim', explode(',', $validated['field_options']));
        } else {
            $validated['field_options'] = null;
        }

        // company_id is auto-set by BelongsToCompany trait
        RequirementField::create($validated);

        return redirect()->route('requirement-fields.index')->with('success', 'Field berhasil ditambahkan!');
    }

    public function edit(RequirementField $requirement_field)
    {
        $categories = $this->getServiceCategories();

        return view('requirement-fields.edit', ['field' => $requirement_field, 'categories' => $categories]);
    }

    public function update(Request $request, RequirementField $requirement_field)
    {
        $validated = $request->validate([
            'service_category' => 'required|string|max:50',
            'field_label' => 'required|string|max:255',
            'field_type' => 'required|in:text,textarea,select,checkbox,radio,date,number',
            'field_options' => 'nullable|string',
            'placeholder' => 'nullable|string|max:255',
            'is_required' => 'boolean',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['field_name'] = Str::slug($validated['field_label'], '_');
        $validated['is_required'] = $request->boolean('is_required');
        $validated['is_active'] = $request->boolean('is_active', true);

        if (!empty($validated['field_options'])) {
            $validated['field_options'] = array_map('trim', explode(',', $validated['field_options']));
        } else {
            $validated['field_options'] = null;
        }

        $requirement_field->update($validated);

        return redirect()->route('requirement-fields.index')->with('success', 'Field berhasil diupdate!');
    }

    public function destroy(RequirementField $requirement_field)
    {
        $requirement_field->delete();

        return redirect()->route('requirement-fields.index')->with('success', 'Field berhasil dihapus!');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:requirement_fields,id',
        ]);

        foreach ($request->order as $index => $id) {
            RequirementField::where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Get unique service categories from the services table.
     */
    private function getServiceCategories(): array
    {
        return Service::query()
            ->select('category')
            ->distinct()
            ->pluck('category')
            ->mapWithKeys(function ($cat) {
                $icons = ['website' => '🌐', 'branding' => '🎨', 'social_media' => '📱', 'invitation' => '💌'];
                $icon = $icons[$cat] ?? '📦';
                $label = $icon . ' ' . ucfirst(str_replace('_', ' ', $cat));
                return [$cat => $label];
            })
            ->toArray();
    }
}
