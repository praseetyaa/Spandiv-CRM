@extends('layouts.app')
@section('title', 'Form Builder')
@section('subtitle', 'Kelola field form requirements publik')
@section('header-actions')
    <a href="{{ route('requirement-fields.create') }}"
        class="px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 text-white text-sm font-medium rounded-xl shadow-lg shadow-blue-500/20 transition-all flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>Tambah Field
    </a>
@endsection
@section('content')
    @php
        $typeLabels = [
            'text' => 'Text', 'textarea' => 'Textarea', 'select' => 'Select',
            'checkbox' => 'Checkbox', 'radio' => 'Radio', 'date' => 'Date', 'number' => 'Number',
        ];
        $icons = ['website' => '🌐', 'branding' => '🎨', 'social_media' => '📱', 'invitation' => '💌'];
    @endphp

    {{-- Public URL Info --}}
    @php
        $userCompany = auth()->user()->company;
        $formUrl = $userCompany && $userCompany->form_slug ? route('requirements.form', $userCompany->form_slug) : null;
    @endphp
    @if($formUrl)
        <div class="glass rounded-2xl p-4 mb-6 flex flex-wrap items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-blue-500/15 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm text-gray-600 dark:text-dark-300">Link form publik perusahaan Anda:</p>
                <a href="{{ $formUrl }}" target="_blank" class="text-blue-400 hover:text-blue-300 text-sm font-medium break-all">{{ $formUrl }}</a>
            </div>
            <button onclick="navigator.clipboard.writeText('{{ $formUrl }}').then(() => this.innerHTML='✓ Copied!')"
                class="px-3 py-1.5 bg-blue-500/10 text-blue-400 text-xs rounded-lg hover:bg-blue-500/20 transition-colors whitespace-nowrap">Copy Link</button>
        </div>
    @endif

    {{-- Filters --}}
    <div class="glass rounded-2xl p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-3 items-center">
            <select name="category"
                class="px-4 py-2 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                <option value="">Semua Kategori</option>
                @foreach($categories as $catKey => $catLabel)
                    <option value="{{ $catKey }}" {{ request('category') == $catKey ? 'selected' : '' }}>{{ $catLabel }}</option>
                @endforeach
            </select>
            @include('partials.company-filter')
            <button type="submit" class="px-4 py-2 bg-gray-200 dark:bg-dark-700 hover:bg-gray-300 dark:hover:bg-dark-600 text-gray-700 dark:text-white text-sm rounded-xl transition-colors">Filter</button>
            @if(request()->hasAny(['category', 'company_id']))
                <a href="{{ route('requirement-fields.index') }}" class="text-xs text-gray-400 hover:text-white">Reset</a>
            @endif
        </form>
    </div>

    @foreach($fields as $catKey => $catFields)
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ $icons[$catKey] ?? '📦' }} {{ ucfirst(str_replace('_', ' ', $catKey)) }}
                </h3>
                <span class="px-2 py-0.5 text-xs rounded-full bg-indigo-500/10 text-indigo-400">{{ $catFields->count() }} fields</span>
            </div>

            {{-- Mobile Cards --}}
            <div class="block lg:hidden space-y-3">
                @foreach($catFields as $field)
                    <div class="glass rounded-xl p-4 space-y-2">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $field->field_label }}</p>
                                <p class="text-xs text-gray-400 dark:text-dark-500">{{ $field->field_name }}</p>
                            </div>
                            <div class="flex items-center gap-1.5">
                                @if($field->is_required)
                                    <span class="px-2 py-0.5 text-xs rounded-lg bg-red-500/10 text-red-400">Wajib</span>
                                @endif
                                @if($field->is_active)
                                    <span class="px-2 py-0.5 text-xs rounded-lg bg-emerald-500/10 text-emerald-400">Aktif</span>
                                @else
                                    <span class="px-2 py-0.5 text-xs rounded-lg bg-gray-200 dark:bg-dark-600 text-gray-500 dark:text-dark-400">Off</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-2 text-xs">
                            <span class="px-2 py-0.5 rounded-lg bg-indigo-500/10 text-indigo-400">{{ $typeLabels[$field->field_type] ?? $field->field_type }}</span>
                            <span class="text-gray-500 dark:text-dark-500">Urutan: {{ $field->sort_order }}</span>
                        </div>
                        @if($field->field_options)
                            <p class="text-xs text-gray-500 dark:text-dark-400 truncate">Opsi: {{ implode(', ', $field->field_options) }}</p>
                        @endif
                        <div class="flex items-center gap-2 pt-1">
                            <a href="{{ route('requirement-fields.edit', $field) }}"
                                class="px-3 py-1.5 bg-gray-200 dark:bg-dark-700 hover:bg-gray-300 dark:hover:bg-dark-600 text-gray-700 dark:text-white text-xs rounded-lg transition-colors">Edit</a>
                            <button type="button" onclick="confirmDelete('{{ route('requirement-fields.destroy', $field) }}')"
                                class="px-3 py-1.5 bg-red-500/10 hover:bg-red-500/20 text-red-400 text-xs rounded-lg transition-colors">Hapus</button>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Desktop Table --}}
            <div class="hidden lg:block glass rounded-2xl overflow-hidden">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-dark-700/50">
                            <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 dark:text-dark-400 uppercase w-12">#</th>
                            <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 dark:text-dark-400 uppercase">Label</th>
                            <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 dark:text-dark-400 uppercase w-24">Tipe</th>
                            <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 dark:text-dark-400 uppercase">Opsi</th>
                            <th class="text-center px-4 py-3 text-xs font-medium text-gray-500 dark:text-dark-400 uppercase w-24">Info</th>
                            <th class="text-right px-4 py-3 text-xs font-medium text-gray-500 dark:text-dark-400 uppercase w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($catFields as $field)
                            <tr class="table-row border-b border-gray-100 dark:border-dark-700/30">
                                <td class="px-4 py-3 text-sm text-gray-400 dark:text-dark-500">{{ $field->sort_order }}</td>
                                <td class="px-4 py-3">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $field->field_label }}</p>
                                    <p class="text-xs text-gray-400 dark:text-dark-500">{{ $field->field_name }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-0.5 text-xs rounded-lg bg-indigo-500/10 text-indigo-400">{{ $typeLabels[$field->field_type] ?? $field->field_type }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-xs text-gray-500 dark:text-dark-400 max-w-[180px] break-words">{{ $field->field_options ? implode(', ', $field->field_options) : '—' }}</p>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-1 flex-wrap">
                                        @if($field->is_required)
                                            <span class="px-1.5 py-0.5 text-[10px] rounded bg-red-500/10 text-red-400">Wajib</span>
                                        @endif
                                        <span class="px-1.5 py-0.5 text-[10px] rounded {{ $field->is_active ? 'bg-emerald-500/10 text-emerald-400' : 'bg-gray-500/10 text-gray-400' }}">{{ $field->is_active ? 'Aktif' : 'Off' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('requirement-fields.edit', $field) }}"
                                            class="px-2.5 py-1 bg-gray-200 dark:bg-dark-700 hover:bg-gray-300 dark:hover:bg-dark-600 text-gray-700 dark:text-white text-xs rounded-lg transition-colors">Edit</a>
                                        <button type="button" onclick="confirmDelete('{{ route('requirement-fields.destroy', $field) }}')"
                                            class="px-2.5 py-1 bg-red-500/10 hover:bg-red-500/20 text-red-400 text-xs rounded-lg transition-colors">Hapus</button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach

    @if($fields->isEmpty())
        <div class="glass rounded-2xl p-12 text-center">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-400 dark:text-dark-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <p class="text-gray-500 dark:text-dark-400 mb-4">
                {{ request()->hasAny(['category', 'company_id']) ? 'Tidak ada field yang cocok dengan filter.' : 'Belum ada field form. Tambahkan field pertama Anda!' }}
            </p>
            <a href="{{ route('requirement-fields.create') }}" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>Tambah Field
            </a>
        </div>
    @endif
@endsection
