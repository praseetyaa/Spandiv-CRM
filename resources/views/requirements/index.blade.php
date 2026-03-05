@extends('layouts.app')
@section('title', 'Requirements Masuk')
@section('subtitle', 'Daftar requirements dari form publik')

@section('header-actions')
    @php
        $userCompany = auth()->user()->company;
        $formUrl = $userCompany && $userCompany->form_slug ? route('requirements.form', $userCompany->form_slug) : null;
    @endphp
    @if($formUrl)
        <a href="{{ $formUrl }}" target="_blank"
            class="px-4 py-2 bg-gray-200 dark:bg-dark-700 hover:bg-gray-300 dark:hover:bg-dark-600 text-gray-700 dark:text-white text-sm font-medium rounded-xl transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
            </svg>Buka Form
        </a>
    @endif
@endsection

@section('content')
    {{-- Filter --}}
    <div class="glass rounded-2xl p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-3 items-center">
            <select name="status"
                class="px-4 py-2 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                <option value="">Semua Status</option>
                @foreach(['new' => '🔵 Baru', 'reviewed' => '🟡 Reviewed', 'converted' => '🟢 Converted'] as $val => $lbl)
                    <option value="{{ $val }}" {{ request('status') == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                @endforeach
            </select>
            @include('partials.company-filter')
            <button type="submit" class="px-4 py-2 bg-gray-200 dark:bg-dark-700 hover:bg-gray-300 dark:hover:bg-dark-600 text-gray-700 dark:text-white text-sm rounded-xl transition-colors">Filter</button>
            @if(request()->hasAny(['status', 'company_id']))
                <a href="{{ route('admin.requirements.index') }}" class="text-xs text-gray-400 hover:text-white">Reset</a>
            @endif
        </form>
    </div>

    {{-- Mobile Cards --}}
    <div class="block lg:hidden space-y-3">
        @forelse($requirements as $req)
            @php
                $statusMap = [
                    'new' => ['bg' => 'bg-blue-500/10 text-blue-400', 'label' => 'Baru'],
                    'reviewed' => ['bg' => 'bg-amber-500/10 text-amber-400', 'label' => 'Reviewed'],
                    'converted' => ['bg' => 'bg-emerald-500/10 text-emerald-400', 'label' => 'Converted'],
                ];
                $st = $statusMap[$req->status] ?? $statusMap['new'];
            @endphp
            <a href="{{ route('admin.requirements.show', $req) }}" class="block glass rounded-xl p-4 hover:bg-gray-50 dark:hover:bg-dark-800/30 transition-colors">
                <div class="flex items-start justify-between mb-2">
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $req->name }}</p>
                        @if($req->company_name)
                            <p class="text-xs text-gray-400 dark:text-dark-500">{{ $req->company_name }}</p>
                        @endif
                    </div>
                    <span class="px-2 py-0.5 text-xs rounded-lg {{ $st['bg'] }}">{{ $st['label'] }}</span>
                </div>
                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-gray-500 dark:text-dark-400">
                    <span>📱 {{ $req->phone }}</span>
                    <span>🔧 {{ $req->service?->name ?? '-' }}</span>
                    <span>📅 {{ $req->created_at->format('d M Y') }}</span>
                </div>
                @if($req->budget_range)
                    <p class="text-xs text-emerald-400 mt-1">💰 {{ $req->budget_range }}</p>
                @endif
            </a>
        @empty
            <div class="glass rounded-2xl p-8 text-center text-gray-500 dark:text-dark-400 text-sm">Belum ada requirements masuk</div>
        @endforelse
    </div>

    {{-- Desktop Table --}}
    <div class="hidden lg:block glass rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-dark-700/50">
                        <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 dark:text-dark-400 uppercase">#</th>
                        <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 dark:text-dark-400 uppercase">Nama</th>
                        <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 dark:text-dark-400 uppercase">Kontak</th>
                        <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 dark:text-dark-400 uppercase">Layanan</th>
                        <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 dark:text-dark-400 uppercase">Budget</th>
                        <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 dark:text-dark-400 uppercase">Status</th>
                        <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 dark:text-dark-400 uppercase">Tanggal</th>
                        <th class="text-right px-5 py-3 text-xs font-medium text-gray-500 dark:text-dark-400 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requirements as $req)
                        @php
                            $statusMap = [
                                'new' => ['bg' => 'bg-blue-500/10 text-blue-400', 'label' => 'Baru'],
                                'reviewed' => ['bg' => 'bg-amber-500/10 text-amber-400', 'label' => 'Reviewed'],
                                'converted' => ['bg' => 'bg-emerald-500/10 text-emerald-400', 'label' => 'Converted'],
                            ];
                            $st = $statusMap[$req->status] ?? $statusMap['new'];
                        @endphp
                        <tr class="table-row border-b border-gray-100 dark:border-dark-700/30">
                            <td class="px-5 py-4 text-sm text-gray-400 dark:text-dark-500">#{{ $req->id }}</td>
                            <td class="px-5 py-4">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $req->name }}</p>
                                @if($req->company_name)
                                    <p class="text-xs text-gray-400 dark:text-dark-500">{{ $req->company_name }}</p>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <p class="text-sm text-gray-600 dark:text-dark-300">{{ $req->phone }}</p>
                                <p class="text-xs text-gray-400 dark:text-dark-500">{{ $req->email ?: '-' }}</p>
                            </td>
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-dark-300">{{ $req->service?->name ?? '-' }}</td>
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-dark-300">{{ $req->budget_range ?: '-' }}</td>
                            <td class="px-5 py-4">
                                <span class="px-2.5 py-1 text-xs rounded-lg {{ $st['bg'] }}">{{ $st['label'] }}</span>
                            </td>
                            <td class="px-5 py-4 text-sm text-gray-400 dark:text-dark-500">{{ $req->created_at->format('d M Y H:i') }}</td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.requirements.show', $req) }}"
                                        class="px-3 py-1.5 bg-gray-200 dark:bg-dark-700 hover:bg-gray-300 dark:hover:bg-dark-600 text-gray-700 dark:text-white text-xs rounded-lg transition-colors">Detail</a>
                                    @if(!$req->isConverted())
                                        <form method="POST" action="{{ route('admin.requirements.convert', $req) }}" class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="px-3 py-1.5 bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 text-xs rounded-lg transition-colors">→ Lead</button>
                                        </form>
                                    @else
                                        <a href="{{ route('leads.show', $req->lead_id) }}"
                                            class="px-3 py-1.5 bg-emerald-500/10 text-emerald-400 text-xs rounded-lg">Lead #{{ $req->lead_id }}</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-12 text-gray-500 dark:text-dark-400">Belum ada requirements masuk</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($requirements->hasPages())
        <div class="mt-6">{{ $requirements->withQueryString()->links() }}</div>
    @endif
@endsection
