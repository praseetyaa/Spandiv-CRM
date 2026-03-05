@extends('layouts.app')
@section('title', 'Detail Subscription')
@section('content')
    <div class="">
        <div class="glass rounded-2xl p-8 mb-6">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $subscription->service->name }}</h3>
                    <p class="text-dark-400 text-sm">Client: {{ $subscription->client->name }}</p>
                </div>
                <a href="{{ route('subscriptions.index') }}"
                    class="px-4 py-2 text-gray-400 dark:text-gray-500 dark:text-dark-400 hover:text-gray-900 dark:hover:text-white text-sm transition-colors">← Kembali</a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-gray-100 dark:bg-dark-800/50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 dark:text-dark-400 mb-1">Harga/Bulan</p>
                    <p class="text-lg font-bold text-emerald-400">Rp {{ number_format($subscription->price, 0, ',', '.') }}
                    </p>
                </div>
                <div class="bg-gray-100 dark:bg-dark-800/50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 dark:text-dark-400 mb-1">Status</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ ucfirst($subscription->status) }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-dark-800/50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 dark:text-dark-400 mb-1">Mulai</p>
                    <p class="text-sm text-gray-900 dark:text-white">{{ $subscription->start_date->format('d M Y') }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-dark-800/50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 dark:text-dark-400 mb-1">Invoice Count</p>
                    <p class="text-sm text-gray-900 dark:text-white">{{ $subscription->invoices->count() }} invoice</p>
                </div>
            </div>
        </div>
        <div class="glass rounded-2xl p-6">
            <h4 class="font-semibold text-white mb-4">Invoice Terkait</h4>
            <div class="space-y-3">
                @forelse($subscription->invoices as $inv)
                    <div class="bg-gray-100 dark:bg-dark-800/50 rounded-xl p-4 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $inv->invoice_number }}</p>
                            <p class="text-xs text-gray-500 dark:text-dark-400">{{ $inv->issue_date->format('d M Y') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-emerald-400">Rp
                                {{ number_format($inv->total_amount, 0, ',', '.') }}</p>
                            @php $ic = ['paid' => 'emerald', 'partial' => 'amber', 'overdue' => 'red', 'sent' => 'blue', 'draft' => 'dark'][$inv->status] ?? 'dark'; @endphp<span
                                class="px-2 py-0.5 text-xs rounded bg-{{ $ic }}-500/10 text-{{ $ic }}-400">{{ ucfirst($inv->status) }}</span>
                        </div>
                    </div>
                @empty <p class="text-sm text-dark-400">Belum ada invoice</p>@endforelse
            </div>
        </div>
    </div>
@endsection