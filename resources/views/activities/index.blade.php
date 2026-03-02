@extends('layouts.app')
@section('title', 'Activity Log')
@section('subtitle', 'Audit trail sistem')
@section('content')
    <div class="glass rounded-2xl p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-3 items-center">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari aktivitas..."
                class="flex-1 min-w-[200px] px-4 py-2 bg-dark-800/50 border border-dark-600/50 rounded-xl text-white text-sm placeholder-dark-500 focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
            <select name="module"
                class="px-4 py-2 bg-dark-800/50 border border-dark-600/50 rounded-xl text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                <option value="">Semua Modul</option>
                @foreach($modules as $mod)<option value="{{ $mod }}" {{ request('module') == $mod ? 'selected' : '' }}>
                {{ ucfirst($mod) }}</option>@endforeach
            </select>
            <button type="submit"
                class="px-4 py-2 bg-dark-700 hover:bg-dark-600 text-white text-sm rounded-xl transition-colors">Filter</button>
        </form>
    </div>
    <div class="glass rounded-2xl overflow-hidden">
        <div class="divide-y divide-dark-700/30">
            @forelse($activities as $act)
                <div class="px-6 py-4 flex items-start gap-4 table-row">
                    <div
                        class="w-9 h-9 bg-dark-800 rounded-full flex items-center justify-center text-dark-400 flex-shrink-0 mt-0.5">
                        @php $icons = ['created' => '➕', 'updated' => '✏️', 'deleted' => '🗑️', 'converted' => '🔄', 'completed' => '✅', 'follow_up' => '📞', 'toggled' => '⚡', 'auto_generated' => '🤖', 'auto_overdue' => '⚠️']; @endphp
                        {{ $icons[$act->action] ?? '📋' }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-white">{{ $act->description }}</p>
                        <div class="flex items-center gap-3 mt-1">
                            <span class="text-xs text-dark-400">{{ $act->created_at->diffForHumans() }}</span>
                            <span class="px-2 py-0.5 text-xs rounded bg-dark-700 text-dark-400">{{ $act->module }}</span>
                            <span class="text-xs text-dark-500">{{ $act->user->name ?? 'System' }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-dark-400 py-8">Belum ada aktivitas</div>
            @endforelse
        </div>
        @if($activities->hasPages())
        <div class="px-6 py-4 border-t border-dark-700/50">{{ $activities->withQueryString()->links() }}</div>@endif
    </div>
@endsection