@extends('layouts.app')
@section('title', isset($company) ? 'Edit Perusahaan' : 'Tambah Perusahaan')
@section('content')
    <div class="max-w-2xl">
        <div class="glass rounded-2xl p-8">
            <form method="POST"
                action="{{ isset($company) ? route('companies.update', $company) : route('companies.store') }}"
                class="space-y-6">
                @csrf
                @if(isset($company)) @method('PUT') @endif

                <div>
                    <label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Nama Perusahaan <span
                            class="text-red-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $company->name ?? '') }}" required
                        class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none"
                        placeholder="PT Contoh Indonesia">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', $company->email ?? '') }}"
                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none"
                            placeholder="info@company.com">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Telepon</label>
                        <input type="text" name="phone" value="{{ old('phone', $company->phone ?? '') }}"
                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none"
                            placeholder="021-xxxxxxx">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Alamat</label>
                    <textarea name="address" rows="2"
                        class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none"
                        placeholder="Alamat lengkap...">{{ old('address', $company->address ?? '') }}</textarea>
                </div>
                <div class="flex items-center gap-3 pt-4">
                    <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 text-white text-sm font-medium rounded-xl shadow-lg shadow-blue-500/20 transition-all">
                        {{ isset($company) ? 'Update Perusahaan' : 'Simpan Perusahaan' }}
                    </button>
                    <a href="{{ route('companies.index') }}"
                        class="px-6 py-2.5 bg-gray-200 dark:bg-dark-700 hover:bg-gray-300 dark:hover:bg-dark-600 text-gray-700 dark:text-white text-sm font-medium rounded-xl transition-colors">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection