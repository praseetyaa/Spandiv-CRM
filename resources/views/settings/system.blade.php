@extends('layouts.app')
@section('title', 'System Settings')
@section('subtitle', 'Kelola pengaturan aplikasi, integrasi, dan pembaruan sistem.')

@section('content')
    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Sidebar Nav -->
        <div class="w-full lg:w-1/4">
            <div class="glass rounded-2xl p-2 flex flex-col gap-1 sticky top-24">
                <button onclick="switchTab('general')" id="tab-general"
                    class="nav-tab w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-300 active-tab bg-blue-500/10 text-blue-600 dark:text-blue-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Pengaturan Umum
                </button>
                <button onclick="switchTab('integrations')" id="tab-integrations"
                    class="nav-tab w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-300 text-gray-600 dark:text-dark-300 hover:bg-gray-100 dark:hover:bg-dark-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                    Integrasi & API
                </button>
                <button onclick="switchTab('maintenance')" id="tab-maintenance"
                    class="nav-tab w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-300 text-gray-600 dark:text-dark-300 hover:bg-gray-100 dark:hover:bg-dark-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    Maintenance & Backup
                </button>
                <button onclick="switchTab('update')" id="tab-update"
                    class="nav-tab w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-300 text-gray-600 dark:text-dark-300 hover:bg-gray-100 dark:hover:bg-dark-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    System Update
                </button>
            </div>
        </div>

        <!-- Content Area -->
        <div class="w-full lg:w-3/4">

            {{-- ====== TAB 1: GENERAL ====== --}}
            <div id="content-general" class="tab-content block animate-fade-up">
                <div class="glass rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-dark-700/50">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Pengaturan Umum</h3>
                        <p class="text-sm text-gray-500 dark:text-dark-400 mt-1">Konfigurasi dasar aplikasi Anda.</p>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('system-settings.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-dark-300 mb-2">Application
                                        Name</label>
                                    <input type="text" name="app_name"
                                        value="{{ $settings['app_name'] ?? config('app.name') }}" required
                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-dark-800/50 border border-gray-200 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 focus:outline-none transition-all">
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-dark-300 mb-2">Application
                                        Tagline</label>
                                    <input type="text" name="app_tagline" value="{{ $settings['app_tagline'] ?? '' }}"
                                        placeholder="e.g. Modern CRM"
                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-dark-800/50 border border-gray-200 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 focus:outline-none transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-dark-300 mb-2">Support
                                        Email</label>
                                    <input type="email" name="support_email" value="{{ $settings['support_email'] ?? '' }}"
                                        placeholder="support@company.com"
                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-dark-800/50 border border-gray-200 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 focus:outline-none transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-dark-300 mb-2">Default
                                        Currency</label>
                                    <input type="text" name="currency" value="{{ $settings['currency'] ?? 'IDR' }}"
                                        placeholder="IDR, USD"
                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-dark-800/50 border border-gray-200 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 focus:outline-none transition-all">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-dark-300 mb-2">Default
                                        Timezone</label>
                                    <select name="timezone"
                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-dark-800/50 border border-gray-200 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 focus:outline-none transition-all">
                                        @php $currentTz = $settings['timezone'] ?? config('app.timezone');
                                        $timezones = ['Asia/Jakarta' => 'Asia/Jakarta (WIB)', 'Asia/Makassar' => 'Asia/Makassar (WITA)', 'Asia/Jayapura' => 'Asia/Jayapura (WIT)', 'Asia/Singapore' => 'Asia/Singapore', 'Asia/Tokyo' => 'Asia/Tokyo', 'Europe/London' => 'Europe/London', 'America/New_York' => 'America/New York', 'UTC' => 'UTC']; @endphp
                                        @foreach($timezones as $tz => $label)
                                            <option value="{{ $tz }}" {{ $currentTz === $tz ? 'selected' : '' }}>{{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="my-8 border-t border-gray-200 dark:border-dark-700/50"></div>

                            <h4
                                class="text-sm font-semibold text-gray-700 dark:text-dark-300 mb-4 uppercase tracking-wider">
                                Branding</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-dark-300 mb-2">System
                                        Logo</label>
                                    <input type="file" name="system_logo" accept="image/*"
                                        class="w-full px-4 py-2 bg-gray-50 dark:bg-dark-800/50 border border-gray-200 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-500/10 file:text-blue-600 dark:file:text-blue-400 hover:file:bg-blue-500/20 focus:outline-none">
                                    @if(isset($settings['system_logo']))
                                        <div
                                            class="mt-3 p-3 bg-white dark:bg-dark-800 rounded-xl border border-gray-200 dark:border-dark-700/50 inline-block">
                                            <img src="{{ asset($settings['system_logo']) }}" alt="Logo"
                                                class="max-h-12 object-contain">
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-dark-300 mb-2">System
                                        Favicon</label>
                                    <input type="file" name="system_favicon" accept="image/png, image/x-icon"
                                        class="w-full px-4 py-2 bg-gray-50 dark:bg-dark-800/50 border border-gray-200 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-500/10 file:text-blue-600 dark:file:text-blue-400 hover:file:bg-blue-500/20 focus:outline-none">
                                    @if(isset($settings['system_favicon']))
                                        <div
                                            class="mt-3 p-3 bg-white dark:bg-dark-800 rounded-xl border border-gray-200 dark:border-dark-700/50 inline-block">
                                            <img src="{{ asset($settings['system_favicon']) }}" alt="Favicon"
                                                class="max-h-8 object-contain">
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-8 flex justify-end">
                                <button type="submit"
                                    class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 text-white font-medium rounded-xl shadow-lg shadow-blue-500/20 transition-all duration-300 flex items-center gap-2 hover:-translate-y-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Simpan Pengaturan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- ====== TAB 2: INTEGRATIONS ====== --}}
            <div id="content-integrations" class="tab-content hidden animate-fade-up">
                <div class="glass rounded-2xl overflow-hidden mb-6">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-dark-700/50">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Integrasi & API</h3>
                        <p class="text-sm text-gray-500 dark:text-dark-400 mt-1">Konfigurasi email, WhatsApp, dan
                            penyimpanan file.</p>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('system-settings.update') }}" method="POST">
                            @csrf
                            {{-- SMTP --}}
                            <h4 class="text-base font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <span
                                    class="w-8 h-8 rounded-lg bg-blue-500/10 flex items-center justify-center text-blue-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </span> SMTP Email Settings
                            </h4>
                            <div
                                class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50/50 dark:bg-dark-800/30 p-5 rounded-2xl border border-gray-100 dark:border-dark-700/30 mb-8">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-dark-300 mb-2">Mail
                                        Host</label>
                                    <input type="text" name="mail_host"
                                        value="{{ $settings['mail_host'] ?? config('mail.mailers.smtp.host') }}"
                                        placeholder="smtp.gmail.com"
                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-dark-800/50 border border-gray-200 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-dark-300 mb-2">Mail
                                        Port</label>
                                    <input type="text" name="mail_port"
                                        value="{{ $settings['mail_port'] ?? config('mail.mailers.smtp.port') }}"
                                        placeholder="587"
                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-dark-800/50 border border-gray-200 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-dark-300 mb-2">Mail
                                        Username</label>
                                    <input type="text" name="mail_username"
                                        value="{{ $settings['mail_username'] ?? config('mail.mailers.smtp.username') }}"
                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-dark-800/50 border border-gray-200 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-dark-300 mb-2">Mail
                                        Password</label>
                                    <div class="flex">
                                        <input type="password" name="mail_password" id="mailPasswordInput"
                                            value="{{ $settings['mail_password'] ?? config('mail.mailers.smtp.password') }}"
                                            class="flex-1 px-4 py-2.5 bg-gray-50 dark:bg-dark-800/50 border border-gray-200 dark:border-dark-600/50 border-r-0 rounded-l-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none transition-all">
                                        <button type="button" onclick="togglePasswordVisibility()"
                                            class="px-3 bg-gray-100 dark:bg-dark-700 border border-gray-200 dark:border-dark-600/50 border-l-0 rounded-r-xl text-gray-500 dark:text-dark-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-200 dark:hover:bg-dark-600 transition-colors flex items-center">
                                            <svg id="eyeIconOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <svg id="eyeIconClosed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                        </button>
                                </div>
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-dark-300 mb-2">Encryption</label>
                                    <select name="mail_encryption"
                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-dark-800/50 border border-gray-200 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none transition-all">
                                        <option value="tls" {{ ($settings['mail_encryption'] ?? 'tls') == 'tls' ? 'selected' : '' }}>TLS</option>
                                        <option value="ssl" {{ ($settings['mail_encryption'] ?? '') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                        <option value="" {{ ($settings['mail_encryption'] ?? 'tls') == '' ? 'selected' : '' }}>None</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-dark-300 mb-2">From
                                        Address</label>
                                    <input type="email" name="mail_from_address"
                                        value="{{ $settings['mail_from_address'] ?? config('mail.from.address') }}"
                                        placeholder="noreply@company.com"
                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-dark-800/50 border border-gray-200 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none transition-all">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-dark-300 mb-2">From
                                        Name</label>
                                    <input type="text" name="mail_from_name"
                                        value="{{ $settings['mail_from_name'] ?? config('mail.from.name', config('app.name')) }}"
                                        placeholder="Spandiv CRM"
                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-dark-800/50 border border-gray-200 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none transition-all">
                                </div>
                            </div>

                            <div class="my-6 border-t border-gray-200 dark:border-dark-700/50"></div>

                            {{-- WhatsApp --}}
                            <h4 class="text-base font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <span
                                    class="w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                </span> WhatsApp Gateway
                            </h4>
                            <div
                                class="grid grid-cols-1 gap-6 bg-gray-50/50 dark:bg-dark-800/30 p-5 rounded-2xl border border-gray-100 dark:border-dark-700/30 mb-8">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-dark-300 mb-2">API
                                        URL</label>
                                    <input type="url" name="whatsapp_api_url"
                                        value="{{ $settings['whatsapp_api_url'] ?? '' }}"
                                        placeholder="https://api.your-wa-gateway.com/send"
                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-dark-800/50 border border-gray-200 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500/50 focus:outline-none transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-dark-300 mb-2">API Key /
                                        Token</label>
                                    <input type="text" name="whatsapp_api_key"
                                        value="{{ $settings['whatsapp_api_key'] ?? '' }}"
                                        placeholder="Your API Secret Token"
                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-dark-800/50 border border-gray-200 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500/50 focus:outline-none transition-all">
                                </div>
                            </div>

                            <div class="my-6 border-t border-gray-200 dark:border-dark-700/50"></div>

                            {{-- Storage --}}
                            <h4 class="text-base font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <span
                                    class="w-8 h-8 rounded-lg bg-cyan-500/10 flex items-center justify-center text-cyan-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" />
                                    </svg>
                                </span> Storage Driver
                            </h4>
                            <div
                                class="bg-gray-50/50 dark:bg-dark-800/30 p-5 rounded-2xl border border-gray-100 dark:border-dark-700/30">
                                <select name="storage_driver"
                                    class="w-full md:w-1/2 px-4 py-2.5 bg-gray-50 dark:bg-dark-800/50 border border-gray-200 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-cyan-500/50 focus:outline-none transition-all">
                                    <option value="local" {{ ($settings['storage_driver'] ?? 'local') == 'local' ? 'selected' : '' }}>Local Storage</option>
                                    <option value="s3" {{ ($settings['storage_driver'] ?? '') == 's3' ? 'selected' : '' }}>
                                        Amazon S3 / Spaces</option>
                                </select>
                            </div>

                            <div class="mt-8 flex justify-end">
                                <button type="submit"
                                    class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 text-white font-medium rounded-xl shadow-lg shadow-blue-500/20 transition-all duration-300 flex items-center gap-2 hover:-translate-y-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Simpan Integrasi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Test Email --}}
                <div class="glass rounded-2xl overflow-hidden p-6 border-l-4 border-l-blue-500">
                    <h4 class="text-base font-semibold text-gray-900 dark:text-white mb-2">Test Koneksi Email</h4>
                    <p class="text-sm text-gray-500 dark:text-dark-400 mb-4">Kirim email test untuk verifikasi SMTP.</p>
                    <form action="{{ route('system-settings.test-email') }}" method="POST"
                        class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                        @csrf
                        <input type="email" name="test_email" placeholder="Alamat email test..." required
                            class="flex-1 max-w-sm px-4 py-2.5 bg-gray-50 dark:bg-dark-800/50 border border-gray-200 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none transition-all">
                        <button type="submit"
                            class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-all duration-300 flex items-center gap-2 whitespace-nowrap hover:-translate-y-0.5 shadow-lg shadow-blue-500/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            Kirim Test
                        </button>
                    </form>
                </div>
            </div>

            {{-- ====== TAB 3: MAINTENANCE ====== --}}
            <div id="content-maintenance" class="tab-content hidden animate-fade-up">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Maintenance Mode --}}
                    <div class="glass rounded-2xl overflow-hidden h-full flex flex-col relative group">
                        <div
                            class="absolute inset-0 bg-gradient-to-br from-amber-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        </div>
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-dark-700/50 relative z-10">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Maintenance Mode</h3>
                        </div>
                        <div class="p-6 flex-1 flex flex-col items-center justify-center text-center relative z-10">
                            @if(app()->isDownForMaintenance())
                                <div
                                    class="w-20 h-20 rounded-full bg-red-500/10 flex items-center justify-center mb-4 animate-pulse">
                                    <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                    </svg>
                                </div>
                                <h4 class="text-xl font-bold text-red-500 mb-2">Sistem OFFLINE</h4>
                                <p class="text-sm text-gray-600 dark:text-dark-400 mb-6">Aplikasi sedang dalam mode maintenance.
                                </p>
                                <form action="{{ route('system-settings.maintenance') }}" method="POST" class="mt-auto">
                                    @csrf
                                    <button
                                        class="px-6 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-medium rounded-xl shadow-lg shadow-emerald-500/20 transition-all duration-300 flex items-center gap-2 hover:-translate-y-0.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                        Nonaktifkan Maintenance
                                    </button>
                                </form>
                            @else
                                <div class="w-20 h-20 rounded-full bg-emerald-500/10 flex items-center justify-center mb-4">
                                    <svg class="w-10 h-10 text-emerald-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h4 class="text-xl font-bold text-emerald-500 dark:text-emerald-400 mb-2">Sistem ONLINE</h4>
                                <p class="text-sm text-gray-600 dark:text-dark-400 mb-6">Aplikasi aktif. Maintenance mode akan
                                    mengunci akses user non-admin.</p>
                                <form action="{{ route('system-settings.maintenance') }}" method="POST"
                                    onsubmit="return confirm('PERHATIAN: Aplikasi akan offline untuk user biasa. Lanjutkan?');"
                                    class="mt-auto">
                                    @csrf
                                    <button
                                        class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-xl shadow-lg shadow-amber-500/25 transition-all duration-300 flex items-center gap-2 hover:-translate-y-0.5 border border-amber-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                        Aktifkan Maintenance
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    {{-- Database Backup --}}
                    <div class="glass rounded-2xl overflow-hidden h-full flex flex-col relative group">
                        <div
                            class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        </div>
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-dark-700/50 relative z-10">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Database Backup</h3>
                        </div>
                        <div class="p-6 flex-1 flex flex-col items-center justify-center text-center relative z-10">
                            <div class="w-20 h-20 rounded-full bg-blue-500/10 flex items-center justify-center mb-4">
                                <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" />
                                </svg>
                            </div>
                            <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Manual Backup</h4>
                            <p class="text-sm text-gray-600 dark:text-dark-400 mb-2">File SQL akan terdownload langsung.</p>
                            <p class="text-xs text-amber-600 dark:text-amber-400 mb-6">Membutuhkan mysqldump di server</p>
                            <form action="{{ route('system-settings.backup') }}" method="POST" class="mt-auto"
                                onsubmit="return confirm('Mulai backup database?');">
                                @csrf
                                <button
                                    class="px-6 py-2.5 bg-blue-600 hover:bg-blue-500 text-white font-medium rounded-xl shadow-lg shadow-blue-500/20 transition-all duration-300 flex items-center gap-2 hover:-translate-y-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Download Backup
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ====== TAB 4: SYSTEM UPDATE ====== --}}
            <div id="content-update" class="tab-content hidden animate-fade-up">
                <div class="glass rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-dark-700/50">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">System Update (GitHub)</h3>
                        <p class="text-sm text-gray-500 dark:text-dark-400 mt-1">Cek dan tarik pembaruan terbaru dari
                            repository.</p>
                    </div>
                    <div class="p-6">
                        {{-- Version Cards --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div
                                class="bg-gray-50 dark:bg-dark-800/50 rounded-2xl p-6 border border-gray-200 dark:border-dark-700/50 text-center relative overflow-hidden group">
                                <div
                                    class="absolute -right-4 -top-4 w-16 h-16 bg-blue-500/10 rounded-full blur-xl group-hover:bg-blue-500/20 transition-colors">
                                </div>
                                <h6 class="text-sm font-medium text-gray-500 dark:text-dark-400 mb-3">Versi Lokal</h6>
                                <span id="localHash"
                                    class="inline-block px-3 py-1 bg-gray-200 dark:bg-dark-600 text-gray-700 dark:text-dark-300 rounded-lg text-lg font-bold font-mono tracking-wider mb-2">—</span>
                                <p id="localDate" class="text-xs text-gray-500 dark:text-dark-500"></p>
                                <p id="localMessage" class="text-xs text-gray-400 dark:text-dark-500 mt-1 truncate"></p>
                            </div>
                            <div
                                class="bg-gray-50 dark:bg-dark-800/50 rounded-2xl p-6 border border-gray-200 dark:border-dark-700/50 text-center relative overflow-hidden group">
                                <div
                                    class="absolute -right-4 -top-4 w-16 h-16 bg-purple-500/10 rounded-full blur-xl group-hover:bg-purple-500/20 transition-colors">
                                </div>
                                <h6 class="text-sm font-medium text-gray-500 dark:text-dark-400 mb-3">Versi Remote</h6>
                                <span id="remoteHash"
                                    class="inline-block px-3 py-1 bg-gray-200 dark:bg-dark-600 text-gray-700 dark:text-dark-300 rounded-lg text-lg font-bold font-mono tracking-wider mb-2">—</span>
                                <div id="updateBadge" class="hidden text-xs font-semibold animate-pulse mt-1 mb-1"></div>
                                <p id="remoteDate" class="text-xs text-gray-500 dark:text-dark-500"></p>
                                <p id="remoteMessage" class="text-xs text-gray-400 dark:text-dark-500 mt-1 truncate"></p>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div
                            class="text-center py-8 bg-gradient-to-b from-transparent to-gray-50/50 dark:to-dark-800/20 rounded-2xl">
                            <div
                                class="w-16 h-16 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 mx-auto flex items-center justify-center text-white mb-6 shadow-lg shadow-indigo-500/30">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                </svg>
                            </div>
                            <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Manajemen Pembaruan</h4>
                            <p class="text-sm text-gray-600 dark:text-dark-400 mb-8 max-w-md mx-auto">Cek pembaruan, lalu
                                jalankan update: git pull, composer install, dan migrasi database.</p>

                            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                                <button onclick="checkForUpdates()" id="btnCheckUpdate"
                                    class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 text-white font-bold rounded-xl shadow-lg shadow-blue-500/25 transition-all duration-300 flex items-center gap-3 hover:-translate-y-0.5">
                                    <svg id="checkSvgIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    <svg id="checkSvgSpinner" class="w-5 h-5 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="10" stroke-width="2" opacity="0.3"/><path stroke-linecap="round" stroke-width="2" d="M12 2a10 10 0 019.95 9"/></svg>
                                    <span id="checkText">Cek Pembaruan</span>
                                </button>
                                <button onclick="runSystemUpdate()" id="btnRunUpdate"
                                    class="hidden px-8 py-3 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 text-white font-bold rounded-xl shadow-lg shadow-amber-500/30 transition-all duration-300 flex items-center gap-3 hover:-translate-y-0.5" disabled>
                                    <svg id="updateSvgIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    <svg id="updateSvgSpinner" class="w-5 h-5 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="10" stroke-width="2" opacity="0.3"/><path stroke-linecap="round" stroke-width="2" d="M12 2a10 10 0 019.95 9"/></svg>
                                    <span id="updateText">Jalankan Update</span>
                                </button>
                            </div>
                            <p id="branchInfo" class="hidden text-xs text-gray-400 dark:text-dark-500 mt-4">Branch: <span
                                    id="branchName" class="font-mono"></span></p>
                        </div>

                        {{-- Logs --}}
                        <div id="updateLogsContainer" class="mt-8 hidden">
                            <h6 class="text-sm font-bold text-gray-900 dark:text-white mb-3">Update Logs</h6>
                            <div
                                class="bg-gray-900 dark:bg-dark-950 rounded-xl overflow-hidden border border-gray-700 dark:border-dark-700 shadow-inner">
                                <div
                                    class="bg-gray-800 dark:bg-dark-900 px-4 py-2 flex items-center gap-2 border-b border-gray-700 dark:border-dark-700">
                                    <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                    <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                                    <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                                    <span class="text-gray-400 text-xs ml-2 font-mono">system_update.log</span>
                                </div>
                                <div id="updateLogsContent" class="p-4 text-gray-300 font-mono text-xs overflow-y-auto"
                                    style="max-height: 400px; white-space: pre-wrap;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Tab Navigation
        function switchTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(el => { el.classList.add('hidden'); el.classList.remove('block'); });
            const sel = document.getElementById('content-' + tabId);
            if (sel) { sel.classList.remove('hidden'); sel.classList.add('block'); }
            document.querySelectorAll('.nav-tab').forEach(el => {
                el.classList.remove('bg-blue-500/10', 'text-blue-600', 'dark:text-blue-400', 'active-tab');
                el.classList.add('text-gray-600', 'dark:text-dark-300');
            });
            const btn = document.getElementById('tab-' + tabId);
            if (btn) { btn.classList.remove('text-gray-600', 'dark:text-dark-300'); btn.classList.add('bg-blue-500/10', 'text-blue-600', 'dark:text-blue-400', 'active-tab'); }
            if (history.pushState) { window.history.pushState({}, '', window.location.pathname + '?tab=' + tabId); }
        }
        document.addEventListener('DOMContentLoaded', function () {
            const tab = new URLSearchParams(window.location.search).get('tab');
            if (tab && ['general', 'integrations', 'maintenance', 'update'].includes(tab)) switchTab(tab);
        });

        // Password toggle
        function togglePasswordVisibility() {
            const inp = document.getElementById('mailPasswordInput');
            const open = document.getElementById('eyeIconOpen');
            const closed = document.getElementById('eyeIconClosed');
            if (inp.type === 'password') {
                inp.type = 'text';
                open.classList.add('hidden');
                closed.classList.remove('hidden');
            } else {
                inp.type = 'password';
                open.classList.remove('hidden');
                closed.classList.add('hidden');
            }
        }

        // System Update AJAX
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        function setLoading(prefix, loading, msg) {
            const map = {
                'CheckUpdate': { btn: 'btnCheckUpdate', icon: 'checkSvgIcon', spinner: 'checkSvgSpinner', text: 'checkText' },
                'RunUpdate': { btn: 'btnRunUpdate', icon: 'updateSvgIcon', spinner: 'updateSvgSpinner', text: 'updateText' }
            };
            const ids = map[prefix];
            const btn = document.getElementById(ids.btn);
            const icon = document.getElementById(ids.icon);
            const spinner = document.getElementById(ids.spinner);
            const text = document.getElementById(ids.text);

            if (loading) {
                btn.disabled = true;
                btn.classList.add('opacity-75', 'cursor-not-allowed');
                icon.classList.add('hidden');
                spinner.classList.remove('hidden');
                spinner.classList.add('animate-spin');
                text.textContent = msg;
            } else {
                btn.disabled = false;
                btn.classList.remove('opacity-75', 'cursor-not-allowed');
                icon.classList.remove('hidden');
                spinner.classList.add('hidden');
                spinner.classList.remove('animate-spin');
            }
        }

        async function checkForUpdates() {
            setLoading('CheckUpdate', true, 'Mengecek...');
            try {
                const res = await fetch('{{ route("system-update.check") }}', { headers: { 'Accept': 'application/json' } });
                const d = await res.json();
                if (!d.success) throw new Error(d.error);

                const lh = document.getElementById('localHash');
                lh.textContent = d.local_hash;
                lh.className = 'inline-block px-3 py-1 bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400 rounded-lg text-lg font-bold font-mono tracking-wider mb-2';
                document.getElementById('localDate').textContent = 'Updated: ' + d.local_date;
                document.getElementById('localMessage').textContent = d.local_message;

                const rh = document.getElementById('remoteHash'), ub = document.getElementById('updateBadge');
                if (d.has_update) {
                    rh.textContent = d.remote_hash;
                    rh.className = 'inline-block px-3 py-1 bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400 rounded-lg text-lg font-bold font-mono tracking-wider mb-2';
                    ub.className = 'text-xs font-semibold text-amber-600 dark:text-amber-400 animate-pulse mt-1 mb-1';
                    ub.textContent = 'Update Tersedia (' + d.behind_count + ' commit)';
                    const bu = document.getElementById('btnRunUpdate');
                    bu.classList.remove('hidden');
                    bu.disabled = false;
                } else {
                    rh.textContent = d.remote_hash;
                    rh.className = 'inline-block px-3 py-1 bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400 rounded-lg text-lg font-bold font-mono tracking-wider mb-2';
                    ub.className = 'text-xs font-semibold text-emerald-600 dark:text-emerald-400 mt-1 mb-1';
                    ub.textContent = '✓ Sudah versi terbaru';
                    document.getElementById('btnRunUpdate').classList.add('hidden');
                }
                document.getElementById('remoteDate').textContent = 'Latest: ' + d.remote_date;
                document.getElementById('remoteMessage').textContent = d.remote_message;
                document.getElementById('branchInfo').classList.remove('hidden');
                document.getElementById('branchName').textContent = d.branch;
            } catch (e) { alert('Error: ' + e.message); }
            finally {
                setLoading('CheckUpdate', false);
                document.getElementById('checkText').textContent = 'Cek Pembaruan';
            }
        }

        async function runSystemUpdate() {
            if (!confirm('Yakin ingin menjalankan update sistem?')) return;
            setLoading('RunUpdate', true, 'Sedang Update...');
            const lc = document.getElementById('updateLogsContainer'), lo = document.getElementById('updateLogsContent');
            lc.classList.remove('hidden'); lo.textContent = '⏳ Memulai update...\n';
            try {
                const res = await fetch('{{ route("system-update.run") }}', { method: 'POST', headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken } });
                const d = await res.json();
                let log = '';
                if (d.logs) d.logs.forEach(l => { log += '\n' + (l.success ? '✅' : '❌') + ' === ' + l.step + ' ===\n' + l.output + '\n'; });
                if (d.success) { lo.textContent += log + '\n🎉 ' + d.message; setTimeout(() => checkForUpdates(), 2000); }
                else { lo.textContent += log + '\n❌ ' + (d.error || 'Gagal'); if (d.dirty_files) lo.textContent += '\n\nFile belum commit:\n' + d.dirty_files; }
                lo.scrollTop = lo.scrollHeight;
            } catch (e) { lo.textContent += '\n❌ Network: ' + e.message; }
            finally {
                setLoading('RunUpdate', false);
                document.getElementById('updateText').textContent = 'Jalankan Update';
            }
        }
    </script>
@endsection