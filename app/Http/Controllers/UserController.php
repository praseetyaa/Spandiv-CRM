<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = User::with('company');

        // Admin can only see users from their company
        if ($user->isAdmin()) {
            $query->where('company_id', $user->company_id);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('company_id') && $user->isSuperAdmin()) {
            $query->where('company_id', $request->company_id);
        }

        $users = $query->latest()->paginate(15);
        $companies = $user->isSuperAdmin() ? Company::all() : collect();

        return view('users.index', compact('users', 'companies'));
    }

    public function create()
    {
        $user = auth()->user();
        $companies = $user->isSuperAdmin() ? Company::all() : collect();
        $roles = $this->getAvailableRoles($user);

        return view('users.create', compact('companies', 'roles'));
    }

    public function store(Request $request)
    {
        $authUser = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => ['required', Rule::in(array_keys($this->getAvailableRoles($authUser)))],
            'company_id' => 'nullable|exists:companies,id',
        ]);

        // Admin can only create users for their own company
        if ($authUser->isAdmin()) {
            $validated['company_id'] = $authUser->company_id;
        }

        $validated['password'] = Hash::make($validated['password']);

        $newUser = User::create($validated);
        Activity::log('created', 'users', "User baru: {$newUser->name} ({$newUser->role})", $newUser);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function edit(User $user)
    {
        $authUser = auth()->user();

        // Admin can only edit users from their company
        if ($authUser->isAdmin() && $user->company_id !== $authUser->company_id) {
            abort(403);
        }

        $companies = $authUser->isSuperAdmin() ? Company::all() : collect();
        $roles = $this->getAvailableRoles($authUser);

        return view('users.edit', compact('user', 'companies', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $authUser = auth()->user();

        if ($authUser->isAdmin() && $user->company_id !== $authUser->company_id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:6',
            'role' => ['required', Rule::in(array_keys($this->getAvailableRoles($authUser)))],
            'company_id' => 'nullable|exists:companies,id',
        ]);

        if ($authUser->isAdmin()) {
            $validated['company_id'] = $authUser->company_id;
        }

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);
        Activity::log('updated', 'users', "User diupdate: {$user->name}", $user);

        return redirect()->route('users.index')->with('success', 'User berhasil diupdate!');
    }

    public function destroy(User $user)
    {
        $authUser = auth()->user();

        // Cannot delete yourself
        if ($user->id === $authUser->id) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri!');
        }

        if ($authUser->isAdmin() && $user->company_id !== $authUser->company_id) {
            abort(403);
        }

        $name = $user->name;
        $user->delete();
        Activity::log('deleted', 'users', "User dihapus: {$name}");

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
    }

    private function getAvailableRoles(User $authUser): array
    {
        if ($authUser->isSuperAdmin()) {
            return ['superadmin' => 'Super Admin', 'admin' => 'Admin Perusahaan', 'staff' => 'Staff'];
        }
        return ['admin' => 'Admin', 'staff' => 'Staff'];
    }
}
