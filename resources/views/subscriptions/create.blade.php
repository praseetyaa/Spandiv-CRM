@extends('layouts.app')
@section('title', isset($subscription) ? 'Edit Subscription' : 'Tambah Subscription')
@section('content')
    <div class="max-w-3xl">
        <div class="glass rounded-2xl p-8">
            <form method="POST"
                action="{{ isset($subscription) ? route('subscriptions.update', $subscription) : route('subscriptions.store') }}"
                class="space-y-6">
                @csrf @if(isset($subscription)) @method('PUT') @endif
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div><label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Client <span
                                class="text-red-400">*</span></label><select name="client_id" required
                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                            <option value="">Pilih Client</option>@foreach($clients as $c)<option value="{{ $c->id }}" {{ old('client_id', $subscription->client_id ?? '') == $c->id ? 'selected' : '' }}>{{ $c->name }}
                            </option>@endforeach
                        </select></div>
                    <div><label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Layanan <span
                                class="text-red-400">*</span></label><select name="service_id" required
                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                            <option value="">Pilih Layanan</option>@foreach($services as $s)<option value="{{ $s->id }}" {{ old('service_id', $subscription->service_id ?? '') == $s->id ? 'selected' : '' }}>
                            {{ $s->name }}</option>@endforeach
                        </select></div>
                    <div><label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Harga/Bulan (Rp) <span
                                class="text-red-400">*</span></label><input type="number" name="price"
                            value="{{ old('price', $subscription->price ?? '') }}" required
                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                    </div>
                    <div><label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Billing Cycle</label><select
                            name="billing_cycle"
                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                            <option value="monthly">Monthly</option>
                        </select></div>
                    <div><label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Tanggal Mulai <span
                                class="text-red-400">*</span></label><input type="date" name="start_date"
                            value="{{ old('start_date', isset($subscription) ? $subscription->start_date->format('Y-m-d') : '') }}"
                            required
                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                    </div>
                    <div><label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Tanggal Akhir</label><input type="date"
                            name="end_date"
                            value="{{ old('end_date', isset($subscription) && $subscription->end_date ? $subscription->end_date->format('Y-m-d') : '') }}"
                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                    </div>
                    <div><label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Status <span
                                class="text-red-400">*</span></label><select name="status" required
                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">@foreach(['active' => 'Aktif', 'paused' => 'Paused', 'cancelled' => 'Cancelled'] as $v => $l)
                            <option value="{{ $v }}" {{ old('status', $subscription->status ?? 'active') == $v ? 'selected' : '' }}>{{ $l }}</option>@endforeach</select></div>
                </div>
                <div class="flex items-center gap-3 pt-4">
                    <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl shadow-lg shadow-blue-500/20 transition-all">{{ isset($subscription) ? 'Update' : 'Simpan' }}</button>
                    <a href="{{ route('subscriptions.index') }}"
                        class="px-6 py-2.5 bg-gray-200 dark:bg-dark-700 hover:bg-gray-300 dark:hover:bg-dark-600 text-gray-700 dark:text-white text-sm font-medium rounded-xl transition-colors">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection