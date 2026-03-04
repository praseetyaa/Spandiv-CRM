<!DOCTYPE html>
<html lang="id" class="h-full dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — {{ \App\Models\Setting::get('app_name', 'Spandiv CRM') }}</title>
    <meta name="description" content="{{ \App\Models\Setting::get('app_name', 'Spandiv CRM') }} - Internal CRM">
    @if(\App\Models\Setting::get('system_favicon'))
        <link rel="icon" href="{{ asset(\App\Models\Setting::get('system_favicon')) }}" type="image/png">
    @endif

    {{-- Apply saved theme before render to prevent flash --}}
    <script>
        if (localStorage.getItem('theme') === 'light') document.documentElement.classList.remove('dark');
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>


    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .dark ::-webkit-scrollbar-track {
            background: #1e293b;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: #475569;
        }

        .dark ::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }

        /* Glass */
        .glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 0, 0, 0.06);
        }

        .dark .glass {
            background: rgba(30, 41, 59, 0.8);
            border: 1px solid rgba(148, 163, 184, 0.1);
        }

        .gradient-text {
            background: linear-gradient(135deg, #60a5fa, #a78bfa, #f472b6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-card {
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
        }

        .dark .stat-card:hover {
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .nav-link {
            transition: all 0.2s ease;
        }

        .nav-link:hover {
            background: rgba(59, 130, 246, 0.08);
            border-left: 3px solid #3b82f6;
            padding-left: 13px;
        }

        .dark .nav-link:hover {
            background: rgba(59, 130, 246, 0.1);
        }

        .nav-link.active {
            background: rgba(59, 130, 246, 0.1);
            border-left: 3px solid #3b82f6;
            padding-left: 13px;
            color: #2563eb;
        }

        .dark .nav-link.active {
            background: rgba(59, 130, 246, 0.15);
            color: #60a5fa;
        }

        .table-row:hover {
            background: rgba(59, 130, 246, 0.04);
        }

        .dark .table-row:hover {
            background: rgba(59, 130, 246, 0.05);
        }

        .badge {
            transition: all 0.2s ease;
        }

        .badge:hover {
            transform: scale(1.05);
        }

        .theme-toggle {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .theme-toggle:hover {
            transform: rotate(15deg) scale(1.1);
        }

        .notif-dropdown {
            transform: translateY(-10px);
            opacity: 0;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            pointer-events: none;
            visibility: hidden;
        }

        .notif-dropdown.show {
            transform: translateY(0);
            opacity: 1;
            pointer-events: all;
            visibility: visible;
        }
    </style>

    @stack('styles')
</head>

<body class="h-full bg-gray-50 dark:bg-dark-950 text-gray-700 dark:text-dark-200">
    <div class="flex h-full">
        <!-- Sidebar -->
        <aside
            class="w-64 bg-white dark:bg-dark-900 border-r border-gray-200 dark:border-dark-700/50 flex flex-col fixed h-full z-30 transition-colors duration-300"
            id="sidebar">
            <!-- Logo -->
            <div class="p-6 border-b border-gray-200 dark:border-dark-700/50">
                <div class="flex items-center gap-3">
                    @if(\App\Models\Setting::get('system_logo'))
                        <img src="{{ asset(\App\Models\Setting::get('system_logo')) }}" alt="Logo"
                            class="w-10 h-10 rounded-xl object-contain">
                    @else
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                    @endif
                    <div>
                        <h1 class="text-lg font-bold text-gray-900 dark:text-white">
                            {{ \App\Models\Setting::get('app_name', 'Spandiv') }}</h1>
                        <p class="text-xs text-gray-400 dark:text-dark-400">
                            {{ \App\Models\Setting::get('app_tagline', 'CRM System') }}</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                <p class="text-xs text-gray-400 dark:text-dark-500 uppercase tracking-wider font-semibold mb-3 px-4">
                    Menu Utama</p>

                <a href="{{ route('dashboard') }}"
                    class="nav-link nav-icon-animated flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm {{ request()->routeIs('dashboard') ? 'active' : 'text-gray-600 dark:text-dark-300 hover:text-gray-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    Dashboard
                </a>

                <p
                    class="text-xs text-gray-400 dark:text-dark-500 uppercase tracking-wider font-semibold mt-6 mb-3 px-4">
                    Penjualan</p>

                <a href="{{ route('leads.index') }}"
                    class="nav-link nav-icon-animated flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm {{ request()->routeIs('leads.*') ? 'active' : 'text-gray-600 dark:text-dark-300 hover:text-gray-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Lead
                </a>

                <a href="{{ route('clients.index') }}"
                    class="nav-link nav-icon-animated flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm {{ request()->routeIs('clients.*') ? 'active' : 'text-gray-600 dark:text-dark-300 hover:text-gray-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Client
                </a>

                <p
                    class="text-xs text-gray-400 dark:text-dark-500 uppercase tracking-wider font-semibold mt-6 mb-3 px-4">
                    Operasional</p>

                <a href="{{ route('services.index') }}"
                    class="nav-link nav-icon-animated flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm {{ request()->routeIs('services.*') ? 'active' : 'text-gray-600 dark:text-dark-300 hover:text-gray-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                    Layanan
                </a>

                <a href="{{ route('projects.index') }}"
                    class="nav-link nav-icon-animated flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm {{ request()->routeIs('projects.*') ? 'active' : 'text-gray-600 dark:text-dark-300 hover:text-gray-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    Project
                </a>

                <a href="{{ route('subscriptions.index') }}"
                    class="nav-link nav-icon-animated flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm {{ request()->routeIs('subscriptions.*') ? 'active' : 'text-gray-600 dark:text-dark-300 hover:text-gray-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Subscription
                </a>

                <p
                    class="text-xs text-gray-400 dark:text-dark-500 uppercase tracking-wider font-semibold mt-6 mb-3 px-4">
                    Keuangan</p>

                <a href="{{ route('invoices.index') }}"
                    class="nav-link nav-icon-animated flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm {{ request()->routeIs('invoices.*') ? 'active' : 'text-gray-600 dark:text-dark-300 hover:text-gray-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Invoice
                </a>

                <a href="{{ route('payments.index') }}"
                    class="nav-link nav-icon-animated flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm {{ request()->routeIs('payments.*') ? 'active' : 'text-gray-600 dark:text-dark-300 hover:text-gray-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    Pembayaran
                </a>

                <p
                    class="text-xs text-gray-400 dark:text-dark-500 uppercase tracking-wider font-semibold mt-6 mb-3 px-4">
                    Sistem</p>

                <a href="{{ route('activities.index') }}"
                    class="nav-link nav-icon-animated flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm {{ request()->routeIs('activities.*') ? 'active' : 'text-gray-600 dark:text-dark-300 hover:text-gray-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Activity Log
                </a>

                @if(auth()->user()->canManageUsers())
                    <a href="{{ route('users.index') }}"
                        class="nav-link nav-icon-animated flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm {{ request()->routeIs('users.*') ? 'active' : 'text-gray-600 dark:text-dark-300 hover:text-gray-900 dark:hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Pengguna
                    </a>
                @endif

                @if(auth()->user()->isSuperAdmin())
                    <a href="{{ route('companies.index') }}"
                        class="nav-link nav-icon-animated flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm {{ request()->routeIs('companies.*') ? 'active' : 'text-gray-600 dark:text-dark-300 hover:text-gray-900 dark:hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Perusahaan
                    </a>

                    <a href="{{ route('system-settings.index') }}"
                        class="nav-link nav-icon-animated flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm {{ request()->routeIs('system-settings.*', 'system-update.*') ? 'active' : 'text-gray-600 dark:text-dark-300 hover:text-gray-900 dark:hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        System Settings
                    </a>
                @endif
            </nav>

            <!-- User Section -->
            <div class="p-4 border-t border-gray-200 dark:border-dark-700/50">
                <div class="flex items-center gap-3">
                    <div
                        class="w-9 h-9 bg-gradient-to-br from-emerald-400 to-cyan-500 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                        {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                            {{ auth()->user()->name ?? 'Admin' }}
                        </p>
                        <p class="text-xs text-gray-400 dark:text-dark-400 truncate">{{ auth()->user()->email ?? '' }}
                        </p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="text-gray-400 dark:text-dark-400 hover:text-red-400 transition-colors"
                            title="Logout">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 ml-64 min-h-full">
            <!-- Top Bar -->
            <header class="glass sticky top-0 z-20 px-8 py-4 border-b border-gray-200/50 dark:border-dark-700/30">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">@yield('title', 'Dashboard')</h2>
                        <p class="text-sm text-gray-500 dark:text-dark-400">@yield('subtitle', '')</p>
                    </div>
                    <div class="flex items-center gap-3">
                        @yield('header-actions')

                        <!-- Notification Bell -->
                        <div class="relative" id="notifContainer">
                            <button onclick="toggleNotifications()"
                                class="relative p-2 rounded-xl text-gray-500 dark:text-dark-400 hover:bg-gray-100 dark:hover:bg-dark-800 hover:text-gray-700 dark:hover:text-white transition-all"
                                title="Notifikasi" id="notifBtn">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <span id="notifBadge"
                                    class="hidden absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] bg-red-500 text-white text-[10px] font-bold rounded-full items-center justify-center animate-pulse">0</span>
                            </button>

                            <!-- Notification Dropdown -->
                            <div id="notifDropdown"
                                class="notif-dropdown absolute right-0 top-12 w-80 bg-white dark:bg-dark-900 border border-gray-200 dark:border-dark-700/50 rounded-2xl shadow-2xl overflow-hidden z-50">
                                <div
                                    class="p-4 border-b border-gray-100 dark:border-dark-700/50 flex items-center justify-between">
                                    <h4 class="font-semibold text-gray-900 dark:text-white text-sm">Notifikasi</h4>
                                    <button onclick="clearAllNotifications()"
                                        class="text-xs text-blue-500 hover:text-blue-600 font-medium">Hapus
                                        Semua</button>
                                </div>
                                <div id="notifList" class="max-h-80 overflow-y-auto">
                                    <div class="p-8 text-center text-gray-400 dark:text-dark-500 text-sm">Tidak ada
                                        notifikasi</div>
                                </div>
                            </div>
                        </div>

                        <!-- Dark/Light Mode Toggle -->
                        <button onclick="toggleTheme()"
                            class="theme-toggle p-2 rounded-xl text-gray-500 dark:text-dark-400 hover:bg-gray-100 dark:hover:bg-dark-800 hover:text-gray-700 dark:hover:text-white transition-all"
                            title="Ganti Tema" id="themeToggle">
                            <!-- Sun icon (visible in dark mode) -->
                            <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <!-- Moon icon (visible in light mode) -->
                            <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="p-8">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl text-emerald-600 dark:text-emerald-400 text-sm flex items-center gap-3"
                        id="flash-success">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ session('success') }}
                        <button onclick="document.getElementById('flash-success').remove()"
                            class="ml-auto text-emerald-400/60 hover:text-emerald-400">✕</button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-xl text-red-600 dark:text-red-400 text-sm flex items-center gap-3"
                        id="flash-error">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        {{ session('error') }}
                        <button onclick="document.getElementById('flash-error').remove()"
                            class="ml-auto text-red-400/60 hover:text-red-400">✕</button>
                    </div>
                @endif

                @if($errors->any())
                    <div
                        class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-xl text-red-600 dark:text-red-400 text-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
        <div
            class="relative bg-white dark:bg-dark-900 border border-gray-200 dark:border-dark-700/50 rounded-2xl p-6 w-full max-w-md mx-4 shadow-2xl">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-red-500/10 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Konfirmasi Hapus</h3>
                    <p class="text-sm text-gray-500 dark:text-dark-400">Apakah Anda yakin ingin menghapus data ini?
                        Tindakan ini tidak dapat dibatalkan.</p>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeDeleteModal()"
                    class="px-4 py-2 bg-gray-200 dark:bg-dark-700 hover:bg-gray-300 dark:hover:bg-dark-600 text-gray-700 dark:text-white text-sm font-medium rounded-xl transition-colors">
                    Batal
                </button>
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-4 py-2 bg-red-600 hover:bg-red-500 text-white text-sm font-medium rounded-xl transition-colors">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // ===== THEME TOGGLE =====
        function toggleTheme() {
            const html = document.documentElement;
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        }

        // ===== NOTIFICATION SYSTEM =====
        const NOTIF_KEY = 'spandiv_notifications';

        function getNotifications() {
            return JSON.parse(localStorage.getItem(NOTIF_KEY) || '[]');
        }

        function saveNotifications(notifs) {
            localStorage.setItem(NOTIF_KEY, JSON.stringify(notifs));
            updateNotifUI();
        }

        function addNotification(type, message) {
            const notifs = getNotifications();
            notifs.unshift({ id: Date.now(), type, message, time: new Date().toISOString(), read: false });
            if (notifs.length > 20) notifs.pop();
            saveNotifications(notifs);
        }

        function updateNotifUI() {
            const notifs = getNotifications();
            const unread = notifs.filter(n => !n.read).length;
            const badge = document.getElementById('notifBadge');
            if (badge) {
                if (unread > 0) {
                    badge.textContent = unread > 9 ? '9+' : unread;
                    badge.classList.remove('hidden');
                    badge.classList.add('flex');
                } else {
                    badge.classList.add('hidden');
                    badge.classList.remove('flex');
                }
            }
            renderNotifications();
        }

        function renderNotifications() {
            const notifs = getNotifications();
            const list = document.getElementById('notifList');
            if (!list) return;
            if (notifs.length === 0) {
                list.innerHTML = '<div class="p-8 text-center text-gray-400 dark:text-dark-500 text-sm">Tidak ada notifikasi</div>';
                return;
            }
            list.innerHTML = notifs.map(n => {
                const icon = n.type === 'success' ? '✅' : n.type === 'error' ? '❌' : 'ℹ️';
                const bg = n.read ? '' : 'bg-blue-50 dark:bg-blue-500/5';
                const diff = (Date.now() - new Date(n.time).getTime()) / 1000;
                const timeAgo = diff < 60 ? 'Baru saja' : diff < 3600 ? Math.floor(diff / 60) + 'm lalu' : diff < 86400 ? Math.floor(diff / 3600) + 'j lalu' : Math.floor(diff / 86400) + 'h lalu';
                return `<div class="flex items-start gap-3 p-4 border-b border-gray-100 dark:border-dark-700/30 ${bg} hover:bg-gray-50 dark:hover:bg-dark-800/50 transition-colors">
                    <span class="text-base flex-shrink-0 mt-0.5">${icon}</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-700 dark:text-dark-200">${n.message}</p>
                        <p class="text-xs text-gray-400 dark:text-dark-500 mt-1">${timeAgo}</p>
                    </div>
                    <button onclick="event.stopPropagation(); removeNotification(${n.id})" class="text-gray-300 dark:text-dark-600 hover:text-red-400 transition-colors flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>`;
            }).join('');
        }

        function toggleNotifications() {
            const dd = document.getElementById('notifDropdown');
            const isOpen = dd.classList.contains('show');
            dd.classList.toggle('show');
            if (!isOpen) {
                const notifs = getNotifications().map(n => ({ ...n, read: true }));
                saveNotifications(notifs);
            }
        }

        function removeNotification(id) {
            saveNotifications(getNotifications().filter(n => n.id !== id));
        }

        function clearAllNotifications() {
            saveNotifications([]);
        }

        // Close dropdowns on outside click
        document.addEventListener('click', (e) => {
            const nc = document.getElementById('notifContainer');
            if (nc && !nc.contains(e.target)) {
                document.getElementById('notifDropdown').classList.remove('show');
            }
        });

        // ===== FLASH MESSAGES =====
        setTimeout(() => {
            const flash = document.getElementById('flash-success');
            if (flash) { flash.style.transition = 'opacity 0.5s'; flash.style.opacity = '0'; setTimeout(() => flash.remove(), 500); }
        }, 5000);
        setTimeout(() => {
            const flash = document.getElementById('flash-error');
            if (flash) { flash.style.transition = 'opacity 0.5s'; flash.style.opacity = '0'; setTimeout(() => flash.remove(), 500); }
        }, 8000);

        // ===== DELETE MODAL =====
        function confirmDelete(url) {
            const modal = document.getElementById('deleteModal');
            document.getElementById('deleteForm').action = url;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeDeleteModal(); });

        // ===== INIT =====
        document.addEventListener('DOMContentLoaded', () => { updateNotifUI(); });
    </script>

    {{-- Auto-add flash to notifications --}}
    @if(session('success'))
        <script>
            addNotification('success', @json(session('success')));
            if (typeof window.launchConfetti === 'function') window.launchConfetti();
        </script>
    @endif
    @if(session('error'))
        <script>addNotification('error', @json(session('error')));</script>
    @endif

    {{-- Server-side smart alerts --}}
    @if(isset($systemAlerts) && count($systemAlerts) > 0)
        <script>
            (function () {
                const alerts = @json($systemAlerts);
                const ALERT_KEY = 'spandiv_shown_alerts';
                const shown = JSON.parse(localStorage.getItem(ALERT_KEY) || '{}');
                const today = new Date().toISOString().slice(0, 10);

                // Clear old entries (older than today)
                Object.keys(shown).forEach(k => { if (shown[k] !== today) delete shown[k]; });

                alerts.forEach(a => {
                    const key = btoa(a.message).slice(0, 20);
                    if (!shown[key]) {
                        addNotification(a.type, a.icon + ' ' + a.message);
                        shown[key] = today;
                    }
                });

                localStorage.setItem(ALERT_KEY, JSON.stringify(shown));
            })();
        </script>
    @endif

    {{-- Welcome notification (first visit) --}}
    <script>
        (function () {
            if (!localStorage.getItem('spandiv_welcomed')) {
                addNotification('info', '👋 Selamat datang di Spandiv CRM! Gunakan menu sidebar untuk navigasi.');
                localStorage.setItem('spandiv_welcomed', 'true');
            }
        })();
    </script>

    @stack('scripts')
</body>

</html>