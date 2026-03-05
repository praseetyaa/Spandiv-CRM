<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ajukan Requirements — {{ $company->name }}</title>
    @if(isset($company) && $company->logo)
        <link rel="icon" href="{{ asset($company->logo) }}" type="image/png">
    @elseif(\App\Models\Setting::get('system_favicon'))
        <link rel="icon" href="{{ asset(\App\Models\Setting::get('system_favicon')) }}" type="image/png">
    @else
        <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }

        .hero-bg {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 40%, #312e81 70%, #0f172a 100%);
            position: relative;
            overflow-x: hidden;
            min-height: 100vh;
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
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

        .glass-card {
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(40px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 0.75rem;
            color: #fff;
            font-size: 0.875rem;
            transition: all 0.3s;
            outline: none;
        }
        .form-input::placeholder { color: #64748b; }
        .form-input:focus {
            border-color: rgba(99, 102, 241, 0.5);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15), 0 0 20px rgba(99, 102, 241, 0.1);
        }
        .form-input option { background: #1e293b; color: #fff; }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #cbd5e1;
            margin-bottom: 0.5rem;
        }

        .gradient-text {
            background: linear-gradient(135deg, #60a5fa, #a78bfa, #f472b6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .btn-primary {
            padding: 0.875rem 2rem;
            background: linear-gradient(135deg, #3b82f6, #6366f1, #8b5cf6);
            color: #fff;
            font-weight: 600;
            border-radius: 0.75rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 0.875rem;
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(99, 102, 241, 0.4);
        }
        .btn-primary:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        .step-indicator {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .step-dot {
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 600;
            border: 2px solid rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.3);
            transition: all 0.4s;
        }
        .step-dot.active {
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            border-color: transparent;
            color: #fff;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
        }
        .step-dot.completed {
            background: #10b981;
            border-color: transparent;
            color: #fff;
        }
        .step-line {
            flex: 1;
            height: 2px;
            background: rgba(255,255,255,0.1);
            transition: background 0.4s;
        }
        .step-line.active {
            background: linear-gradient(90deg, #10b981, #3b82f6);
        }

        .form-step { display: none; }
        .form-step.active { display: block; animation: fadeInUp 0.4s ease; }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .checkbox-card, .radio-card {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 0.75rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .checkbox-card:hover, .radio-card:hover {
            background: rgba(255,255,255,0.06);
            border-color: rgba(99, 102, 241, 0.3);
        }
        .checkbox-card input:checked + span,
        .radio-card input:checked + span {
            color: #a78bfa;
        }
        .checkbox-card:has(input:checked),
        .radio-card:has(input:checked) {
            background: rgba(99, 102, 241, 0.1);
            border-color: rgba(99, 102, 241, 0.3);
        }

        .float-anim { animation: floatUp 6s ease-in-out infinite; }
        .float-anim-delay { animation: floatUp 6s ease-in-out 2s infinite; }
        @keyframes floatUp {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-12px); }
        }

        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255,255,255,0.2);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Hidden honeypot */
        .hp-field { position: absolute; left: -9999px; opacity: 0; height: 0; overflow: hidden; }
    </style>
</head>

<body class="hero-bg">
    {{-- Floating orbs --}}
    <div class="absolute top-20 right-20 w-72 h-72 bg-blue-500/10 rounded-full blur-3xl float-anim"></div>
    <div class="absolute bottom-20 left-20 w-56 h-56 bg-purple-500/10 rounded-full blur-3xl float-anim-delay"></div>

    <div class="relative z-10 max-w-3xl mx-auto px-4 py-8 sm:py-12">

        {{-- Header --}}
        <div class="text-center mb-10">
            <div class="flex items-center justify-center gap-3 mb-6">
                @if($company->logo)
                    <img src="{{ asset($company->logo) }}" alt="{{ $company->name }}"
                        class="w-12 h-12 rounded-xl object-contain">
                @else
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/25">
                        <span class="text-xl font-bold text-white">{{ substr($company->name, 0, 1) }}</span>
                    </div>
                @endif
                <div class="text-left">
                    <h2 class="text-xl font-bold text-white">{{ $company->name }}</h2>
                    <p class="text-xs text-slate-400">Requirements Form</p>
                </div>
            </div>

            <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-3">
                Ajukan <span class="gradient-text">Requirements</span>
            </h1>
            <p class="text-slate-400 text-sm sm:text-base max-w-lg mx-auto">
                Ceritakan kebutuhan Anda dan kami akan memberikan solusi terbaik. Isi form di bawah ini untuk memulai.
            </p>
        </div>

        {{-- Step Indicator --}}
        <div class="step-indicator max-w-md mx-auto mb-8">
            <div class="step-dot active" data-step="1" id="stepDot1">1</div>
            <div class="step-line" id="stepLine1"></div>
            <div class="step-dot" data-step="2" id="stepDot2">2</div>
            <div class="step-line" id="stepLine2"></div>
            <div class="step-dot" data-step="3" id="stepDot3">3</div>
        </div>

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-xl">
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-red-500"></div>
                @foreach($errors->all() as $error)
                    <p class="text-sm text-red-400 pl-3">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('requirements.submit', $formSlug) }}" id="requirementForm">
            @csrf

            {{-- Honeypot (invisible to users, bots fill this) --}}
            <div class="hp-field" aria-hidden="true">
                <label for="website_url_confirm">Leave empty</label>
                <input type="text" name="website_url_confirm" id="website_url_confirm" tabindex="-1" autocomplete="off">
            </div>

            {{-- ========== STEP 1: Personal Info ========== --}}
            <div class="form-step active" id="step1">
                <div class="glass-card rounded-2xl p-6 sm:p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-blue-500/15 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-white">Data Diri</h3>
                            <p class="text-xs text-slate-400">Informasi kontak Anda</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="form-label">Nama Lengkap <span class="text-red-400">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="form-input" placeholder="Nama lengkap Anda">
                        </div>
                        <div>
                            <label class="form-label">Nama Perusahaan/Brand</label>
                            <input type="text" name="company_name" value="{{ old('company_name') }}" class="form-input" placeholder="PT. / CV. / Brand Anda">
                        </div>
                        <div>
                            <label class="form-label">No. WhatsApp <span class="text-red-400">*</span></label>
                            <input type="text" name="phone" value="{{ old('phone') }}" required class="form-input" placeholder="08xxxxxxxxxx">
                        </div>
                        <div>
                            <label class="form-label">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-input" placeholder="email@perusahaan.com">
                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="button" onclick="goToStep(2)" class="btn-primary flex items-center gap-2">
                            Lanjut
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- ========== STEP 2: Service & Requirements ========== --}}
            <div class="form-step" id="step2">
                <div class="glass-card rounded-2xl p-6 sm:p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-purple-500/15 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-white">Layanan & Requirements</h3>
                            <p class="text-xs text-slate-400">Pilih layanan dan isi kebutuhan Anda</p>
                        </div>
                    </div>

                    {{-- Service Selection --}}
                    <div class="mb-6">
                        <label class="form-label">Pilih Layanan <span class="text-red-400">*</span></label>
                        <select name="service_id" id="serviceSelect" required class="form-input">
                            <option value="">— Pilih Layanan —</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" data-category="{{ $service->category }}"
                                    {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }} — {{ ucfirst(str_replace('_', ' ', $service->category)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Dynamic Fields Container --}}
                    <div id="dynamicFields" class="space-y-5">
                        <div class="text-center py-8 text-slate-500 text-sm">
                            <svg class="w-12 h-12 mx-auto mb-3 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Pilih layanan di atas untuk melihat form requirements
                        </div>
                    </div>

                    {{-- Loading indicator --}}
                    <div id="fieldsLoading" class="hidden text-center py-8">
                        <div class="loading-spinner mx-auto mb-3"></div>
                        <p class="text-slate-400 text-sm">Memuat form requirements...</p>
                    </div>

                    <div class="flex justify-between mt-6">
                        <button type="button" onclick="goToStep(1)" class="px-5 py-3 bg-white/5 border border-white/10 text-white text-sm font-medium rounded-xl hover:bg-white/10 transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                            </svg>
                            Kembali
                        </button>
                        <button type="button" onclick="goToStep(3)" class="btn-primary flex items-center gap-2">
                            Lanjut
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- ========== STEP 3: Budget & Submit ========== --}}
            <div class="form-step" id="step3">
                <div class="glass-card rounded-2xl p-6 sm:p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-emerald-500/15 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-white">Budget & Catatan</h3>
                            <p class="text-xs text-slate-400">Langkah terakhir sebelum submit</p>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label class="form-label">Estimasi Budget</label>
                            <select name="budget_range" class="form-input">
                                <option value="">— Pilih Range Budget —</option>
                                @foreach(['< Rp 1.000.000', 'Rp 1.000.000 - Rp 3.000.000', 'Rp 3.000.000 - Rp 5.000.000', 'Rp 5.000.000 - Rp 10.000.000', 'Rp 10.000.000 - Rp 25.000.000', '> Rp 25.000.000', 'Belum ditentukan'] as $range)
                                    <option value="{{ $range }}" {{ old('budget_range') == $range ? 'selected' : '' }}>{{ $range }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="form-label">Catatan Tambahan</label>
                            <textarea name="notes" rows="4" class="form-input" placeholder="Ada hal lain yang ingin Anda sampaikan? Tulis di sini...">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    {{-- Summary --}}
                    <div class="mt-6 p-4 bg-indigo-500/5 border border-indigo-500/10 rounded-xl">
                        <p class="text-xs text-slate-400 mb-2 font-medium">📋 Ringkasan</p>
                        <div id="formSummary" class="text-sm text-slate-300 space-y-1"></div>
                    </div>

                    <div class="flex justify-between mt-6">
                        <button type="button" onclick="goToStep(2)" class="px-5 py-3 bg-white/5 border border-white/10 text-white text-sm font-medium rounded-xl hover:bg-white/10 transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                            </svg>
                            Kembali
                        </button>
                        <button type="submit" id="submitBtn" class="btn-primary flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            Kirim Requirements
                        </button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Footer --}}
        <div class="text-center mt-8">
            <p class="text-xs text-slate-600">
                &copy; {{ date('Y') }} {{ $company->name }}.
                Data Anda aman & terlindungi.
            </p>
        </div>
    </div>

    <script>
        let currentStep = 1;
        const totalSteps = 3;

        function goToStep(step) {
            // Validate current step before going forward
            if (step > currentStep && !validateStep(currentStep)) return;

            // Hide all steps
            document.querySelectorAll('.form-step').forEach(s => s.classList.remove('active'));
            // Show target step
            document.getElementById('step' + step).classList.add('active');

            // Update indicators
            for (let i = 1; i <= totalSteps; i++) {
                const dot = document.getElementById('stepDot' + i);
                const line = document.getElementById('stepLine' + (i - 1));
                dot.classList.remove('active', 'completed');
                if (i < step) {
                    dot.classList.add('completed');
                    dot.innerHTML = '✓';
                } else if (i === step) {
                    dot.classList.add('active');
                    dot.innerHTML = i;
                } else {
                    dot.innerHTML = i;
                }
                if (line) {
                    line.classList.toggle('active', i < step);
                }
            }

            currentStep = step;

            // Update summary on step 3
            if (step === 3) updateSummary();

            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function validateStep(step) {
            if (step === 1) {
                const name = document.querySelector('[name="name"]');
                const phone = document.querySelector('[name="phone"]');
                if (!name.value.trim()) { name.focus(); shake(name); return false; }
                if (!phone.value.trim()) { phone.focus(); shake(phone); return false; }
                return true;
            }
            if (step === 2) {
                const service = document.getElementById('serviceSelect');
                if (!service.value) { service.focus(); shake(service); return false; }
                // Validate required dynamic fields
                const requiredFields = document.querySelectorAll('#dynamicFields [data-required="true"]');
                for (const field of requiredFields) {
                    const inputs = field.querySelectorAll('input, select, textarea');
                    let filled = false;
                    inputs.forEach(inp => {
                        if (inp.type === 'checkbox' || inp.type === 'radio') {
                            if (inp.checked) filled = true;
                        } else if (inp.value.trim()) {
                            filled = true;
                        }
                    });
                    if (!filled) {
                        field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        shake(field);
                        return false;
                    }
                }
                return true;
            }
            return true;
        }

        function shake(el) {
            el.style.animation = 'none';
            el.offsetHeight;
            el.style.animation = 'shake 0.5s';
            setTimeout(() => el.style.animation = '', 500);
        }

        // ── Service change → load dynamic fields ────────
        const serviceSelect = document.getElementById('serviceSelect');
        serviceSelect.addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            const category = selected?.dataset?.category;
            if (!category) {
                document.getElementById('dynamicFields').innerHTML = `
                    <div class="text-center py-8 text-slate-500 text-sm">
                        Pilih layanan di atas untuk melihat form requirements
                    </div>`;
                return;
            }
            loadFields(category);
        });

        async function loadFields(category) {
            const container = document.getElementById('dynamicFields');
            const loading = document.getElementById('fieldsLoading');
            container.innerHTML = '';
            loading.classList.remove('hidden');

            try {
                const baseUrl = @json(url('/api/requirement-fields/' . $formSlug));
                const res = await fetch(`${baseUrl}/${category}`);
                const fields = await res.json();
                loading.classList.add('hidden');

                if (fields.length === 0) {
                    container.innerHTML = '<p class="text-slate-500 text-sm text-center py-4">Tidak ada field tambahan untuk kategori ini.</p>';
                    return;
                }

                fields.forEach(field => {
                    container.appendChild(createFieldElement(field));
                });
            } catch (err) {
                loading.classList.add('hidden');
                container.innerHTML = '<p class="text-red-400 text-sm text-center py-4">Gagal memuat fields. Silakan coba lagi.</p>';
            }
        }

        function createFieldElement(field) {
            const wrapper = document.createElement('div');
            wrapper.setAttribute('data-required', field.is_required ? 'true' : 'false');

            const label = `<label class="form-label">${field.field_label}${field.is_required ? ' <span class="text-red-400">*</span>' : ''}</label>`;

            let input = '';
            const name = `req_${field.field_name}`;

            switch (field.field_type) {
                case 'text':
                    input = `<input type="text" name="${name}" class="form-input" placeholder="${field.placeholder || ''}" ${field.is_required ? 'required' : ''}>`;
                    break;
                case 'number':
                    input = `<input type="number" name="${name}" class="form-input" placeholder="${field.placeholder || ''}" ${field.is_required ? 'required' : ''}>`;
                    break;
                case 'textarea':
                    input = `<textarea name="${name}" rows="3" class="form-input" placeholder="${field.placeholder || ''}" ${field.is_required ? 'required' : ''}></textarea>`;
                    break;
                case 'date':
                    input = `<input type="date" name="${name}" class="form-input" ${field.is_required ? 'required' : ''}>`;
                    break;
                case 'select':
                    const opts = (field.field_options || []).map(o => `<option value="${o}">${o}</option>`).join('');
                    input = `<select name="${name}" class="form-input" ${field.is_required ? 'required' : ''}>
                        <option value="">— Pilih —</option>${opts}
                    </select>`;
                    break;
                case 'checkbox':
                    input = `<div class="grid grid-cols-1 sm:grid-cols-2 gap-2">` +
                        (field.field_options || []).map(o =>
                            `<label class="checkbox-card">
                                <input type="checkbox" name="${name}[]" value="${o}" class="w-4 h-4 rounded bg-white/5 border-white/20 text-indigo-500 focus:ring-indigo-500/30 focus:ring-offset-0">
                                <span class="text-sm text-slate-300">${o}</span>
                            </label>`
                        ).join('') + `</div>`;
                    break;
                case 'radio':
                    input = `<div class="grid grid-cols-1 sm:grid-cols-2 gap-2">` +
                        (field.field_options || []).map(o =>
                            `<label class="radio-card">
                                <input type="radio" name="${name}" value="${o}" class="w-4 h-4 bg-white/5 border-white/20 text-indigo-500 focus:ring-indigo-500/30 focus:ring-offset-0" ${field.is_required ? 'required' : ''}>
                                <span class="text-sm text-slate-300">${o}</span>
                            </label>`
                        ).join('') + `</div>`;
                    break;
            }

            wrapper.innerHTML = label + input;
            return wrapper;
        }

        // ── Summary ────────────────────────────────────
        function updateSummary() {
            const summary = document.getElementById('formSummary');
            const name = document.querySelector('[name="name"]')?.value || '-';
            const company = document.querySelector('[name="company_name"]')?.value || '-';
            const phone = document.querySelector('[name="phone"]')?.value || '-';
            const serviceEl = document.getElementById('serviceSelect');
            const service = serviceEl.options[serviceEl.selectedIndex]?.text || '-';

            summary.innerHTML = `
                <p>👤 <strong>${name}</strong> ${company !== '-' ? `(${company})` : ''}</p>
                <p>📱 ${phone}</p>
                <p>🔧 ${service}</p>
            `;
        }

        // ── Prevent double submit ──────────────────────
        document.getElementById('requirementForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = '<span class="loading-spinner"></span> Mengirim...';
        });

        // Load fields if service was pre-selected (e.g. validation error redirect)
        document.addEventListener('DOMContentLoaded', function() {
            const selected = serviceSelect.options[serviceSelect.selectedIndex];
            if (selected?.dataset?.category) {
                loadFields(selected.dataset.category);
            }
        });
    </script>

    <style>
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-8px); }
            50% { transform: translateX(8px); }
            75% { transform: translateX(-4px); }
        }
    </style>
</body>
</html>
