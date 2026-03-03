@extends('layouts.app')
@section('title', 'Detail Client: ' . $client->name)
@section('content')
    <div class="max-w-5xl">
        <div class="glass rounded-2xl p-8 mb-6">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $client->name }}</h3>
                    <p class="text-dark-400 text-sm">{{ $client->business_name }} · {{ $client->industry }}</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('clients.edit', $client) }}"
                        class="px-4 py-2 bg-gray-200 dark:bg-dark-700 hover:bg-gray-300 dark:hover:bg-dark-600 text-gray-700 dark:text-white text-sm rounded-xl transition-colors">Edit</a>
                    <a href="{{ route('clients.index') }}"
                        class="px-4 py-2 text-gray-400 dark:text-gray-500 dark:text-dark-400 hover:text-gray-900 dark:hover:text-white text-sm transition-colors">← Kembali</a>
                </div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-gray-100 dark:bg-dark-800/50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 dark:text-dark-400 mb-1">Email</p>
                    <p class="text-sm text-gray-900 dark:text-white">{{ $client->email }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-dark-800/50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 dark:text-dark-400 mb-1">Telepon</p>
                    <p class="text-sm text-gray-900 dark:text-white">{{ $client->phone }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-dark-800/50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 dark:text-dark-400 mb-1">Instagram</p>
                    <p class="text-sm text-gray-900 dark:text-white">{{ $client->instagram ?? '-' }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-dark-800/50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 dark:text-dark-400 mb-1">Lifetime Value</p>
                    <p class="text-sm font-semibold text-emerald-400">Rp
                        {{ number_format($client->total_lifetime_value, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Projects -->
        <div class="glass rounded-2xl p-6 mb-6">
            <h4 class="font-semibold text-white mb-4">Project ({{ $client->projects->count() }})</h4>
            <div class="space-y-3">
                @forelse($client->projects as $project)
                    <div class="bg-gray-100 dark:bg-dark-800/50 rounded-xl p-4 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $project->title }}</p>
                            <p class="text-xs text-gray-500 dark:text-dark-400">{{ $project->service->name }} · Rp
                                {{ number_format($project->price, 0, ',', '.') }}</p>
                        </div>
                        <span
                            class="px-2.5 py-1 text-xs rounded-lg bg-blue-500/10 text-blue-400">{{ ucfirst(str_replace('_', ' ', $project->status)) }}</span>
                    </div>
                @empty
                    <p class="text-sm text-dark-400">Belum ada project</p>
                @endforelse
            </div>
        </div>

        <!-- Invoices -->
        <div class="glass rounded-2xl p-6">
            <h4 class="font-semibold text-white mb-4">Invoice ({{ $client->invoices->count() }})</h4>
            <div class="space-y-3">
                @forelse($client->invoices as $invoice)
                    <div class="bg-gray-100 dark:bg-dark-800/50 rounded-xl p-4 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $invoice->invoice_number }}</p>
                            <p class="text-xs text-gray-500 dark:text-dark-400">{{ $invoice->issue_date->format('d M Y') }} · Rp
                                {{ number_format($invoice->total_amount, 0, ',', '.') }}</p>
                        </div>
                        @php $invColor = ['paid' => 'emerald', 'partial' => 'amber', 'overdue' => 'red', 'sent' => 'blue', 'draft' => 'dark'][$invoice->status] ?? 'dark'; @endphp
                        <span
                            class="px-2.5 py-1 text-xs rounded-lg bg-{{ $invColor }}-500/10 text-{{ $invColor }}-400">{{ ucfirst($invoice->status) }}</span>
                    </div>
                @empty
                    <p class="text-sm text-dark-400">Belum ada invoice</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection