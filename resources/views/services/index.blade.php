@extends('layouts.app')
@section('title', 'Layanan')
@section('subtitle', 'Kelola layanan Spandiv')
@section('header-actions')
    <a href="{{ route('services.create') }}"
        class="px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 text-white text-sm font-medium rounded-xl shadow-lg shadow-blue-500/20 transition-all flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>Tambah Layanan</a>
@endsection
@section('content')
    <div class="glass rounded-2xl p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-3 items-center">
            <select name="category"
                class="px-4 py-2 bg-dark-800/50 border border-dark-600/50 rounded-xl text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                <option value="">Semua Kategori</option>
                @foreach(['website' => 'Website', 'branding' => 'Branding', 'social_media' => 'Social Media', 'invitation' => 'Invitation'] as $val => $lbl)
                    <option value="{{ $val }}" {{ request('category') == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                @endforeach
            </select>
            <button type="submit"
                class="px-4 py-2 bg-dark-700 hover:bg-dark-600 text-white text-sm rounded-xl transition-colors">Filter</button>
        </form>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($services as $service)
            @php $catColor = ['website' => 'blue', 'branding' => 'purple', 'social_media' => 'amber', 'invitation' => 'emerald'][$service->category] ?? 'blue'; @endphp
            <div class="glass rounded-2xl p-6 stat-card">
                <div class="flex items-start justify-between mb-4">
                    <span
                        class="px-2.5 py-1 text-xs rounded-lg bg-{{ $catColor }}-500/10 text-{{ $catColor }}-400 border border-{{ $catColor }}-500/20">{{ ucfirst(str_replace('_', ' ', $service->category)) }}</span>
                    <span
                        class="px-2.5 py-1 text-xs rounded-lg {{ $service->billing_type == 'recurring' ? 'bg-cyan-500/10 text-cyan-400' : 'bg-dark-600 text-dark-400' }}">{{ $service->billing_type == 'recurring' ? '🔁 Recurring' : 'One Time' }}</span>
                </div>
                <h3 class="text-lg font-semibold text-white mb-2">{{ $service->name }}</h3>
                <p class="text-2xl font-bold text-emerald-400 mb-4">Rp
                    {{ number_format($service->base_price, 0, ',', '.') }}<span
                        class="text-sm text-dark-400 font-normal">{{ $service->billing_type == 'recurring' ? '/bulan' : '' }}</span>
                </p>
                <div class="flex items-center gap-4 text-xs text-dark-400 mb-4">
                    <span>{{ $service->leads_count ?? 0 }} leads</span>
                    <span>{{ $service->projects_count ?? 0 }} projects</span>
                    <span>{{ $service->subscriptions_count ?? 0 }} subs</span>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('services.edit', $service) }}"
                        class="px-3 py-1.5 bg-dark-700 hover:bg-dark-600 text-white text-xs rounded-lg transition-colors">Edit</a>
                    <button type="button" onclick="confirmDelete('{{ route('services.destroy', $service) }}')"
                        class="px-3 py-1.5 bg-red-500/10 hover:bg-red-500/20 text-red-400 text-xs rounded-lg transition-colors">Hapus</button>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center text-dark-400 py-8">Belum ada layanan</div>
        @endforelse
    </div>
    @if($services->hasPages())
    <div class="mt-6">{{ $services->withQueryString()->links() }}</div>@endif
@endsection