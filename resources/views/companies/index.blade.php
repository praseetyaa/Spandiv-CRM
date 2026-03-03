@extends('layouts.app')
@section('title', 'Perusahaan')
@section('subtitle', 'Kelola data perusahaan')
@section('header-actions')
    <a href="{{ route('companies.create') }}"
        class="px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 text-white text-sm font-medium rounded-xl shadow-lg shadow-blue-500/20 transition-all duration-300 flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Tambah Perusahaan
    </a>
@endsection
@section('content')
    <div class="glass rounded-2xl p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-3 items-center">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama perusahaan..."
                class="flex-1 min-w-[200px] px-4 py-2 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm placeholder-gray-400 dark:placeholder-dark-500 focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
            <button type="submit"
                class="px-4 py-2 bg-gray-200 dark:bg-dark-700 hover:bg-gray-300 dark:hover:bg-dark-600 text-gray-700 dark:text-white text-sm rounded-xl transition-colors">Filter</button>
        </form>
    </div>
    <div class="glass rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-dark-700/50">
                        <th class="text-left text-xs font-medium text-gray-500 dark:text-dark-400 uppercase tracking-wider px-6 py-4">Perusahaan</th>
                        <th class="text-left text-xs font-medium text-gray-500 dark:text-dark-400 uppercase tracking-wider px-6 py-4">Kontak</th>
                        <th class="text-left text-xs font-medium text-gray-500 dark:text-dark-400 uppercase tracking-wider px-6 py-4">Users</th>
                        <th class="text-left text-xs font-medium text-gray-500 dark:text-dark-400 uppercase tracking-wider px-6 py-4">Dibuat</th>
                        <th class="text-right text-xs font-medium text-gray-500 dark:text-dark-400 uppercase tracking-wider px-6 py-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-dark-700/30">
                    @forelse($companies as $company)
                        <tr class="table-row">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-500 rounded-xl flex items-center justify-center text-white font-bold text-sm">
                                        {{ strtoupper(substr($company->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $company->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-dark-400">{{ $company->slug }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-600 dark:text-dark-300">{{ $company->email ?? '-' }}</p>
                                <p class="text-xs text-gray-500 dark:text-dark-400">{{ $company->phone ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="badge px-2.5 py-1 rounded-lg text-xs font-medium bg-blue-500/10 text-blue-500 dark:text-blue-400">{{ $company->users_count }} user</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-dark-300">{{ $company->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('companies.edit', $company) }}" class="p-1.5 text-gray-400 dark:text-dark-400 hover:text-amber-400 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </a>
                                    <button type="button" onclick="confirmDelete('{{ route('companies.destroy', $company) }}')" class="p-1.5 text-gray-400 dark:text-dark-400 hover:text-red-400 transition-colors" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-gray-500 dark:text-dark-400 py-8">Belum ada perusahaan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($companies->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-dark-700/50">{{ $companies->withQueryString()->links() }}</div>
        @endif
    </div>
@endsection
