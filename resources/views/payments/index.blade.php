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
    <div class="glass rounded-2xl overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="border-b border-dark-700/50">
                    <th class="text-left text-xs font-medium text-dark-400 uppercase px-6 py-4">Invoice</th>
                    <th class="text-left text-xs font-medium text-dark-400 uppercase px-6 py-4">Client</th>
                    <th class="text-left text-xs font-medium text-dark-400 uppercase px-6 py-4">Jumlah</th>
                    <th class="text-left text-xs font-medium text-dark-400 uppercase px-6 py-4">Metode</th>
                    <th class="text-left text-xs font-medium text-dark-400 uppercase px-6 py-4">Tanggal</th>
                    <th class="text-left text-xs font-medium text-dark-400 uppercase px-6 py-4">Catatan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark-700/30">
                @forelse($payments as $pay)
                    <tr class="table-row">
                        <td class="px-6 py-4 text-sm font-medium text-white">{{ $pay->invoice->invoice_number }}</td>
                        <td class="px-6 py-4 text-sm text-dark-300">{{ $pay->invoice->client->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-emerald-400">Rp
                            {{ number_format($pay->amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            @php $mColor = ['transfer' => 'blue', 'cash' => 'emerald', 'e-wallet' => 'purple'][$pay->method] ?? 'dark'; @endphp
                            <span
                                class="px-2.5 py-1 text-xs rounded-lg bg-{{ $mColor }}-500/10 text-{{ $mColor }}-400">{{ ucfirst(str_replace('-', ' ', $pay->method)) }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-dark-300">{{ $pay->payment_date->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-sm text-dark-400">{{ $pay->notes ?? '-' }}</td>
                    </tr>
                @empty <tr>
                    <td colspan="6" class="text-center text-dark-400 py-8">Belum ada pembayaran</td>
                </tr>@endforelse
            </tbody>
        </table>
        @if($payments->hasPages())
        <div class="px-6 py-4 border-t border-dark-700/50">{{ $payments->withQueryString()->links() }}</div>@endif
    </div>
@endsection