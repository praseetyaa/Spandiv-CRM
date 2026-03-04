@extends('layouts.app')
@section('title', 'Activity Log')
@section('subtitle', 'Audit trail sistem')
@section('content')
    {{-- Search & Filter --}}
    <div class="glass rounded-2xl p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-3 items-center">
            <div class="flex-1 min-w-[200px] relative">
                <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-dark-500" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari aktivitas..."
                    class="w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-dark-800/50 border border-gray-200 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm placeholder-gray-400 dark:placeholder-dark-500 focus:ring-2 focus:ring-blue-500/50 focus:outline-none transition-all">
            </div>
            <select name="module"
                class="px-4 py-2.5 bg-gray-50 dark:bg-dark-800/50 border border-gray-200 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none transition-all">
                <option value="">Semua Modul</option>
                @foreach ($modules as $mod)
                    <option value="{{ $mod }}" {{ request('module') == $mod ? 'selected' : '' }}>
                        {{ ucfirst($mod) }}
                    </option>
                @endforeach
            </select>
            <button type="submit"
                class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-all duration-300 flex items-center gap-2 hover:-translate-y-0.5 shadow-lg shadow-blue-500/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                Filter
            </button>
        </form>
    </div>

    {{-- Activity List --}}
    <div class="glass rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-dark-700/50">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Riwayat Aktivitas</h3>
            <p class="text-sm text-gray-500 dark:text-dark-400 mt-0.5">
                {{ $activities->total() }} aktivitas tercatat
            </p>
        </div>

        <div class="divide-y divide-gray-100 dark:divide-dark-700/30">
            @php
                $actionStyles = [
                    'created' => ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>', 'bg' => 'bg-emerald-100 dark:bg-emerald-500/15', 'text' => 'text-emerald-600 dark:text-emerald-400'],
                    'updated' => ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>', 'bg' => 'bg-blue-100 dark:bg-blue-500/15', 'text' => 'text-blue-600 dark:text-blue-400'],
                    'deleted' => ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>', 'bg' => 'bg-red-100 dark:bg-red-500/15', 'text' => 'text-red-600 dark:text-red-400'],
                    'converted' => ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>', 'bg' => 'bg-purple-100 dark:bg-purple-500/15', 'text' => 'text-purple-600 dark:text-purple-400'],
                    'completed' => ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>', 'bg' => 'bg-emerald-100 dark:bg-emerald-500/15', 'text' => 'text-emerald-600 dark:text-emerald-400'],
                    'follow_up' => ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>', 'bg' => 'bg-cyan-100 dark:bg-cyan-500/15', 'text' => 'text-cyan-600 dark:text-cyan-400'],
                    'toggled' => ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>', 'bg' => 'bg-amber-100 dark:bg-amber-500/15', 'text' => 'text-amber-600 dark:text-amber-400'],
                    'auto_generated' => ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>', 'bg' => 'bg-gray-100 dark:bg-gray-500/15', 'text' => 'text-gray-600 dark:text-gray-400'],
                    'auto_overdue' => ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>', 'bg' => 'bg-orange-100 dark:bg-orange-500/15', 'text' => 'text-orange-600 dark:text-orange-400'],
                ];
                $defaultStyle = ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>', 'bg' => 'bg-gray-100 dark:bg-gray-500/15', 'text' => 'text-gray-600 dark:text-gray-400'];
            @endphp

            @forelse($activities as $act)
                @php $style = $actionStyles[$act->action] ?? $defaultStyle; @endphp
                <div
                    class="px-6 py-4 flex items-start gap-4 hover:bg-gray-50/50 dark:hover:bg-dark-800/30 transition-colors group">
                    {{-- Icon --}}
                    <div
                        class="w-10 h-10 rounded-xl {{ $style['bg'] }} flex items-center justify-center flex-shrink-0 mt-0.5 group-hover:scale-105 transition-transform">
                        <svg class="w-5 h-5 {{ $style['text'] }}" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">{!! $style['icon'] !!}</svg>
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white leading-snug">{{ $act->description }}</p>
                        <div class="flex flex-wrap items-center gap-2 mt-1.5">
                            <span
                                class="text-xs text-gray-500 dark:text-dark-400">{{ $act->created_at->diffForHumans() }}</span>
                            <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-dark-600"></span>
                            <span
                                class="px-2 py-0.5 text-xs font-medium rounded-md bg-gray-100 dark:bg-dark-700 text-gray-600 dark:text-dark-300 border border-gray-200 dark:border-dark-600/50">{{ $act->module }}</span>
                            <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-dark-600"></span>
                            <span class="text-xs text-gray-500 dark:text-dark-400 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                {{ $act->user->name ?? 'System' }}
                            </span>
                        </div>
                    </div>

                    {{-- Timestamp (desktop) --}}
                    <div class="hidden lg:block flex-shrink-0 text-right">
                        <span class="text-xs text-gray-400 dark:text-dark-500">{{ $act->created_at->format('d M Y') }}</span>
                        <br>
                        <span class="text-xs text-gray-400 dark:text-dark-500">{{ $act->created_at->format('H:i') }}</span>
                    </div>
                </div>
            @empty
                <div class="text-center py-16">
                    <div
                        class="w-16 h-16 bg-gray-100 dark:bg-dark-800 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400 dark:text-dark-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <p class="text-gray-500 dark:text-dark-400 font-medium">Belum ada aktivitas</p>
                    <p class="text-sm text-gray-400 dark:text-dark-500 mt-1">Aktivitas akan muncul di sini saat ada perubahan
                        data.</p>
                </div>
            @endforelse
        </div>

        @if ($activities->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-dark-700/50">
                {{ $activities->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection