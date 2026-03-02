@extends('layouts.app')
@section('title', 'Detail Lead: ' . $lead->name)

@section('content')
    <div class="max-w-4xl">
        <div class="glass rounded-2xl p-8 mb-6">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-white">{{ $lead->name }}</h3>
                    <p class="text-dark-400 text-sm mt-1">{{ $lead->phone }} · {{ $lead->email ?? 'No email' }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('leads.edit', $lead) }}"
                        class="px-4 py-2 bg-dark-700 hover:bg-dark-600 text-white text-sm rounded-xl transition-colors">Edit</a>
                    <a href="{{ route('leads.index') }}"
                        class="px-4 py-2 text-dark-400 hover:text-white text-sm transition-colors">← Kembali</a>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-dark-800/50 rounded-xl p-4">
                    <p class="text-xs text-dark-400 mb-1">Status</p>
                    <p class="text-sm font-medium text-white">{{ ucfirst(str_replace('_', ' ', $lead->status)) }}</p>
                </div>
                <div class="bg-dark-800/50 rounded-xl p-4">
                    <p class="text-xs text-dark-400 mb-1">Urgency</p>
                    <p
                        class="text-sm font-medium text-{{ $lead->urgency_level == 'high' ? 'red' : ($lead->urgency_level == 'medium' ? 'amber' : 'emerald') }}-400">
                        {{ ucfirst($lead->urgency_level) }}</p>
                </div>
                <div class="bg-dark-800/50 rounded-xl p-4">
                    <p class="text-xs text-dark-400 mb-1">Lead Score</p>
                    <p class="text-sm font-medium text-white">{{ $lead->lead_score }}/100</p>
                </div>
                <div class="bg-dark-800/50 rounded-xl p-4">
                    <p class="text-xs text-dark-400 mb-1">Estimated Value</p>
                    <p class="text-sm font-medium text-emerald-400">
                        {{ $lead->estimated_value ? 'Rp ' . number_format($lead->estimated_value, 0, ',', '.') : '-' }}</p>
                </div>
                <div class="bg-dark-800/50 rounded-xl p-4">
                    <p class="text-xs text-dark-400 mb-1">Layanan</p>
                    <p class="text-sm font-medium text-white">{{ $lead->service->name ?? '-' }}</p>
                </div>
                <div class="bg-dark-800/50 rounded-xl p-4">
                    <p class="text-xs text-dark-400 mb-1">Sumber</p>
                    <p class="text-sm font-medium text-white">{{ ucfirst($lead->source) }}</p>
                </div>
                <div class="bg-dark-800/50 rounded-xl p-4">
                    <p class="text-xs text-dark-400 mb-1">Last Follow Up</p>
                    <p class="text-sm font-medium text-white">
                        {{ $lead->last_follow_up ? $lead->last_follow_up->format('d M Y H:i') : 'Belum' }}</p>
                </div>
                <div class="bg-dark-800/50 rounded-xl p-4">
                    <p class="text-xs text-dark-400 mb-1">Dibuat</p>
                    <p class="text-sm font-medium text-white">{{ $lead->created_at->format('d M Y') }}</p>
                </div>
            </div>

            @if($lead->notes)
                <div class="mt-6 bg-dark-800/50 rounded-xl p-4">
                    <p class="text-xs text-dark-400 mb-2">Catatan</p>
                    <p class="text-sm text-dark-200">{{ $lead->notes }}</p>
                </div>
            @endif

            @if($lead->client)
                <div class="mt-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl">
                    <p class="text-sm text-emerald-400">✓ Lead ini sudah dikonversi menjadi client: <a
                            href="{{ route('clients.show', $lead->client) }}"
                            class="font-medium underline">{{ $lead->client->name }}</a></p>
                </div>
            @endif
        </div>
    </div>
@endsection