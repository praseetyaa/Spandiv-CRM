@extends('layouts.app')
@section('title', 'Detail Lead: ' . $lead->name)

@section('content')
    <div class="max-w-4xl">
        <div class="glass rounded-2xl p-8 mb-6">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $lead->name }}</h3>
                    <p class="text-dark-400 text-sm mt-1">{{ $lead->phone }} · {{ $lead->email ?? 'No email' }}</p>
                </div>
                <div class="flex items-center gap-2">
                    @if($lead->canConvert())
                        <button onclick="document.getElementById('convertModal').classList.remove('hidden')"
                            class="px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white text-sm font-medium rounded-xl shadow-lg shadow-emerald-500/20 transition-all duration-300 flex items-center gap-2 animate-pulse hover:animate-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                            Convert to Client
                        </button>
                    @endif
                    <a href="{{ route('leads.edit', $lead) }}"
                        class="px-4 py-2 bg-gray-200 dark:bg-dark-700 hover:bg-gray-300 dark:hover:bg-dark-600 text-gray-700 dark:text-white text-sm rounded-xl transition-colors">Edit</a>
                    <a href="{{ route('leads.index') }}"
                        class="px-4 py-2 text-gray-400 dark:text-gray-500 dark:text-dark-400 hover:text-gray-900 dark:hover:text-white text-sm transition-colors">←
                        Kembali</a>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-gray-100 dark:bg-dark-800/50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 dark:text-dark-400 mb-1">Status</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                        {{ ucfirst(str_replace('_', ' ', $lead->status)) }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-dark-800/50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 dark:text-dark-400 mb-1">Urgency</p>
                    <p
                        class="text-sm font-medium text-{{ $lead->urgency_level == 'high' ? 'red' : ($lead->urgency_level == 'medium' ? 'amber' : 'emerald') }}-400">
                        {{ ucfirst($lead->urgency_level) }}
                    </p>
                </div>
                <div class="bg-gray-100 dark:bg-dark-800/50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 dark:text-dark-400 mb-1">Lead Score</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $lead->lead_score }}/100</p>
                </div>
                <div class="bg-gray-100 dark:bg-dark-800/50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 dark:text-dark-400 mb-1">Estimated Value</p>
                    <p class="text-sm font-medium text-emerald-400">
                        {{ $lead->estimated_value ? 'Rp ' . number_format($lead->estimated_value, 0, ',', '.') : '-' }}
                    </p>
                </div>
                <div class="bg-gray-100 dark:bg-dark-800/50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 dark:text-dark-400 mb-1">Layanan</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $lead->service->name ?? '-' }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-dark-800/50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 dark:text-dark-400 mb-1">Sumber</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ ucfirst($lead->source) }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-dark-800/50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 dark:text-dark-400 mb-1">Last Follow Up</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                        {{ $lead->last_follow_up ? $lead->last_follow_up->format('d M Y H:i') : 'Belum' }}
                    </p>
                </div>
                <div class="bg-gray-100 dark:bg-dark-800/50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 dark:text-dark-400 mb-1">Dibuat</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $lead->created_at->format('d M Y') }}
                    </p>
                </div>
            </div>

            @if($lead->notes)
                <div class="mt-6 bg-gray-100 dark:bg-dark-800/50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 dark:text-dark-400 mb-2">Catatan</p>
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

    {{-- Conversion Modal --}}
    @if($lead->canConvert())
        <div id="convertModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen p-4">
                {{-- Backdrop --}}
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity"
                    onclick="document.getElementById('convertModal').classList.add('hidden')"></div>

                {{-- Modal --}}
                <div
                    class="relative glass rounded-2xl w-full max-w-2xl p-8 shadow-2xl border border-gray-200 dark:border-dark-600/50 transform transition-all">
                    {{-- Header --}}
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Convert Lead to Client</h3>
                                <p class="text-xs text-gray-500 dark:text-dark-400">Konversi {{ $lead->name }} menjadi client
                                    aktif</p>
                            </div>
                        </div>
                        <button onclick="document.getElementById('convertModal').classList.add('hidden')"
                            class="p-2 text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors rounded-lg hover:bg-gray-100 dark:hover:bg-dark-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    {{-- Flow Indicator --}}
                    <div class="flex items-center gap-2 mb-6 p-3 bg-gray-100 dark:bg-dark-800/50 rounded-xl">
                        <span
                            class="px-2.5 py-1 bg-blue-500/10 text-blue-400 text-xs font-medium rounded-lg border border-blue-500/20">Lead</span>
                        <svg class="w-4 h-4 text-gray-400 dark:text-dark-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                        <span
                            class="px-2.5 py-1 bg-emerald-500/10 text-emerald-400 text-xs font-medium rounded-lg border border-emerald-500/20">Client</span>
                        <svg class="w-4 h-4 text-gray-400 dark:text-dark-500 transition-opacity" id="flowArrowProject"
                            style="display:none;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                        <span
                            class="px-2.5 py-1 bg-purple-500/10 text-purple-400 text-xs font-medium rounded-lg border border-purple-500/20 transition-opacity"
                            id="flowBadgeProject" style="display:none;">Project</span>
                        <svg class="w-4 h-4 text-gray-400 dark:text-dark-500 transition-opacity" id="flowArrowSub"
                            style="display:none;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                        <span
                            class="px-2.5 py-1 bg-amber-500/10 text-amber-400 text-xs font-medium rounded-lg border border-amber-500/20 transition-opacity"
                            id="flowBadgeSub" style="display:none;">Subscription</span>
                    </div>

                    <form method="POST" action="{{ route('leads.convert', $lead) }}" class="space-y-6">
                        @csrf

                        {{-- Client Info Section --}}
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 dark:text-dark-200 mb-3 flex items-center gap-2">
                                <span
                                    class="w-6 h-6 rounded-full bg-emerald-500/10 text-emerald-400 text-xs flex items-center justify-center font-bold">1</span>
                                Informasi Client
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 dark:text-dark-400 mb-1">Nama <span
                                            class="text-red-400">*</span></label>
                                    <input type="text" name="client_name" value="{{ $lead->name }}" required
                                        class="w-full px-3 py-2 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500/50 focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 dark:text-dark-400 mb-1">Telepon <span
                                            class="text-red-400">*</span></label>
                                    <input type="text" name="client_phone" value="{{ $lead->phone }}" required
                                        class="w-full px-3 py-2 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500/50 focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 dark:text-dark-400 mb-1">Email</label>
                                    <input type="email" name="client_email" value="{{ $lead->email }}"
                                        class="w-full px-3 py-2 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500/50 focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 dark:text-dark-400 mb-1">Nama Bisnis
                                        <span class="text-red-400">*</span></label>
                                    <input type="text" name="business_name" value="{{ $lead->name }}" required
                                        class="w-full px-3 py-2 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500/50 focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 dark:text-dark-400 mb-1">Industri
                                        <span class="text-red-400">*</span></label>
                                    <select name="industry" required
                                        class="w-full px-3 py-2 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500/50 focus:outline-none">
                                        @foreach(['Technology', 'E-Commerce', 'F&B', 'Fashion', 'Healthcare', 'Education', 'Finance', 'Real Estate', 'Manufacturing', 'Creative Agency', 'Consulting', 'Lainnya'] as $ind)
                                            <option value="{{ $ind }}">{{ $ind }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-500 dark:text-dark-400 mb-1">Instagram</label>
                                    <input type="text" name="instagram" placeholder="@username"
                                        class="w-full px-3 py-2 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500/50 focus:outline-none">
                                </div>
                            </div>
                        </div>

                        {{-- Divider --}}
                        <div class="border-t border-gray-200 dark:border-dark-700/50"></div>

                        {{-- Project Option --}}
                        <div>
                            <label class="flex items-center gap-3 cursor-pointer group" for="create_project">
                                <input type="checkbox" id="create_project" name="create_project" value="1"
                                    class="w-4 h-4 rounded border-gray-300 dark:border-dark-600 text-purple-500 focus:ring-purple-500/50"
                                    onchange="toggleSection('projectFields', this.checked); toggleFlow('Project', this.checked)">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="w-6 h-6 rounded-full bg-purple-500/10 text-purple-400 text-xs flex items-center justify-center font-bold">2</span>
                                    <span
                                        class="text-sm font-semibold text-gray-700 dark:text-dark-200 group-hover:text-purple-400 transition-colors">Buat
                                        Project Sekaligus</span>
                                </div>
                                @if($lead->service)
                                    <span class="text-xs text-gray-400 dark:text-dark-500 ml-auto">Layanan:
                                        {{ $lead->service->name }}</span>
                                @endif
                            </label>
                            <div id="projectFields" class="hidden mt-4 ml-8 space-y-4 animate-slideDown">
                                <div class="p-4 bg-purple-500/5 border border-purple-500/10 rounded-xl space-y-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="md:col-span-2">
                                            <label class="block text-xs font-medium text-gray-500 dark:text-dark-400 mb-1">Judul
                                                Project <span class="text-red-400">*</span></label>
                                            <input type="text" name="project_title" value="Project - {{ $lead->name }}"
                                                class="w-full px-3 py-2 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-purple-500/50 focus:outline-none">
                                        </div>
                                        <div>
                                            <label
                                                class="block text-xs font-medium text-gray-500 dark:text-dark-400 mb-1">Tanggal
                                                Mulai <span class="text-red-400">*</span></label>
                                            <input type="date" name="project_start_date" value="{{ now()->format('Y-m-d') }}"
                                                class="w-full px-3 py-2 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-purple-500/50 focus:outline-none">
                                        </div>
                                        <div>
                                            <label
                                                class="block text-xs font-medium text-gray-500 dark:text-dark-400 mb-1">Deadline
                                                <span class="text-red-400">*</span></label>
                                            <input type="date" name="project_deadline"
                                                value="{{ now()->addDays(30)->format('Y-m-d') }}"
                                                class="w-full px-3 py-2 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-purple-500/50 focus:outline-none">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label
                                                class="block text-xs font-medium text-gray-500 dark:text-dark-400 mb-1">Deskripsi</label>
                                            <textarea name="project_description" rows="2" placeholder="Deskripsi project..."
                                                class="w-full px-3 py-2 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-purple-500/50 focus:outline-none"></textarea>
                                        </div>
                                    </div>
                                    @if($lead->estimated_value)
                                        <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-dark-400">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Harga project: Rp {{ number_format($lead->estimated_value, 0, ',', '.') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Subscription Option --}}
                        <div>
                            <label class="flex items-center gap-3 cursor-pointer group" for="create_subscription">
                                <input type="checkbox" id="create_subscription" name="create_subscription" value="1"
                                    class="w-4 h-4 rounded border-gray-300 dark:border-dark-600 text-amber-500 focus:ring-amber-500/50"
                                    onchange="toggleSection('subscriptionFields', this.checked); toggleFlow('Sub', this.checked)">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="w-6 h-6 rounded-full bg-amber-500/10 text-amber-400 text-xs flex items-center justify-center font-bold">3</span>
                                    <span
                                        class="text-sm font-semibold text-gray-700 dark:text-dark-200 group-hover:text-amber-400 transition-colors">Buat
                                        Subscription Sekaligus</span>
                                </div>
                                @if($lead->service)
                                    <span
                                        class="text-xs text-gray-400 dark:text-dark-500 ml-auto">{{ ucfirst($lead->service->billing_type) }}</span>
                                @endif
                            </label>
                            <div id="subscriptionFields" class="hidden mt-4 ml-8 space-y-4 animate-slideDown">
                                <div class="p-4 bg-amber-500/5 border border-amber-500/10 rounded-xl space-y-4">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label
                                                class="block text-xs font-medium text-gray-500 dark:text-dark-400 mb-1">Billing
                                                Cycle <span class="text-red-400">*</span></label>
                                            <select name="billing_cycle"
                                                class="w-full px-3 py-2 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/50 focus:outline-none">
                                                <option value="monthly">Bulanan</option>
                                                <option value="quarterly">Per 3 Bulan</option>
                                                <option value="yearly">Tahunan</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 dark:text-dark-400 mb-1">Mulai
                                                <span class="text-red-400">*</span></label>
                                            <input type="date" name="subscription_start_date"
                                                value="{{ now()->format('Y-m-d') }}"
                                                class="w-full px-3 py-2 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/50 focus:outline-none">
                                        </div>
                                        <div>
                                            <label
                                                class="block text-xs font-medium text-gray-500 dark:text-dark-400 mb-1">Berakhir
                                                <span class="text-red-400">*</span></label>
                                            <input type="date" name="subscription_end_date"
                                                value="{{ now()->addYear()->format('Y-m-d') }}"
                                                class="w-full px-3 py-2 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/50 focus:outline-none">
                                        </div>
                                    </div>
                                    @if($lead->estimated_value)
                                        <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-dark-400">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Harga subscription: Rp {{ number_format($lead->estimated_value, 0, ',', '.') }}/cycle
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-dark-700/50">
                            <button type="button" onclick="document.getElementById('convertModal').classList.add('hidden')"
                                class="px-5 py-2.5 bg-gray-200 dark:bg-dark-700 hover:bg-gray-300 dark:hover:bg-dark-600 text-gray-700 dark:text-white text-sm font-medium rounded-xl transition-colors">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-5 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white text-sm font-medium rounded-xl shadow-lg shadow-emerald-500/20 transition-all flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Konversi Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        function toggleSection(id, show) {
            const el = document.getElementById(id);
            if (show) {
                el.classList.remove('hidden');
                // Add animation
                el.style.opacity = '0';
                el.style.transform = 'translateY(-10px)';
                requestAnimationFrame(() => {
                    el.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                });
            } else {
                el.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
                el.style.opacity = '0';
                el.style.transform = 'translateY(-10px)';
                setTimeout(() => el.classList.add('hidden'), 200);
            }
        }

        function toggleFlow(type, show) {
            const arrow = document.getElementById('flowArrow' + type);
            const badge = document.getElementById('flowBadge' + type);
            if (arrow && badge) {
                arrow.style.display = show ? 'block' : 'none';
                badge.style.display = show ? 'inline-flex' : 'none';
            }
        }
    </script>
@endpush