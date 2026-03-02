<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Spandiv CRM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        .login-bg {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);
        }

        .glow {
            box-shadow: 0 0 60px rgba(59, 130, 246, 0.15), 0 0 120px rgba(139, 92, 246, 0.1);
        }
    </style>
</head>

<body class="h-full login-bg flex items-center justify-center p-4">
    <!-- Decorative elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div
                class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl shadow-2xl shadow-blue-500/25 mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-white">Spandiv CRM</h1>
            <p class="text-slate-400 text-sm mt-1">Digital Agency Management System</p>
        </div>

        <!-- Login Card -->
        <div class="bg-slate-900/80 backdrop-blur-xl border border-slate-700/50 rounded-2xl p-8 glow">
            <h2 class="text-lg font-semibold text-white mb-6">Masuk ke akun Anda</h2>

            @if($errors->any())
                <div class="mb-4 p-3 bg-red-500/10 border border-red-500/20 rounded-xl text-red-400 text-sm">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-300 mb-2">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition-all"
                        placeholder="admin@spandiv.com">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-300 mb-2">Password</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition-all"
                        placeholder="••••••••">
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-slate-400 cursor-pointer">
                        <input type="checkbox" name="remember"
                            class="w-4 h-4 bg-slate-800 border-slate-600 rounded text-blue-500 focus:ring-blue-500/50">
                        Ingat saya
                    </label>
                </div>

                <button type="submit"
                    class="w-full py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/25 hover:shadow-blue-500/40 transition-all duration-300 transform hover:-translate-y-0.5">
                    Masuk
                </button>
            </form>

            <p class="mt-6 text-center text-xs text-slate-500">
                Default: admin@spandiv.com / password
            </p>
        </div>

        <p class="text-center text-xs text-slate-600 mt-6">
            &copy; {{ date('Y') }} Spandiv Digital Solutions. All rights reserved.
        </p>
    </div>
</body>

</html>