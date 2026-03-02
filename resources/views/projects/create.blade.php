@extends('layouts.app')
@section('title', isset($project) ? 'Edit Project' : 'Tambah Project')
@section('content')
    <div class="max-w-3xl">
        <div class="glass rounded-2xl p-8">
            <form method="POST"
                action="{{ isset($project) ? route('projects.update', $project) : route('projects.store') }}"
                class="space-y-6">
                @csrf @if(isset($project)) @method('PUT') @endif
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2"><label class="block text-sm font-medium text-dark-300 mb-2">Judul Project
                            <span class="text-red-400">*</span></label><input type="text" name="title"
                            value="{{ old('title', $project->title ?? '') }}" required
                            class="w-full px-4 py-2.5 bg-dark-800/50 border border-dark-600/50 rounded-xl text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                    </div>
                    <div><label class="block text-sm font-medium text-dark-300 mb-2">Client <span
                                class="text-red-400">*</span></label>
                        <select name="client_id" required
                            class="w-full px-4 py-2.5 bg-dark-800/50 border border-dark-600/50 rounded-xl text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                            <option value="">Pilih Client</option>
                            @foreach($clients as $c)<option value="{{ $c->id }}" {{ old('client_id', $project->client_id ?? '') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>@endforeach
                        </select>
                    </div>
                    <div><label class="block text-sm font-medium text-dark-300 mb-2">Layanan <span
                                class="text-red-400">*</span></label>
                        <select name="service_id" required
                            class="w-full px-4 py-2.5 bg-dark-800/50 border border-dark-600/50 rounded-xl text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                            <option value="">Pilih Layanan</option>
                            @foreach($services as $s)<option value="{{ $s->id }}" {{ old('service_id', $project->service_id ?? '') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>@endforeach
                        </select>
                    </div>
                    <div><label class="block text-sm font-medium text-dark-300 mb-2">Harga (Rp) <span
                                class="text-red-400">*</span></label><input type="number" name="price"
                            value="{{ old('price', $project->price ?? '') }}" required
                            class="w-full px-4 py-2.5 bg-dark-800/50 border border-dark-600/50 rounded-xl text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                    </div>
                    <div><label class="block text-sm font-medium text-dark-300 mb-2">Status <span
                                class="text-red-400">*</span></label>
                        <select name="status" required
                            class="w-full px-4 py-2.5 bg-dark-800/50 border border-dark-600/50 rounded-xl text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                            @foreach(['brief' => 'Brief', 'dp_paid' => 'DP Paid', 'on_progress' => 'On Progress', 'revision' => 'Revisi', 'waiting_client' => 'Waiting Client', 'completed' => 'Completed'] as $v => $l)
                                <option value="{{ $v }}" {{ old('status', $project->status ?? 'brief') == $v ? 'selected' : '' }}>
                                    {{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div><label class="block text-sm font-medium text-dark-300 mb-2">Tanggal Mulai <span
                                class="text-red-400">*</span></label><input type="date" name="start_date"
                            value="{{ old('start_date', isset($project) ? $project->start_date->format('Y-m-d') : '') }}"
                            required
                            class="w-full px-4 py-2.5 bg-dark-800/50 border border-dark-600/50 rounded-xl text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                    </div>
                    <div><label class="block text-sm font-medium text-dark-300 mb-2">Deadline <span
                                class="text-red-400">*</span></label><input type="date" name="deadline"
                            value="{{ old('deadline', isset($project) ? $project->deadline->format('Y-m-d') : '') }}"
                            required
                            class="w-full px-4 py-2.5 bg-dark-800/50 border border-dark-600/50 rounded-xl text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                    </div>
                    @if(isset($project))
                        <div><label class="block text-sm font-medium text-dark-300 mb-2">Progress (%)</label><input
                                type="number" name="progress_percentage" min="0" max="100"
                                value="{{ old('progress_percentage', $project->progress_percentage ?? 0) }}"
                                class="w-full px-4 py-2.5 bg-dark-800/50 border border-dark-600/50 rounded-xl text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                        </div>
                    @endif
                </div>
                <div><label class="block text-sm font-medium text-dark-300 mb-2">Deskripsi</label><textarea
                        name="description" rows="3"
                        class="w-full px-4 py-2.5 bg-dark-800/50 border border-dark-600/50 rounded-xl text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">{{ old('description', $project->description ?? '') }}</textarea>
                </div>
                <div class="flex items-center gap-3 pt-4">
                    <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl shadow-lg shadow-blue-500/20 transition-all">{{ isset($project) ? 'Update' : 'Simpan' }}</button>
                    <a href="{{ route('projects.index') }}"
                        class="px-6 py-2.5 bg-dark-700 hover:bg-dark-600 text-white text-sm font-medium rounded-xl transition-colors">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection