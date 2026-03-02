@extends('layouts.app')
@section('title', 'Lead Management')
@section('subtitle', 'Kelola pipeline lead Anda')

@section('header-actions')
    <a href="{{ route('leads.create') }}"
        class="px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 text-white text-sm font-medium rounded-xl shadow-lg shadow-blue-500/20 transition-all duration-300 flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Tambah Lead
    </a>
@endsection

@section('content')
    <!-- Pipeline Stats -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
        @php
            $statusLabels = ['new' => 'Baru', 'contacted' => 'Dihubungi', 'proposal_sent' => 'Proposal', 'negotiation' => 'Negosiasi', 'closed_won' => 'Won ✓', 'closed_lost' => 'Lost ✗'];
            $statusColors = ['new' => 'blue', 'contacted' => 'cyan', 'proposal_sent' => 'amber', 'negotiation' => 'purple', 'closed_won' => 'emerald', 'closed_lost' => 'red'];
        @endphp
        @foreach($pipelineStats as $status => $count)
            <a href="{{ route('leads.index', ['status' => $status]) }}"
                class="glass rounded-xl p-4 text-center hover:bg-{{ $statusColors[$status] }}-500/10 transition-all group">
                <p class="text-2xl font-bold text-{{ $statusColors[$status] }}-400">{{ $count }}</p>
                <p class="text-xs text-dark-400 mt-1 group-hover:text-white transition-colors">{{ $statusLabels[$status] }}</p>
            </a>
        @endforeach
    </div>

    <!-- Filters -->
    <div class="glass rounded-2xl p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-3 items-center">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, telepon, email..."
                class="flex-1 min-w-[200px] px-4 py-2 bg-dark-800/50 border border-dark-600/50 rounded-xl text-white text-sm placeholder-dark-500 focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
            <select name="status"
                class="px-4 py-2 bg-dark-800/50 border border-dark-600/50 rounded-xl text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                <option value="">Semua Status</option>
                @foreach($statusLabels as $val => $label)
                    <option value="{{ $val }}" {{ request('status') == $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <select name="urgency"
                class="px-4 py-2 bg-dark-800/50 border border-dark-600/50 rounded-xl text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                <option value="">Semua Urgency</option>
                <option value="low" {{ request('urgency') == 'low' ? 'selected' : '' }}>Low</option>
                <option value="medium" {{ request('urgency') == 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="high" {{ request('urgency') == 'high' ? 'selected' : '' }}>High</option>
            </select>
            <button type="submit"
                class="px-4 py-2 bg-dark-700 hover:bg-dark-600 text-white text-sm rounded-xl transition-colors">Filter</button>
            <a href="{{ route('leads.index') }}"
                class="px-4 py-2 text-dark-400 hover:text-white text-sm transition-colors">Reset</a>
        </form>
    </div>

    <!-- Table -->
    <div class="glass rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-dark-700/50">
                        <th class="text-left text-xs font-medium text-dark-400 uppercase tracking-wider px-6 py-4">Lead</th>
                        <th class="text-left text-xs font-medium text-dark-400 uppercase tracking-wider px-6 py-4">Layanan
                        </th>
                        <th class="text-left text-xs font-medium text-dark-400 uppercase tracking-wider px-6 py-4">Status
                        </th>
                        <th class="text-left text-xs font-medium text-dark-400 uppercase tracking-wider px-6 py-4">Urgency
                        </th>
                        <th class="text-left text-xs font-medium text-dark-400 uppercase tracking-wider px-6 py-4">Score
                        </th>
                        <th class="text-left text-xs font-medium text-dark-400 uppercase tracking-wider px-6 py-4">Est.
                            Value</th>
                        <th class="text-right text-xs font-medium text-dark-400 uppercase tracking-wider px-6 py-4">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dark-700/30">
                    @forelse($leads as $lead)
                        <tr class="table-row">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="text-sm font-medium text-white">{{ $lead->name }}</p>
                                    <p class="text-xs text-dark-400">{{ $lead->phone }} · {{ $lead->source }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-dark-300">{{ $lead->service->name ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusBg = ['new' => 'blue', 'contacted' => 'cyan', 'proposal_sent' => 'amber', 'negotiation' => 'purple', 'closed_won' => 'emerald', 'closed_lost' => 'red'];
                                @endphp
                                <span
                                    class="badge inline-flex px-2.5 py-1 rounded-lg text-xs font-medium bg-{{ $statusBg[$lead->status] }}-500/10 text-{{ $statusBg[$lead->status] }}-400 border border-{{ $statusBg[$lead->status] }}-500/20">
                                    {{ $statusLabels[$lead->status] }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @php $urgBg = ['low' => 'green', 'medium' => 'yellow', 'high' => 'red']; @endphp
                                <span
                                    class="badge inline-flex px-2.5 py-1 rounded-lg text-xs font-medium bg-{{ $urgBg[$lead->urgency_level] }}-500/10 text-{{ $urgBg[$lead->urgency_level] }}-400">
                                    {{ ucfirst($lead->urgency_level) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-12 bg-dark-700 rounded-full h-1.5">
                                        <div class="bg-blue-500 h-1.5 rounded-full" style="width: {{ $lead->lead_score }}%">
                                        </div>
                                    </div>
                                    <span class="text-xs text-dark-400">{{ $lead->lead_score }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="text-sm text-dark-300">{{ $lead->estimated_value ? 'Rp ' . number_format($lead->estimated_value, 0, ',', '.') : '-' }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('leads.show', $lead) }}"
                                        class="p-1.5 text-dark-400 hover:text-blue-400 transition-colors" title="Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('leads.edit', $lead) }}"
                                        class="p-1.5 text-dark-400 hover:text-amber-400 transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <button type="button" onclick="confirmDelete('{{ route('leads.destroy', $lead) }}')"
                                        class="p-1.5 text-dark-400 hover:text-red-400 transition-colors" title="Hapus">
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
                            <td colspan="7" class="text-center text-dark-400 py-8">Belum ada lead</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($leads->hasPages())
            <div class="px-6 py-4 border-t border-dark-700/50">
                {{ $leads->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection