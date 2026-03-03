@extends('layouts.app')
@section('title', 'Pembayaran')
@section('subtitle', 'Riwayat pembayaran')
@section('header-actions')
    <a href="{{ route('payments.create') }}"
        class="px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 text-white text-sm font-medium rounded-xl shadow-lg shadow-blue-500/20 transition-all flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>Catat Pembayaran</a>
@endsection
@section('content')
    <div class="glass rounded-2xl p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-3 items-center">
            <select name="method"
                class="px-4 py-2 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                <option value="">Semua Metode</option>
                @foreach(['transfer' => 'Transfer', 'cash' => 'Cash', 'e-wallet' => 'E-Wallet'] as $v => $l)
                <option value="{{ $v }}" {{ request('method') == $v ? 'selected' : '' }}>{{ $l }}</option>
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
                    <th class="text-left text-xs font-medium text-gray-500 dark:text-dark-400 uppercase px-6 py-4">Invoice</th>
                    <th class="text-left text-xs font-medium text-gray-500 dark:text-dark-400 uppercase px-6 py-4">Client</th>
                    <th class="text-left text-xs font-medium text-gray-500 dark:text-dark-400 uppercase px-6 py-4">Jumlah</th>
                    <th class="text-left text-xs font-medium text-gray-500 dark:text-dark-400 uppercase px-6 py-4">Metode</th>
                    <th class="text-left text-xs font-medium text-gray-500 dark:text-dark-400 uppercase px-6 py-4">Tanggal</th>
                    <th class="text-left text-xs font-medium text-gray-500 dark:text-dark-400 uppercase px-6 py-4">Catatan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-dark-700/30">
                @forelse($payments as $pay)
                    <tr class="table-row table-row-animated">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $pay->invoice?->invoice_number ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-dark-300">{{ $pay->invoice?->client?->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-emerald-400">Rp
                            {{ number_format($pay->amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            @php $mColor = ['transfer' => 'blue', 'cash' => 'emerald', 'e-wallet' => 'purple'][$pay->method] ?? 'dark'; @endphp
                            <span
                                class="px-2.5 py-1 text-xs rounded-lg bg-{{ $mColor }}-500/10 text-{{ $mColor }}-400">{{ ucfirst(str_replace('-', ' ', $pay->method)) }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-dark-300">{{ $pay->payment_date->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-sm text-dark-400">{{ $pay->notes ?? '-' }}</td>
                    </tr>
                @empty <tr>
                    <td colspan="6" class="text-center text-gray-500 dark:text-dark-400 py-8">Belum ada pembayaran</td>
                </tr>@endforelse
            </tbody>
        </table>
        @if($payments->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-dark-700/50">{{ $payments->withQueryString()->links() }}</div>@endif
    </div>
@endsection