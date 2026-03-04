<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — {{ \App\Models\Setting::get('app_name', 'Spandiv CRM') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        .hero-bg {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 40%, #312e81 70%, #0f172a 100%);
            position: relative;
            overflow: hidden;
        }

        .hero-bg::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 600px 400px at 20% 80%, rgba(59, 130, 246, 0.12), transparent),
                radial-gradient(ellipse 500px 300px at 80% 20%, rgba(139, 92, 246, 0.1), transparent),
                radial-gradient(ellipse 300px 300px at 50% 50%, rgba(56, 189, 248, 0.06), transparent);
        }

        .login-panel {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(40px);
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .feature-card:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }

        .float-anim {
            animation: floatUp 6s ease-in-out infinite;
        }

        .float-anim-delay {
            animation: floatUp 6s ease-in-out 2s infinite;
        }

        .float-anim-delay2 {
            animation: floatUp 6s ease-in-out 4s infinite;
        }

        @keyframes floatUp {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-12px);
            }
        }

        .pulse-ring {
            animation: pulseRing 3s ease-out infinite;
        }

        @keyframes pulseRing {
            0% {
                transform: scale(1);
                opacity: 0.6;
            }

            100% {
                transform: scale(1.8);
                opacity: 0;
            }
        }

        .gradient-text {
            background: linear-gradient(135deg, #60a5fa, #a78bfa, #f472b6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .input-glow:focus {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2), 0 0 20px rgba(99, 102, 241, 0.1);
        }

        .stat-number {
            background: linear-gradient(180deg, #fff 0%, rgba(255, 255, 255, 0.6) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>

<body class="h-full hero-bg">
    <div class="relative flex h-full min-h-screen max-w-[1100px] mx-auto">

        {{-- ====== LEFT PANEL: Features ====== --}}
        <div class="hidden lg:flex lg:w-1/2 xl:w-[55%] flex-col justify-between p-10 xl:p-14 relative z-10">

            {{-- Floating decorative orbs --}}
            <div class="absolute top-20 right-12 w-64 h-64 bg-blue-500/8 rounded-full blur-3xl float-anim"></div>
            <div class="absolute bottom-32 left-8 w-48 h-48 bg-purple-500/8 rounded-full blur-3xl float-anim-delay">
            </div>
            <div class="absolute top-1/2 right-1/3 w-32 h-32 bg-cyan-500/6 rounded-full blur-2xl float-anim-delay2">
            </div>

            {{-- Top: Brand --}}
            <div class="relative">
                <div class="flex items-center gap-3 mb-16">
                    @if(\App\Models\Setting::get('system_logo'))
                        <img src="{{ asset(\App\Models\Setting::get('system_logo')) }}" alt="Logo"
                            class="w-12 h-12 rounded-xl object-contain">
                    @else
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/25">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                    @endif
                    <div>
                        <h2 class="text-xl font-bold text-white">{{ \App\Models\Setting::get('app_name', 'Spandiv') }}
                        </h2>
                        <p class="text-xs text-slate-400">{{ \App\Models\Setting::get('app_tagline', 'CRM System') }}
                        </p>
                    </div>
                </div>

                {{-- Hero text --}}
                <h1 class="text-4xl xl:text-5xl font-extrabold text-white leading-tight mb-4">
                    Kelola bisnis Anda
                    <br><span class="gradient-text">lebih cerdas.</span>
                </h1>
                <p class="text-lg text-slate-400 max-w-lg leading-relaxed mb-12">
                    Platform CRM all-in-one untuk mengelola lead, klien, project,
                    invoice, dan pembayaran dalam satu dashboard terpadu.
                </p>

                {{-- Feature Cards --}}
                <div class="grid grid-cols-2 gap-4 max-w-lg">
                    <div class="feature-card rounded-2xl p-5">
                        <div class="w-10 h-10 bg-blue-500/15 rounded-xl flex items-center justify-center mb-3">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-white mb-1">Lead & Client</h3>
                        <p class="text-xs text-slate-400 leading-relaxed">Kelola pipeline penjualan dari lead hingga
                            konversi</p>
                    </div>

                    <div class="feature-card rounded-2xl p-5">
                        <div class="w-10 h-10 bg-purple-500/15 rounded-xl flex items-center justify-center mb-3">
                            <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-white mb-1">Project Tracking</h3>
                        <p class="text-xs text-slate-400 leading-relaxed">Monitor progres project real-time dengan
                            timeline</p>
                    </div>

                    <div class="feature-card rounded-2xl p-5">
                        <div class="w-10 h-10 bg-emerald-500/15 rounded-xl flex items-center justify-center mb-3">
                            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-white mb-1">Invoice & Payment</h3>
                        <p class="text-xs text-slate-400 leading-relaxed">Generate invoice otomatis & lacak pembayaran
                        </p>
                    </div>

                    <div class="feature-card rounded-2xl p-5">
                        <div class="w-10 h-10 bg-amber-500/15 rounded-xl flex items-center justify-center mb-3">
                            <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-white mb-1">Dashboard Analytics</h3>
                        <p class="text-xs text-slate-400 leading-relaxed">Visualisasi data bisnis dengan grafik
                            interaktif</p>
                    </div>
                </div>
            </div>

            {{-- Bottom: Stats --}}
            <div class="relative flex items-center gap-10 mt-12">
                <div>
                    <p class="stat-number text-3xl font-extrabold">100%</p>
                    <p class="text-xs text-slate-500 mt-0.5">Self-Hosted</p>
                </div>
                <div class="w-px h-10 bg-slate-700/50"></div>
                <div>
                    <p class="stat-number text-3xl font-extrabold">∞</p>
                    <p class="text-xs text-slate-500 mt-0.5">Unlimited Users</p>
                </div>
                <div class="w-px h-10 bg-slate-700/50"></div>
                <div>
                    <p class="stat-number text-3xl font-extrabold">24/7</p>
                    <p class="text-xs text-slate-500 mt-0.5">Activity Monitoring</p>
                </div>
            </div>
        </div>

        {{-- ====== RIGHT PANEL: Login Form ====== --}}
        <div class="w-full lg:w-1/2 xl:w-[45%] flex items-center justify-center p-6 sm:p-8 lg:p-10 relative z-10">
            {{-- Glass panel --}}
            <div
                class="login-panel w-full max-w-[420px] rounded-3xl border border-white/10 p-8 sm:p-10 relative overflow-hidden">
                {{-- Decorative corner glow --}}
                <div class="absolute -top-20 -right-20 w-40 h-40 bg-blue-500/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-20 -left-20 w-40 h-40 bg-purple-500/10 rounded-full blur-3xl"></div>

                {{-- Mobile Logo (hidden on desktop) --}}
                <div class="lg:hidden text-center mb-8">
                    <div
                        class="inline-flex items-center justify-center w-14 h-14 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl shadow-2xl shadow-blue-500/25 mb-3">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h1 class="text-xl font-bold text-white">{{ \App\Models\Setting::get('app_name', 'Spandiv CRM') }}
                    </h1>
                    <p class="text-slate-400 text-sm mt-0.5">{{ \App\Models\Setting::get('app_tagline', 'CRM System') }}
                    </p>
                </div>

                {{-- Form Header --}}
                <div class="relative mb-8">
                    <h2 class="text-2xl font-bold text-white">Selamat Datang 👋</h2>
                    <p class="text-sm text-slate-400 mt-1.5">Masukkan kredensial untuk mengakses dashboard</p>
                </div>

                {{-- Error Messages --}}
                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-xl relative overflow-hidden">
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-red-500"></div>
                        @foreach($errors->all() as $error)
                            <p class="text-sm text-red-400 pl-3">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                {{-- Login Form --}}
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-300 mb-2">Email</label>
                        <div class="relative">
                            <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-slate-500" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                                class="w-full pl-12 pr-4 py-3.5 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500/50 input-glow transition-all text-sm"
                                placeholder="nama@perusahaan.com">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-300 mb-2">Password</label>
                        <div class="relative">
                            <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-slate-500" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <input type="password" name="password" id="password" required
                                class="w-full pl-12 pr-4 py-3.5 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500/50 input-glow transition-all text-sm"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2.5 text-sm text-slate-400 cursor-pointer group">
                            <input type="checkbox" name="remember"
                                class="w-4 h-4 bg-white/5 border-white/20 rounded text-indigo-500 focus:ring-indigo-500/30 focus:ring-offset-0">
                            <span class="group-hover:text-slate-300 transition-colors">Ingat saya</span>
                        </label>
                    </div>

                    <button type="submit"
                        class="w-full py-3.5 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 hover:from-blue-500 hover:via-indigo-500 hover:to-purple-500 text-white font-semibold rounded-xl shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transition-all duration-300 transform hover:-translate-y-0.5 flex items-center justify-center gap-2 text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        Masuk ke Dashboard
                    </button>
                </form>

                {{-- Footer --}}
                <div class="mt-8 pt-6 border-t border-white/5 text-center">
                    <p class="text-xs text-slate-500">
                        &copy; {{ date('Y') }} {{ \App\Models\Setting::get('app_name', 'Spandiv Digital Solutions') }}
                    </p>
                    <p class="text-xs text-slate-600 mt-1">All rights reserved.</p>
                </div>
            </div>
        </div>

    </div>
</body>

</html>