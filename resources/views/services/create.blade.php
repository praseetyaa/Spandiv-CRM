@extends('layouts.app')
@section('title', isset($service) ? 'Edit Layanan' : 'Tambah Layanan')
@section('content')
    <div class="max-w-2xl">
        <div class="glass rounded-2xl p-8">
            <form method="POST"
                action="{{ isset($service) ? route('services.update', $service) : route('services.store') }}"
                class="space-y-6">
                @csrf @if(isset($service)) @method('PUT') @endif
                <div><label class="block text-sm font-medium text-dark-300 mb-2">Nama Layanan <span
                            class="text-red-400">*</span></label><input type="text" name="name"
                        value="{{ old('name', $service->name ?? '') }}" required
                        class="w-full px-4 py-2.5 bg-dark-800/50 border border-dark-600/50 rounded-xl text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                </div>
                <div class="grid grid-cols-2 gap-6">
                    <div><label class="block text-sm font-medium text-dark-300 mb-2">Kategori <span
                                class="text-red-400">*</span></label>
                        <select name="category" required
                            class="w-full px-4 py-2.5 bg-dark-800/50 border border-dark-600/50 rounded-xl text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                            @foreach(['website' => 'Website', 'branding' => 'Branding', 'social_media' => 'Social Media', 'invitation' => 'Invitation'] as $val => $lbl)
                                <option value="{{ $val }}" {{ old('category', $service->category ?? '') == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div><label class="block text-sm font-medium text-dark-300 mb-2">Tipe Billing <span
                                class="text-red-400">*</span></label>
                        <select name="billing_type" required
                            class="w-full px-4 py-2.5 bg-dark-800/50 border border-dark-600/50 rounded-xl text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                            <option value="one_time" {{ old('billing_type', $service->billing_type ?? '') == 'one_time' ? 'selected' : '' }}>One Time</option>
                            <option value="recurring" {{ old('billing_type', $service->billing_type ?? '') == 'recurring' ? 'selected' : '' }}>Recurring</option>
                        </select>
                    </div>
                </div>
                <div><label class="block text-sm font-medium text-dark-300 mb-2">Harga Dasar (Rp) <span
                            class="text-red-400">*</span></label><input type="number" name="base_price"
                        value="{{ old('base_price', $service->base_price ?? '') }}" required
                        class="w-full px-4 py-2.5 bg-dark-800/50 border border-dark-600/50 rounded-xl text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                </div>
                <div><label class="block text-sm font-medium text-dark-300 mb-2">Deskripsi</label><textarea
                        name="description" rows="3"
                        class="w-full px-4 py-2.5 bg-dark-800/50 border border-dark-600/50 rounded-xl text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">{{ old('description', $service->description ?? '') }}</textarea>
                </div>
                <div class="flex items-center gap-3 pt-4">
                    <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl shadow-lg shadow-blue-500/20 transition-all">{{ isset($service) ? 'Update' : 'Simpan' }}</button>
                    <a href="{{ route('services.index') }}"
                        class="px-6 py-2.5 bg-dark-700 hover:bg-dark-600 text-white text-sm font-medium rounded-xl transition-colors">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection