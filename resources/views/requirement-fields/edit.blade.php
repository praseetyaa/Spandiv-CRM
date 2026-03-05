@extends('layouts.app')
@section('title', isset($field) ? 'Edit Field' : 'Tambah Field')

@section('content')
    <div class="max-w-2xl">
        <div class="glass rounded-2xl p-6 sm:p-8">
            <form method="POST"
                action="{{ isset($field) ? route('requirement-fields.update', $field) : route('requirement-fields.store') }}"
                class="space-y-6">
                @csrf
                @if(isset($field)) @method('PUT') @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Kategori Layanan
                            <span class="text-red-400">*</span></label>
                        <select name="service_category" required
                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                            <option value="">— Pilih Kategori —</option>
                            @foreach($categories as $catKey => $catLabel)
                                <option value="{{ $catKey }}" {{ old('service_category', $field->service_category ?? '') == $catKey ? 'selected' : '' }}>{{ $catLabel }}</option>
                            @endforeach
                            <option value="all" {{ old('service_category', $field->service_category ?? '') == 'all' ? 'selected' : '' }}>🌍 Semua Kategori</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Tipe Field <span
                                class="text-red-400">*</span></label>
                        <select name="field_type" required id="fieldTypeSelect"
                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                            @foreach(['text' => 'Text', 'textarea' => 'Textarea', 'number' => 'Number', 'date' => 'Date', 'select' => 'Select (Dropdown)', 'checkbox' => 'Checkbox (Multi-pilih)', 'radio' => 'Radio (Pilih satu)'] as $val => $lbl)
                                <option value="{{ $val }}" {{ old('field_type', $field->field_type ?? '') == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Label Field <span
                            class="text-red-400">*</span></label>
                    <input type="text" name="field_label" value="{{ old('field_label', $field->field_label ?? '') }}"
                        required
                        class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none"
                        placeholder="Contoh: Jenis Website">
                    <p class="text-xs text-gray-400 dark:text-dark-500 mt-1">Nama field otomatis digenerate dari label</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Placeholder</label>
                    <input type="text" name="placeholder" value="{{ old('placeholder', $field->placeholder ?? '') }}"
                        class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none"
                        placeholder="Contoh: Masukkan deskripsi...">
                </div>

                <div id="optionsGroup">
                    <label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Opsi (pisahkan dengan
                        koma)</label>
                    <textarea name="field_options" rows="3"
                        class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none"
                        placeholder="Company Profile, Landing Page, E-Commerce, Web App">{{ old('field_options', isset($field) && $field->field_options ? implode(', ', $field->field_options) : '') }}</textarea>
                    <p class="text-xs text-gray-400 dark:text-dark-500 mt-1">Hanya untuk tipe Select, Checkbox, dan Radio
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Urutan</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $field->sort_order ?? 0) }}"
                            min="0"
                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                    </div>
                    <div class="flex items-center gap-3 sm:pt-7">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="hidden" name="is_required" value="0">
                            <input type="checkbox" name="is_required" value="1" {{ old('is_required', $field->is_required ?? false) ? 'checked' : '' }}
                                class="w-4 h-4 rounded bg-gray-200 dark:bg-dark-700 border-gray-300 dark:border-dark-600 text-blue-500 focus:ring-blue-500/30">
                            <span class="text-sm text-gray-600 dark:text-dark-300">Wajib diisi</span>
                        </label>
                    </div>
                    <div class="flex items-center gap-3 sm:pt-7">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $field->is_active ?? true) ? 'checked' : '' }}
                                class="w-4 h-4 rounded bg-gray-200 dark:bg-dark-700 border-gray-300 dark:border-dark-600 text-emerald-500 focus:ring-emerald-500/30">
                            <span class="text-sm text-gray-600 dark:text-dark-300">Aktif</span>
                        </label>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3 pt-4">
                    <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 text-white text-sm font-medium rounded-xl shadow-lg shadow-blue-500/20 transition-all">
                        {{ isset($field) ? 'Update Field' : 'Simpan Field' }}
                    </button>
                    <a href="{{ route('requirement-fields.index') }}"
                        class="px-6 py-2.5 bg-gray-200 dark:bg-dark-700 hover:bg-gray-300 dark:hover:bg-dark-600 text-gray-700 dark:text-white text-sm font-medium rounded-xl transition-colors">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const typeSelect = document.getElementById('fieldTypeSelect');
        const optionsGroup = document.getElementById('optionsGroup');
        function toggleOptions() {
            const needsOpts = ['select', 'checkbox', 'radio'].includes(typeSelect.value);
            optionsGroup.style.display = needsOpts ? 'block' : 'none';
        }
        typeSelect.addEventListener('change', toggleOptions);
        toggleOptions();
    </script>
@endpush