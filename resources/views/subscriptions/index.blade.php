@extends('layouts.app')
@section('title', 'Subscription')
@section('subtitle', 'Kelola langganan bulanan')
@section('header-actions')
    <a href="{{ route('subscriptions.create') }}"
        class="px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 text-white text-sm font-medium rounded-xl shadow-lg shadow-blue-500/20 transition-all flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>Tambah Subscription</a>
@endsection
@section('content')
    <div class="glass rounded-2xl p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-3 items-center">
            <select name="status"
                class="px-4 py-2 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                <option value="">Semua Status</option>
                @foreach(['active' => 'Active', 'paused' => 'Paused', 'cancelled' => 'Cancelled'] as $v => $l)
                    <option value="{{ $v }}" {{ request('status') == $v ? 'selected' : '' }}>{{ $l }}</option>
                @endforeach
            </select>
            @include('partials.company-filter')
            <button type="submit"
                class="px-4 py-2 bg-gray-200 dark:bg-dark-700 hover:bg-gray-300 dark:hover:bg-dark-600 text-gray-700 dark:text-white text-sm rounded-xl transition-colors">Filter</button>
        </form>
    </div>
    <div class="glass rounded-2xl overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200 dark:border-dark-700/50">
                    <th class="text-left text-xs font-medium text-gray-500 dark:text-dark-400 uppercase px-6 py-4">Client
                    </th>
                    <th class="text-left text-xs font-medium text-gray-500 dark:text-dark-400 uppercase px-6 py-4">Layanan
                    </th>
                    <th class="text-left text-xs font-medium text-gray-500 dark:text-dark-400 uppercase px-6 py-4">
                        Harga/Bulan</th>
                    <th class="text-left text-xs font-medium text-gray-500 dark:text-dark-400 uppercase px-6 py-4">Mulai
                    </th>
                    <th class="text-left text-xs font-medium text-gray-500 dark:text-dark-400 uppercase px-6 py-4">Status
                    </th>
                    <th class="text-right text-xs font-medium text-gray-500 dark:text-dark-400 uppercase px-6 py-4">Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-dark-700/30">
                @forelse($subscriptions as $sub)
                    @php $subColor = ['active' => 'emerald', 'paused' => 'amber', 'cancelled' => 'red'][$sub->status]; @endphp
                    <tr class="table-row">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $sub->client?->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-dark-300">{{ $sub->service?->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-emerald-400 font-medium">Rp
                            {{ number_format($sub->price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-dark-300">{{ $sub->start_date->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4"><span
                                class="px-2.5 py-1 text-xs rounded-lg bg-{{ $subColor }}-500/10 text-{{ $subColor }}-400">{{ ucfirst($sub->status) }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <form method="POST" action="{{ route('subscriptions.toggle-pause', $sub) }}">@csrf
                                    @method('PATCH')
                                    <button
                                        class="px-3 py-1.5 text-xs rounded-lg {{ $sub->status == 'active' ? 'bg-amber-500/10 text-amber-400 hover:bg-amber-500/20' : 'bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500/20' }} transition-colors">{{ $sub->status == 'active' ? 'Pause' : 'Resume' }}</button>
                                </form>
                                <a href="{{ route('subscriptions.edit', $sub) }}"
                                    class="p-1.5 text-gray-400 dark:text-dark-400 hover:text-amber-400"><svg class="w-4 h-4"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg></a>
                                <button type="button" onclick="confirmDelete('{{ route('subscriptions.destroy', $sub) }}')"
                                    class="p-1.5 text-gray-400 dark:text-dark-400 hover:text-red-400 transition-colors"
                                    title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty <tr>
                    <td colspan="6" class="text-center text-gray-500 dark:text-dark-400 py-8">Belum ada subscription</td>
                </tr>@endforelse
            </tbody>
        </table>
        @if($subscriptions->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-dark-700/50">
        {{ $subscriptions->withQueryString()->links() }}</div>@endif
    </div>
@endsection