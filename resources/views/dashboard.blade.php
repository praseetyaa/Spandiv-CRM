@extends('layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Selamat datang di Spandiv CRM')

@section('content')
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        {{-- LEFT COLUMN: Stats + Data (2/3 width) --}}
        <div class="xl:col-span-2 space-y-6">
            {{-- Stats Cards Row 1 --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                {{-- Total Leads --}}
                <div class="stat-card glass rounded-2xl p-5">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-10 h-10 bg-blue-500/10 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <span class="text-xs text-gray-500 dark:text-dark-400">Bulan Ini</span>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_leads_this_month'] }}</p>
                    <p class="text-xs text-gray-500 dark:text-dark-400 mt-1">Total Lead</p>
                </div>

                {{-- Conversion Rate --}}
                <div class="stat-card glass rounded-2xl p-5">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-10 h-10 bg-emerald-500/10 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-500 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <span class="text-xs text-emerald-500 dark:text-emerald-400">↗ Win Rate</span>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['conversion_rate'] }}%</p>
                    <p class="text-xs text-gray-500 dark:text-dark-400 mt-1">Conversion Rate</p>
                </div>

                {{-- Active Projects --}}
                <div class="stat-card glass rounded-2xl p-5">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-10 h-10 bg-amber-500/10 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-500 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <span class="text-xs text-gray-500 dark:text-dark-400">Aktif</span>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['active_projects'] }}</p>
                    <p class="text-xs text-gray-500 dark:text-dark-400 mt-1">Project Berjalan</p>
                </div>

                {{-- Monthly Revenue --}}
                <div class="stat-card glass rounded-2xl p-5">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-10 h-10 bg-purple-500/10 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-500 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-xs text-gray-500 dark:text-dark-400">Bulan Ini</span>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($stats['monthly_revenue'], 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 dark:text-dark-400 mt-1">Revenue Bulanan</p>
                </div>
            </div>

            {{-- Stats Cards Row 2 --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="stat-card glass rounded-2xl p-5">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-10 h-10 bg-cyan-500/10 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-cyan-500 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($stats['mrr'], 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 dark:text-dark-400 mt-1">MRR (Recurring)</p>
                </div>

                <div class="stat-card glass rounded-2xl p-5">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-10 h-10 bg-pink-500/10 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-pink-500 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['active_subscriptions'] }}</p>
                    <p class="text-xs text-gray-500 dark:text-dark-400 mt-1">Subscription Aktif</p>
                </div>

                <div class="stat-card glass rounded-2xl p-5">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-10 h-10 bg-red-500/10 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-{{ $stats['overdue_invoices'] > 0 ? 'red-400' : 'gray-900 dark:text-white' }}">
                        {{ $stats['overdue_invoices'] }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-dark-400 mt-1">Invoice Overdue</p>
                </div>

                <div class="stat-card glass rounded-2xl p-5">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-10 h-10 bg-indigo-500/10 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_clients'] }}</p>
                    <p class="text-xs text-gray-500 dark:text-dark-400 mt-1">Total Client Aktif</p>
                </div>
            </div>

            {{-- Top Clients & Lead Sources --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="glass rounded-2xl p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Top 5 Client</h3>
                    <div class="space-y-3">
                        @forelse($topClients as $index => $client)
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-100 dark:bg-dark-800/50">
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                    {{ $index + 1 }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $client['name'] }}</p>
                                    <p class="text-xs text-gray-500 dark:text-dark-400">{{ $client['business_name'] ?? '' }}</p>
                                </div>
                                <p class="text-sm font-semibold text-emerald-500 dark:text-emerald-400">Rp {{ number_format($client['total_lifetime_value'], 0, ',', '.') }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-dark-400 text-center py-4">Belum ada data client</p>
                        @endforelse
                    </div>
                </div>

                <div class="glass rounded-2xl p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Performa Sumber Lead</h3>
                    <div class="space-y-3">
                        @forelse($leadSources as $source)
                            <div class="p-3 rounded-xl bg-gray-100 dark:bg-dark-800/50">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-900 dark:text-white capitalize">{{ $source['source'] }}</span>
                                    <span class="text-xs text-gray-500 dark:text-dark-400">{{ $source['won'] }}/{{ $source['total'] }} won</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-dark-700 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-2 rounded-full transition-all duration-500"
                                        style="width: {{ $source['total'] > 0 ? round(($source['won'] / $source['total']) * 100) : 0 }}%">
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-dark-400 text-center py-4">Belum ada data lead</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: Charts --}}
        <div class="space-y-6">
            <div class="glass rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Tren Revenue (6 Bulan)</h3>
                <div style="height: 220px; position: relative;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <div class="glass rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Revenue per Kategori</h3>
                <div style="height: 280px; position: relative;">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const isDark = document.documentElement.classList.contains('dark');
        const gridColor = isDark ? 'rgba(148,163,184,0.08)' : 'rgba(0,0,0,0.06)';
        const tickColor = isDark ? '#64748b' : '#94a3b8';
        const legendColor = isDark ? '#94a3b8' : '#64748b';

        const revenueData = @json($revenueChart);
        new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: {
                labels: revenueData.map(d => d.month),
                datasets: [{
                    label: 'Revenue',
                    data: revenueData.map(d => d.revenue),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true, tension: 0.4, borderWidth: 2,
                    pointBackgroundColor: '#3b82f6', pointRadius: 4, pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { callbacks: { label: (ctx) => 'Rp ' + new Intl.NumberFormat('id-ID').format(ctx.raw) } } },
                scales: {
                    y: { ticks: { color: tickColor, callback: (val) => 'Rp ' + (val / 1000000).toFixed(0) + 'M', font: { size: 10 } }, grid: { color: gridColor } },
                    x: { ticks: { color: tickColor, font: { size: 10 } }, grid: { display: false } }
                }
            }
        });

        const categoryData = @json($revenuePerCategory);
        const categoryColors = { 'website': '#3b82f6', 'branding': '#8b5cf6', 'social_media': '#f59e0b', 'invitation': '#10b981' };
        new Chart(document.getElementById('categoryChart'), {
            type: 'doughnut',
            data: {
                labels: categoryData.map(d => d.category.replace('_', ' ').toUpperCase()),
                datasets: [{ data: categoryData.map(d => parseFloat(d.total_revenue)), backgroundColor: categoryData.map(d => categoryColors[d.category] || '#64748b'), borderWidth: 0, hoverOffset: 6 }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '65%',
                plugins: {
                    legend: { position: 'bottom', labels: { color: legendColor, padding: 12, usePointStyle: true, pointStyleWidth: 8, font: { size: 11 } } },
                    tooltip: { callbacks: { label: (ctx) => ctx.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(ctx.raw) } }
                }
            }
        });
    </script>
@endpush