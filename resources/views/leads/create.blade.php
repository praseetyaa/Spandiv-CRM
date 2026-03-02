@extends('layouts.app')
@section('title', isset($lead) ? 'Edit Lead' : 'Tambah Lead')

@section('content')
    <div class="max-w-3xl">
        <div class="glass rounded-2xl p-8">
            <form method="POST" action="{{ isset($lead) ? route('leads.update', $lead) : route('leads.store') }}"
                class="space-y-6">
                @csrf
                @if(isset($lead)) @method('PUT') @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">Nama <span
                                class="text-red-400">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $lead->name ?? '') }}" required
                            class="w-full px-4 py-2.5 bg-dark-800/50 border border-dark-600/50 rounded-xl text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none"
                            placeholder="Nama lengkap">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">Telepon <span
                                class="text-red-400">*</span></label>
                        <input type="text" name="phone" value="{{ old('phone', $lead->phone ?? '') }}" required
                            class="w-full px-4 py-2.5 bg-dark-800/50 border border-dark-600/50 rounded-xl text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none"
                            placeholder="08xxxxxxxxxx">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', $lead->email ?? '') }}"
                            class="w-full px-4 py-2.5 bg-dark-800/50 border border-dark-600/50 rounded-xl text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none"
                            placeholder="email@example.com">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">Sumber <span
                                class="text-red-400">*</span></label>
                        <select name="source" required
                            class="w-full px-4 py-2.5 bg-dark-800/50 border border-dark-600/50 rounded-xl text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                            @foreach(['instagram', 'website', 'referral', 'google', 'whatsapp', 'other'] as $src)
                                <option value="{{ $src }}" {{ old('source', $lead->source ?? '') == $src ? 'selected' : '' }}>
                                    {{ ucfirst($src) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">Layanan <span
                                class="text-red-400">*</span></label>
                        <select name="service_id" required
                            class="w-full px-4 py-2.5 bg-dark-800/50 border border-dark-600/50 rounded-xl text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                            <option value="">Pilih Layanan</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id', $lead->service_id ?? '') == $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">Estimasi Nilai</label>
                        <input type="number" name="estimated_value"
                            value="{{ old('estimated_value', $lead->estimated_value ?? '') }}"
                            class="w-full px-4 py-2.5 bg-dark-800/50 border border-dark-600/50 rounded-xl text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none"
                            placeholder="5000000">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">Urgency <span
                                class="text-red-400">*</span></label>
                        <select name="urgency_level" required
                            class="w-full px-4 py-2.5 bg-dark-800/50 border border-dark-600/50 rounded-xl text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                            @foreach(['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'] as $val => $label)
                                <option value="{{ $val }}" {{ old('urgency_level', $lead->urgency_level ?? 'medium') == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-2">Status <span
                                class="text-red-400">*</span></label>
                        <select name="status" required
                            class="w-full px-4 py-2.5 bg-dark-800/50 border border-dark-600/50 rounded-xl text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                            @foreach(['new' => 'Baru', 'contacted' => 'Dihubungi', 'proposal_sent' => 'Proposal Terkirim', 'negotiation' => 'Negosiasi', 'closed_won' => 'Closed Won', 'closed_lost' => 'Closed Lost'] as $val => $label)
                                <option value="{{ $val }}" {{ old('status', $lead->status ?? 'new') == $val ? 'selected' : '' }}>
                                    {{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Catatan</label>
                    <textarea name="notes" rows="3"
                        class="w-full px-4 py-2.5 bg-dark-800/50 border border-dark-600/50 rounded-xl text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none"
                        placeholder="Catatan tambahan...">{{ old('notes', $lead->notes ?? '') }}</textarea>
                </div>

                <div class="flex items-center gap-3 pt-4">
                    <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 text-white text-sm font-medium rounded-xl shadow-lg shadow-blue-500/20 transition-all">
                        {{ isset($lead) ? 'Update Lead' : 'Simpan Lead' }}
                    </button>
                    <a href="{{ route('leads.index') }}"
                        class="px-6 py-2.5 bg-dark-700 hover:bg-dark-600 text-white text-sm font-medium rounded-xl transition-colors">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection