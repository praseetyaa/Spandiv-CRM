@extends('layouts.app')
@section('title', 'Client Management')
@section('subtitle', 'Kelola data client Anda')
@section('header-actions')
    <a href="{{ route('clients.create') }}"
        class="px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 text-white text-sm font-medium rounded-xl shadow-lg shadow-blue-500/20 transition-all duration-300 flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Tambah Client
    </a>
@endsection
@section('content')
    <div class="glass rounded-2xl p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-3 items-center">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, bisnis, email..."
                class="flex-1 min-w-[200px] px-4 py-2 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm placeholder-gray-400 dark:placeholder-dark-500 focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
            <select name="status"
                class="px-4 py-2 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inaktif</option>
            </select>
            @include('partials.company-filter')
            <button type="submit"
                class="px-4 py-2 bg-gray-200 dark:bg-dark-700 hover:bg-gray-300 dark:hover:bg-dark-600 text-gray-700 dark:text-white text-sm rounded-xl transition-colors">Filter</button>
        </form>
    </div>
    <div class="glass rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-dark-700/50">
                        <th class="text-left text-xs font-medium text-gray-500 dark:text-dark-400 uppercase tracking-wider px-6 py-4">Client
                        </th>
                        <th class="text-left text-xs font-medium text-gray-500 dark:text-dark-400 uppercase tracking-wider px-6 py-4">Bisnis
                        </th>
                        <th class="text-left text-xs font-medium text-gray-500 dark:text-dark-400 uppercase tracking-wider px-6 py-4">Industry
                        </th>
                        <th class="text-left text-xs font-medium text-gray-500 dark:text-dark-400 uppercase tracking-wider px-6 py-4">Projects
                        </th>
                        <th class="text-left text-xs font-medium text-gray-500 dark:text-dark-400 uppercase tracking-wider px-6 py-4">Lifetime
                            Value</th>
                        <th class="text-left text-xs font-medium text-gray-500 dark:text-dark-400 uppercase tracking-wider px-6 py-4">Status
                        </th>
                        <th class="text-right text-xs font-medium text-gray-500 dark:text-dark-400 uppercase tracking-wider px-6 py-4">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-dark-700/30">
                    @forelse($clients as $client)
                        <tr class="table-row">
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $client->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-dark-400">{{ $client->email }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-dark-300">{{ $client->business_name }}</td>
                            <td class="px-6 py-4"><span
                                    class="badge px-2.5 py-1 rounded-lg text-xs font-medium bg-blue-500/10 text-blue-400 border border-blue-500/20">{{ $client->industry }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-dark-300">{{ $client->projects_count ?? 0 }} project</td>
                            <td class="px-6 py-4 text-sm font-medium text-emerald-400">Rp
                                {{ number_format($client->total_lifetime_value, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="badge px-2.5 py-1 rounded-lg text-xs font-medium {{ $client->client_status == 'active' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-gray-200 dark:bg-dark-600 text-gray-500 dark:text-dark-400' }}">{{ ucfirst($client->client_status) }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if($client->phone)
                                    <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $client->phone)) }}" target="_blank"
                                        class="p-1.5 text-gray-400 dark:text-dark-400 hover:text-emerald-400 transition-colors" title="WhatsApp">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                    </a>
                                    @endif
                                    <a href="{{ route('clients.show', $client) }}"
                                        class="p-1.5 text-gray-400 dark:text-dark-400 hover:text-blue-400 transition-colors"><svg class="w-4 h-4"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg></a>
                                    <a href="{{ route('clients.edit', $client) }}"
                                        class="p-1.5 text-gray-400 dark:text-dark-400 hover:text-amber-400 transition-colors"><svg class="w-4 h-4"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg></a>
                                    <button type="button" onclick="confirmDelete('{{ route('clients.destroy', $client) }}')"
                                        class="p-1.5 text-gray-400 dark:text-dark-400 hover:text-red-400 transition-colors" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-gray-500 dark:text-dark-400 py-8">Belum ada client</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($clients->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-dark-700/50">{{ $clients->withQueryString()->links() }}</div> @endif
    </div>
@endsection