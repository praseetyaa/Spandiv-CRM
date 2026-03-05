@extends('layouts.app')
@section('title', 'Detail Requirement #' . $requirement->id)

@section('content')
    <div class="">
        {{-- Header Card --}}
        <div class="glass rounded-2xl p-6 mb-6">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-4">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center text-white text-xl font-bold">
                        {{ substr($requirement->name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $requirement->name }}</h3>
                        @if($requirement->company_name)
                            <p class="text-sm text-gray-500 dark:text-dark-400">🏢 {{ $requirement->company_name }}</p>
                        @endif
                        <div class="flex items-center gap-3 mt-1">
                            <span class="text-xs text-gray-400 dark:text-dark-500">📱 {{ $requirement->phone }}</span>
                            @if($requirement->email)
                                <span class="text-xs text-gray-400 dark:text-dark-500">✉️ {{ $requirement->email }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @php
                        $statusMap = [
                            'new' => ['bg' => 'bg-blue-500/10 text-blue-400 border-blue-500/20', 'label' => '🔵 Baru'],
                            'reviewed' => ['bg' => 'bg-amber-500/10 text-amber-400 border-amber-500/20', 'label' => '🟡 Reviewed'],
                            'converted' => ['bg' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20', 'label' => '🟢 Converted'],
                        ];
                        $st = $statusMap[$requirement->status] ?? $statusMap['new'];
                    @endphp
                    <span class="px-3 py-1.5 text-sm rounded-xl border {{ $st['bg'] }}">{{ $st['label'] }}</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Info --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Service & Budget --}}
                <div class="glass rounded-2xl p-6">
                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Informasi Layanan
                    </h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-400 dark:text-dark-500">Layanan</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white mt-1">
                                {{ $requirement->service?->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 dark:text-dark-500">Kategori</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white mt-1">
                                {{ ucfirst(str_replace('_', ' ', $requirement->service?->category ?? '-')) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 dark:text-dark-500">Budget</p>
                            <p class="text-sm font-medium text-emerald-400 mt-1">
                                {{ $requirement->budget_range ?: 'Belum ditentukan' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 dark:text-dark-500">Tanggal Submit</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white mt-1">
                                {{ $requirement->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Requirements Detail --}}
                @if($requirement->requirements && count($requirement->requirements) > 0)
                    <div class="glass rounded-2xl p-6">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                            Detail Requirements
                        </h4>
                        <div class="space-y-3">
                            @foreach($requirement->requirements as $key => $value)
                                <div
                                    class="flex flex-col sm:flex-row sm:items-start gap-1 p-3 bg-gray-50 dark:bg-dark-800/30 rounded-xl">
                                    <span class="text-xs font-medium text-gray-500 dark:text-dark-400 sm:w-40 flex-shrink-0">
                                        {{ str_replace('_', ' ', ucfirst($key)) }}
                                    </span>
                                    <span class="text-sm text-gray-900 dark:text-white">
                                        @if(is_array($value))
                                            <div class="flex flex-wrap gap-1.5">
                                                @foreach($value as $v)
                                                    <span
                                                        class="px-2 py-0.5 text-xs bg-indigo-500/10 text-indigo-400 rounded-lg">{{ $v }}</span>
                                                @endforeach
                                            </div>
                                        @else
                                            {{ $value }}
                                        @endif
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

            {{-- Sidebar Actions --}}
            <div class="space-y-6">
                {{-- Actions --}}
                <div class="glass rounded-2xl p-6">
                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Aksi</h4>
                    <div class="space-y-3">
                        @if(!$requirement->isConverted())
                            <form method="POST" action="{{ route('admin.requirements.convert', $requirement) }}">
                                @csrf
                                <button type="submit"
                                    class="w-full px-4 py-2.5 bg-gradient-to-r from-emerald-600 to-cyan-600 hover:from-emerald-500 hover:to-cyan-500 text-white text-sm font-medium rounded-xl shadow-lg shadow-emerald-500/20 transition-all flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                    Convert ke Lead
                                </button>
                            </form>
                        @else
                            <a href="{{ route('leads.show', $requirement->lead_id) }}"
                                class="block w-full px-4 py-2.5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm font-medium rounded-xl text-center hover:bg-emerald-500/20 transition-colors">
                                ✓ Sudah Convert → Lead #{{ $requirement->lead_id }}
                            </a>
                        @endif

                        <a href="{{ route('admin.requirements.index') }}"
                            class="block w-full px-4 py-2.5 bg-gray-200 dark:bg-dark-700 hover:bg-gray-300 dark:hover:bg-dark-600 text-gray-700 dark:text-white text-sm font-medium rounded-xl text-center transition-colors">
                            ← Kembali ke Daftar
                        </a>

                        <button type="button"
                            onclick="confirmDelete('{{ route('admin.requirements.destroy', $requirement) }}')"
                            class="w-full px-4 py-2.5 bg-red-500/10 hover:bg-red-500/20 text-red-400 text-sm font-medium rounded-xl transition-colors">
                            Hapus Requirement
                        </button>
                    </div>
                </div>

                {{-- Meta --}}
                <div class="glass rounded-2xl p-6">
                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Info Tambahan</h4>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-xs text-gray-400 dark:text-dark-500">IP Address</p>
                            <p class="text-gray-600 dark:text-dark-300">{{ $requirement->ip_address ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 dark:text-dark-500">Dibuat</p>
                            <p class="text-gray-600 dark:text-dark-300">{{ $requirement->created_at->diffForHumans() }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 dark:text-dark-500">Terakhir diubah</p>
                            <p class="text-gray-600 dark:text-dark-300">{{ $requirement->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                {{-- Notes --}}
                @if($requirement->notes)
                    <div class="glass rounded-2xl p-6">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Catatan Tambahan
                        </h4>
                        <p class="text-sm text-gray-600 dark:text-dark-300 whitespace-pre-line">{{ $requirement->notes }}</p>
                    </div>
                @endif
            </div>
        </div>
        
    </div>
@endsection