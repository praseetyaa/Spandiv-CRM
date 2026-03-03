@extends('layouts.app')
@section('title', isset($client) ? 'Edit Client' : 'Tambah Client')
@section('content')
    <div class="max-w-3xl">
        <div class="glass rounded-2xl p-8">
            <form method="POST" action="{{ isset($client) ? route('clients.update', $client) : route('clients.store') }}"
                class="space-y-6">
                @csrf
                @if(isset($client)) @method('PUT') @endif
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div><label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Nama <span
                                class="text-red-400">*</span></label><input type="text" name="name"
                            value="{{ old('name', $client->name ?? '') }}" required
                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                    </div>
                    <div><label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Telepon <span
                                class="text-red-400">*</span></label><input type="text" name="phone"
                            value="{{ old('phone', $client->phone ?? '') }}" required
                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                    </div>
                    <div><label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Email <span
                                class="text-red-400">*</span></label><input type="email" name="email"
                            value="{{ old('email', $client->email ?? '') }}" required
                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                    </div>
                    <div><label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Nama Bisnis <span
                                class="text-red-400">*</span></label><input type="text" name="business_name"
                            value="{{ old('business_name', $client->business_name ?? '') }}" required
                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                    </div>
                    <div><label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Industry <span
                                class="text-red-400">*</span></label><input type="text" name="industry"
                            value="{{ old('industry', $client->industry ?? '') }}" required
                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none"
                            placeholder="F&B, Fashion, Teknologi..."></div>
                    <div><label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Instagram</label><input type="text"
                            name="instagram" value="{{ old('instagram', $client->instagram ?? '') }}"
                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none"
                            placeholder="@username"></div>
                    <div><label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Website</label><input type="url"
                            name="website" value="{{ old('website', $client->website ?? '') }}"
                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none"
                            placeholder="https://..."></div>
                    <div><label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Status <span
                                class="text-red-400">*</span></label>
                        <select name="client_status" required
                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                            <option value="active" {{ old('client_status', $client->client_status ?? 'active') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ old('client_status', $client->client_status ?? '') == 'inactive' ? 'selected' : '' }}>Inaktif</option>
                        </select>
                    </div>
                </div>
                <div class="flex items-center gap-3 pt-4">
                    <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 text-white text-sm font-medium rounded-xl shadow-lg shadow-blue-500/20 transition-all">{{ isset($client) ? 'Update' : 'Simpan' }}</button>
                    <a href="{{ route('clients.index') }}"
                        class="px-6 py-2.5 bg-gray-200 dark:bg-dark-700 hover:bg-gray-300 dark:hover:bg-dark-600 text-gray-700 dark:text-white text-sm font-medium rounded-xl transition-colors">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection