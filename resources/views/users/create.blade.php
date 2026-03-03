@extends('layouts.app')
@section('title', isset($user) ? 'Edit User' : 'Tambah User')
@section('content')
    <div class="max-w-2xl">
        <div class="glass rounded-2xl p-8">
            <form method="POST" action="{{ isset($user) ? route('users.update', $user) : route('users.store') }}"
                class="space-y-6">
                @csrf
                @if(isset($user)) @method('PUT') @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Nama <span
                                class="text-red-400">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required
                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none"
                            placeholder="Nama lengkap">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Email <span
                                class="text-red-400">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required
                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none"
                            placeholder="email@example.com">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Password
                            {{ isset($user) ? '(kosongkan jika tidak diubah)' : '' }} <span
                                class="{{ isset($user) ? '' : 'text-red-400' }}">{{ isset($user) ? '' : '*' }}</span></label>
                        <input type="password" name="password" {{ isset($user) ? '' : 'required' }}
                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none"
                            placeholder="Min. 6 karakter">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Role <span
                                class="text-red-400">*</span></label>
                        <select name="role" required
                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                            @foreach($roles as $val => $label)
                                <option value="{{ $val }}" {{ old('role', $user->role ?? 'staff') == $val ? 'selected' : '' }}>
                                    {{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @if(auth()->user()->isSuperAdmin() && $companies->count() > 0)
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Perusahaan</label>
                        <select name="company_id"
                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                            <option value="">— Tanpa Perusahaan (Super Admin) —</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ old('company_id', $user->company_id ?? '') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="flex items-center gap-3 pt-4">
                    <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 text-white text-sm font-medium rounded-xl shadow-lg shadow-blue-500/20 transition-all">
                        {{ isset($user) ? 'Update User' : 'Simpan User' }}
                    </button>
                    <a href="{{ route('users.index') }}"
                        class="px-6 py-2.5 bg-gray-200 dark:bg-dark-700 hover:bg-gray-300 dark:hover:bg-dark-600 text-gray-700 dark:text-white text-sm font-medium rounded-xl transition-colors">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection