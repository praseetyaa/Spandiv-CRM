@extends('layouts.app')
@section('title', 'Detail Project')
@section('content')
    <div class="max-w-5xl">
        <div class="glass rounded-2xl p-8 mb-6">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-white">{{ $project->title }}</h3>
                    <p class="text-dark-400 text-sm">{{ $project->client->name }} · {{ $project->service->name }}</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('projects.edit', $project) }}"
                        class="px-4 py-2 bg-dark-700 hover:bg-dark-600 text-white text-sm rounded-xl transition-colors">Edit</a>
                    <a href="{{ route('projects.index') }}"
                        class="px-4 py-2 text-dark-400 hover:text-white text-sm transition-colors">← Kembali</a>
                </div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                @php $sColor = ['brief' => 'dark', 'dp_paid' => 'cyan', 'on_progress' => 'blue', 'revision' => 'amber', 'waiting_client' => 'purple', 'completed' => 'emerald'][$project->status] ?? 'dark'; @endphp
                <div class="bg-dark-800/50 rounded-xl p-4">
                    <p class="text-xs text-dark-400 mb-1">Status</p><span
                        class="px-2.5 py-1 text-xs rounded-lg bg-{{ $sColor }}-500/10 text-{{ $sColor }}-400">{{ ucfirst(str_replace('_', ' ', $project->status)) }}</span>
                </div>
                <div class="bg-dark-800/50 rounded-xl p-4">
                    <p class="text-xs text-dark-400 mb-1">Progress</p>
                    <p class="text-lg font-bold text-white">{{ $project->progress_percentage }}%</p>
                    <div class="w-full bg-dark-700 rounded-full h-1.5 mt-1">
                        <div class="bg-blue-500 h-1.5 rounded-full" style="width:{{ $project->progress_percentage }}%">
                        </div>
                    </div>
                </div>
                <div class="bg-dark-800/50 rounded-xl p-4">
                    <p class="text-xs text-dark-400 mb-1">Harga</p>
                    <p class="text-sm font-medium text-emerald-400">Rp {{ number_format($project->price, 0, ',', '.') }}</p>
                </div>
                <div class="bg-dark-800/50 rounded-xl p-4">
                    <p class="text-xs text-dark-400 mb-1">Deadline</p>
                    <p
                        class="text-sm font-medium {{ $project->deadline < now() && $project->status !== 'completed' ? 'text-red-400' : 'text-white' }}">
                        {{ $project->deadline->format('d M Y') }}</p>
                </div>
            </div>
            @if($project->description)
            <p class="text-sm text-dark-300">{{ $project->description }}</p>@endif
        </div>

        <!-- Milestones -->
        <div class="glass rounded-2xl p-6 mb-6">
            <h4 class="font-semibold text-white mb-4">Milestone</h4>
            <div class="space-y-3 mb-4">
                @forelse($project->milestones as $ms)
                    <div class="flex items-center gap-4 p-3 rounded-xl bg-dark-800/50">
                        <form method="POST" action="{{ route('projects.milestones.toggle', [$project, $ms]) }}">@csrf
                            @method('PATCH')
                            <button
                                class="w-6 h-6 rounded-md border {{ $ms->status == 'done' ? 'bg-emerald-500 border-emerald-500 text-white' : 'border-dark-500 text-transparent hover:border-blue-500' }} flex items-center justify-center transition-colors">✓</button>
                        </form>
                        <div class="flex-1">
                            <p class="text-sm {{ $ms->status == 'done' ? 'text-dark-400 line-through' : 'text-white' }}">
                                {{ $ms->title }}</p>
                            <p class="text-xs text-dark-500">Due: {{ $ms->due_date->format('d M Y') }}</p>
                        </div>
                        <form method="POST" action="{{ route('projects.milestones.destroy', [$project, $ms]) }}"
                            onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button
                                class="text-dark-500 hover:text-red-400 text-xs">✕</button></form>
                    </div>
                @empty <p class="text-sm text-dark-400">Belum ada milestone</p>@endforelse
            </div>
            <form method="POST" action="{{ route('projects.milestones.store', $project) }}" class="flex gap-3">
                @csrf
                <input type="text" name="title" required placeholder="Judul milestone..."
                    class="flex-1 px-4 py-2 bg-dark-800/50 border border-dark-600/50 rounded-xl text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                <input type="date" name="due_date" required
                    class="px-4 py-2 bg-dark-800/50 border border-dark-600/50 rounded-xl text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm rounded-xl transition-colors">Tambah</button>
            </form>
        </div>
    </div>
@endsection