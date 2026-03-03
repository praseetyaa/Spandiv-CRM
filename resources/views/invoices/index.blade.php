@extends('layouts.app')
@section('title', 'Invoice')
@section('subtitle', 'Kelola invoice & penagihan')
@section('header-actions')
    <div class="flex gap-3">
        <form method="POST" action="{{ route('invoices.generate-recurring') }}">
            @csrf
            <button type="submit"
                class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-medium rounded-xl transition-colors flex items-center gap-2"
                onclick="return confirm('Generate invoice recurring untuk semua subscription aktif?')">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Generate Recurring</button>
        </form>
        <a href="{{ route('invoices.create') }}"
            class="px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 text-white text-sm font-medium rounded-xl shadow-lg shadow-blue-500/20 transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>Buat Invoice</a>
    </div>
@endsection
@section('content')
    <div class="glass rounded-2xl p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-3 items-center">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor invoice atau client..."
                class="flex-1 min-w-[200px] px-4 py-2 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm placeholder-gray-400 dark:placeholder-dark-500 focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
            <select name="status"
                class="px-4 py-2 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                <option value="">Semua Status</option>
                @foreach(['draft' => 'Draft', 'sent' => 'Sent', 'partial' => 'Partial', 'paid' => 'Paid', 'overdue' => 'Overdue'] as $v => $l)
                <option value="{{ $v }}" {{ request('status') == $v ? 'selected' : '' }}>{{ $l }}</option>@endforeach
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
                    <th class="text-left text-xs font-medium text-gray-500 dark:text-dark-400 uppercase px-6 py-4">Invoice
                    </th>
                    <th class="text-left text-xs font-medium text-gray-500 dark:text-dark-400 uppercase px-6 py-4">Client
                    </th>
                    <th class="text-left text-xs font-medium text-gray-500 dark:text-dark-400 uppercase px-6 py-4">Tipe</th>
                    <th class="text-left text-xs font-medium text-gray-500 dark:text-dark-400 uppercase px-6 py-4">Total
                    </th>
                    <th class="text-left text-xs font-medium text-gray-500 dark:text-dark-400 uppercase px-6 py-4">Terbayar
                    </th>
                    <th class="text-left text-xs font-medium text-gray-500 dark:text-dark-400 uppercase px-6 py-4">Status
                    </th>
                    <th class="text-left text-xs font-medium text-gray-500 dark:text-dark-400 uppercase px-6 py-4">Due</th>
                    <th class="text-right text-xs font-medium text-gray-500 dark:text-dark-400 uppercase px-6 py-4">Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-dark-700/30">
                @forelse($invoices as $inv)
                    @php $ic = ['paid' => 'emerald', 'partial' => 'amber', 'overdue' => 'red', 'sent' => 'blue', 'draft' => 'dark'][$inv->status] ?? 'dark'; @endphp
                    <tr class="table-row">
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $inv->invoice_number }}</p>
                            <p class="text-xs text-gray-500 dark:text-dark-400">{{ $inv->issue_date->format('d M Y') }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-dark-300">{{ $inv->client?->name ?? '-' }}</td>
                        <td class="px-6 py-4"><span
                                class="text-xs {{ $inv->subscription_id ? 'text-cyan-400' : 'text-dark-400' }}">{{ $inv->subscription_id ? '🔁 Recurring' : '📋 One Time' }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white font-medium">Rp
                            {{ number_format($inv->total_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-emerald-400">Rp {{ number_format($inv->paid_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4"><span
                                class="px-2.5 py-1 text-xs rounded-lg bg-{{ $ic }}-500/10 text-{{ $ic }}-400 border border-{{ $ic }}-500/20">{{ ucfirst($inv->status) }}</span>
                        </td>
                        <td
                            class="px-6 py-4 text-sm {{ $inv->due_date < now() && $inv->status !== 'paid' ? 'text-red-400' : 'text-dark-300' }}">
                            {{ $inv->due_date->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('invoices.show', $inv) }}"
                                    class="p-1.5 text-gray-400 dark:text-dark-400 hover:text-blue-400"><svg class="w-4 h-4"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg></a>
                                @if($inv->status !== 'paid')<a href="{{ route('payments.create', ['invoice_id' => $inv->id]) }}"
                                class="px-3 py-1.5 text-xs rounded-lg bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500/20 transition-colors">Bayar</a>@endif
                                <button type="button" onclick="confirmDelete('{{ route('invoices.destroy', $inv) }}')"
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
                    <td colspan="8" class="text-center text-gray-500 dark:text-dark-400 py-8">Belum ada invoice</td>
                </tr>@endforelse
            </tbody>
        </table>
        @if($invoices->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-dark-700/50">{{ $invoices->withQueryString()->links() }}
        </div>@endif
    </div>
@endsection