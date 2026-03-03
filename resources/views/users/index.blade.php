@extends('layouts.app')
@section('title', 'User Management')
@section('subtitle', 'Kelola pengguna sistem')
@section('header-actions')
    <a href="{{ route('users.create') }}"
        class="px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 text-white text-sm font-medium rounded-xl shadow-lg shadow-blue-500/20 transition-all duration-300 flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Tambah User
    </a>
@endsection
@section('content')
    <div class="glass rounded-2xl p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-3 items-center">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..."
                class="flex-1 min-w-[200px] px-4 py-2 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm placeholder-gray-400 dark:placeholder-dark-500 focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
            <select name="role"
                class="px-4 py-2 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                <option value="">Semua Role</option>
                <option value="superadmin" {{ request('role') == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Staff</option>
            </select>
            @if(auth()->user()->isSuperAdmin())
                <select name="company_id"
                    class="px-4 py-2 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                    <option value="">Semua Perusahaan</option>
                    @foreach(\App\Models\Company::all() as $comp)
                        <option value="{{ $comp->id }}" {{ request('company_id') == $comp->id ? 'selected' : '' }}>{{ $comp->name }}
                        </option>
                    @endforeach
                </select>
            @endif
            <button type="submit"
                class="px-4 py-2 bg-gray-200 dark:bg-dark-700 hover:bg-gray-300 dark:hover:bg-dark-600 text-gray-700 dark:text-white text-sm rounded-xl transition-colors">Filter</button>
        </form>
    </div>
    <div class="glass rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-dark-700/50">
                        <th
                            class="text-left text-xs font-medium text-gray-500 dark:text-dark-400 uppercase tracking-wider px-6 py-4">
                            User</th>
                        <th
                            class="text-left text-xs font-medium text-gray-500 dark:text-dark-400 uppercase tracking-wider px-6 py-4">
                            Role</th>
                        <th
                            class="text-left text-xs font-medium text-gray-500 dark:text-dark-400 uppercase tracking-wider px-6 py-4">
                            Perusahaan</th>
                        <th
                            class="text-left text-xs font-medium text-gray-500 dark:text-dark-400 uppercase tracking-wider px-6 py-4">
                            Bergabung</th>
                        <th
                            class="text-right text-xs font-medium text-gray-500 dark:text-dark-400 uppercase tracking-wider px-6 py-4">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-dark-700/30">
                    @forelse($users as $u)
                        <tr class="table-row">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-9 h-9 bg-gradient-to-br from-emerald-400 to-cyan-500 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $u->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-dark-400">{{ $u->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $roleBg = ['superadmin' => 'purple', 'admin' => 'blue', 'staff' => 'gray'];
                                    $roleLabels = ['superadmin' => 'Super Admin', 'admin' => 'Admin', 'staff' => 'Staff'];
                                @endphp
                                <span
                                    class="badge px-2.5 py-1 rounded-lg text-xs font-medium bg-{{ $roleBg[$u->role] ?? 'gray' }}-500/10 text-{{ $roleBg[$u->role] ?? 'gray' }}-500 dark:text-{{ $roleBg[$u->role] ?? 'gray' }}-400">
                                    {{ $roleLabels[$u->role] ?? $u->role }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-dark-300">{{ $u->company->name ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-dark-300">{{ $u->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('users.edit', $u) }}"
                                        class="p-1.5 text-gray-400 dark:text-dark-400 hover:text-amber-400 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    @if($u->id !== auth()->id())
                                        <button type="button" onclick="confirmDelete('{{ route('users.destroy', $u) }}')"
                                            class="p-1.5 text-gray-400 dark:text-dark-400 hover:text-red-400 transition-colors"
                                            title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-gray-500 dark:text-dark-400 py-8">Belum ada user</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-dark-700/50">{{ $users->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection