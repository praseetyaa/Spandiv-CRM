<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Terima Kasih — {{ $company->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        .hero-bg {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 40%, #312e81 70%, #0f172a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .hero-bg::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 600px 400px at 20% 80%, rgba(59, 130, 246, 0.12), transparent),
                radial-gradient(ellipse 500px 300px at 80% 20%, rgba(139, 92, 246, 0.1), transparent);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(40px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .gradient-text {
            background: linear-gradient(135deg, #60a5fa, #a78bfa, #f472b6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .success-icon {
            animation: bounceIn 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes bounceIn {
            0% {
                transform: scale(0);
                opacity: 0;
            }

            60% {
                transform: scale(1.15);
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .confetti {
            animation: confettiFall 3s ease-in-out infinite;
        }

        @keyframes confettiFall {
            0% {
                transform: translateY(-10px) rotate(0deg);
                opacity: 1;
            }

            100% {
                transform: translateY(30px) rotate(360deg);
                opacity: 0;
            }
        }
    </style>
</head>

<body class="hero-bg">
    <div class="relative z-10 max-w-md mx-auto px-4 text-center">
        <div class="glass-card rounded-3xl p-10">
            {{-- Success Icon --}}
            <div
                class="success-icon w-20 h-20 bg-emerald-500/15 rounded-full mx-auto flex items-center justify-center mb-6">
                <svg class="w-10 h-10 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <h1 class="text-2xl font-bold text-white mb-3">
                Terima Kasih! <span class="gradient-text">🎉</span>
            </h1>
            <p class="text-slate-400 text-sm leading-relaxed mb-6">
                Requirements Anda berhasil dikirim. Tim kami akan segera menghubungi Anda melalui WhatsApp untuk diskusi
                lebih lanjut.
            </p>

            <div class="p-4 bg-blue-500/5 border border-blue-500/10 rounded-xl mb-6">
                <p class="text-xs text-blue-400 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Estimasi respon: 1x24 jam kerja
                </p>
            </div>

            <a href="{{ route('requirements.form', $formSlug) }}"
                class="inline-flex items-center gap-2 px-6 py-3 bg-white/5 border border-white/10 text-white text-sm font-medium rounded-xl hover:bg-white/10 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                </svg>
                Kembali ke Form
            </a>
        </div>

        <p class="text-xs text-slate-600 mt-6">
            &copy; {{ date('Y') }} {{ $company->name }}
        </p>
    </div>
</body>

</html>