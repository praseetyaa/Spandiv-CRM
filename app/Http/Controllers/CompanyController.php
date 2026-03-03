<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Activity;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $query = Company::withCount('users');

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $companies = $query->latest()->paginate(15);
        return view('companies.index', compact('companies'));
    }

    public function create()
    {
        return view('companies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $company = Company::create($validated);
        Activity::log('created', 'companies', "Perusahaan baru: {$company->name}", $company);

        return redirect()->route('companies.index')->with('success', 'Perusahaan berhasil ditambahkan!');
    }

    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $company->update($validated);
        Activity::log('updated', 'companies', "Perusahaan diupdate: {$company->name}", $company);

        return redirect()->route('companies.index')->with('success', 'Perusahaan berhasil diupdate!');
    }

    public function destroy(Company $company)
    {
        $name = $company->name;
        $company->delete();
        Activity::log('deleted', 'companies', "Perusahaan dihapus: {$name}");

        return redirect()->route('companies.index')->with('success', 'Perusahaan berhasil dihapus!');
    }
}
