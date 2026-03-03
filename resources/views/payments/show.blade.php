@extends('layouts.app')
@section('title', 'Detail Pembayaran')
@section('content')
    <div class="max-w-2xl">
        <div class="glass rounded-2xl p-8">
            <a href="{{ route('payments.index') }}"
                class="text-gray-400 dark:text-gray-500 dark:text-dark-400 hover:text-gray-900 dark:hover:text-white text-sm transition-colors mb-4 inline-block">← Kembali</a>
            <div class="space-y-4">
                <div class="bg-gray-100 dark:bg-dark-800/50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 dark:text-dark-400 mb-1">Invoice</p>
                    <p class="text-sm text-gray-900 dark:text-white">{{ $payment->invoice->invoice_number }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-dark-800/50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 dark:text-dark-400 mb-1">Client</p>
                    <p class="text-sm text-gray-900 dark:text-white">{{ $payment->invoice->client->name }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-dark-800/50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 dark:text-dark-400 mb-1">Jumlah</p>
                    <p class="text-lg font-bold text-emerald-400">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-dark-800/50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 dark:text-dark-400 mb-1">Metode</p>
                    <p class="text-sm text-gray-900 dark:text-white">{{ ucfirst(str_replace('-', ' ', $payment->method)) }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-dark-800/50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 dark:text-dark-400 mb-1">Tanggal</p>
                    <p class="text-sm text-gray-900 dark:text-white">{{ $payment->payment_date->format('d M Y') }}</p>
                </div>
                @if($payment->notes)
                    <div class="bg-gray-100 dark:bg-dark-800/50 rounded-xl p-4">
                        <p class="text-xs text-gray-500 dark:text-dark-400 mb-1">Catatan</p>
                        <p class="text-sm text-gray-900 dark:text-white">{{ $payment->notes }}</p>
                </div>@endif
            </div>
        </div>
    </div>
@endsection