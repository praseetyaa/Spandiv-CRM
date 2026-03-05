@extends('layouts.app')
@section('title', isset($lead) ? 'Edit Lead' : 'Tambah Lead')

@section('content')
    <div class="">
        {{-- Won Status Info Alert --}}
        @if(isset($lead) && $lead->isWon() && $lead->canConvert())
            <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-500/20 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-emerald-400 font-medium">Lead ini sudah Won! 🎉</p>
                    <p class="text-xs text-emerald-400/70 mt-0.5">Setelah menyimpan, Anda bisa konversi lead ini menjadi Client
                        dari halaman detail.</p>
                </div>
                <a href="{{ route('leads.show', $lead) }}"
                    class="px-3 py-1.5 bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-400 text-xs font-medium rounded-lg transition-colors flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                    Convert
                </a>
            </div>
        @elseif(isset($lead) && $lead->isWon() && $lead->client)
            <div
                class="mb-6 p-4 bg-gray-100 dark:bg-dark-800/50 border border-gray-200 dark:border-dark-600/50 rounded-xl flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-500/20 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <p class="text-sm text-gray-600 dark:text-dark-300">Lead ini sudah dikonversi menjadi client:
                    <a href="{{ route('clients.show', $lead->client) }}"
                        class="text-emerald-400 font-medium underline">{{ $lead->client->name }}</a>
                </p>
            </div>
        @endif

        <div class="glass rounded-2xl p-8">
            <form method="POST" action="{{ isset($lead) ? route('leads.update', $lead) : route('leads.store') }}"
                class="space-y-6">
                @csrf
                @if(isset($lead)) @method('PUT') @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Nama <span
                                class="text-red-400">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $lead->name ?? '') }}" required
                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none"
                            placeholder="Nama lengkap">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Telepon <span
                                class="text-red-400">*</span></label>
                        <input type="text" name="phone" value="{{ old('phone', $lead->phone ?? '') }}" required
                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none"
                            placeholder="08xxxxxxxxxx">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', $lead->email ?? '') }}"
                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none"
                            placeholder="email@example.com">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Sumber <span
                                class="text-red-400">*</span></label>
                        <select name="source" required
                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                            @foreach(['instagram', 'website', 'referral', 'google', 'whatsapp', 'other'] as $src)
                                <option value="{{ $src }}" {{ old('source', $lead->source ?? '') == $src ? 'selected' : '' }}>
                                    {{ ucfirst($src) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Layanan <span
                                class="text-red-400">*</span></label>
                        <select name="service_id" required
                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                            <option value="">Pilih Layanan</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id', $lead->service_id ?? '') == $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Estimasi
                            Nilai</label>
                        <input type="number" name="estimated_value"
                            value="{{ old('estimated_value', $lead->estimated_value ?? '') }}"
                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none"
                            placeholder="5000000">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Urgency <span
                                class="text-red-400">*</span></label>
                        <select name="urgency_level" required
                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                            @foreach(['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'] as $val => $label)
                                <option value="{{ $val }}" {{ old('urgency_level', $lead->urgency_level ?? 'medium') == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Status <span
                                class="text-red-400">*</span></label>
                        <select name="status" required id="leadStatusSelect"
                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                            @foreach(['new' => 'Baru', 'contacted' => 'Dihubungi', 'proposal_sent' => 'Proposal Terkirim', 'negotiation' => 'Negosiasi', 'closed_won' => 'Closed Won', 'closed_lost' => 'Closed Lost'] as $val => $label)
                                <option value="{{ $val }}" {{ old('status', $lead->status ?? 'new') == $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Dynamic Won Status Hint --}}
                <div id="wonStatusHint" class="hidden p-3 bg-emerald-500/10 border border-emerald-500/20 rounded-xl">
                    <p class="text-xs text-emerald-400 flex items-center gap-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                        💡 Setelah menyimpan dengan status Won, Anda bisa langsung convert ke Client + Project dari halaman
                        detail lead.
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Catatan</label>
                    <textarea name="notes" rows="3"
                        class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none"
                        placeholder="Catatan tambahan...">{{ old('notes', $lead->notes ?? '') }}</textarea>
                </div>

                <div class="flex items-center gap-3 pt-4">
                    <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 text-white text-sm font-medium rounded-xl shadow-lg shadow-blue-500/20 transition-all">
                        {{ isset($lead) ? 'Update Lead' : 'Simpan Lead' }}
                    </button>
                    <a href="{{ route('leads.index') }}"
                        class="px-6 py-2.5 bg-gray-200 dark:bg-dark-700 hover:bg-gray-300 dark:hover:bg-dark-600 text-gray-700 dark:text-white text-sm font-medium rounded-xl transition-colors">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const statusSelect = document.getElementById('leadStatusSelect');
            const wonHint = document.getElementById('wonStatusHint');

            if (statusSelect && wonHint) {
                function checkStatus() {
                    if (statusSelect.value === 'closed_won') {
                        wonHint.classList.remove('hidden');
                    } else {
                        wonHint.classList.add('hidden');
                    }
                }
                statusSelect.addEventListener('change', checkStatus);
                checkStatus(); // Check on load
            }
        });
    </script>
@endpush