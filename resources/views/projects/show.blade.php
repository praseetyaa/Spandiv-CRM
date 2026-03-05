@extends('layouts.app')
@section('title', 'Detail Project')
@section('content')
    <div class="">
        <div class="glass rounded-2xl p-8 mb-6">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $project->title }}</h3>
                    <p class="text-dark-400 text-sm">{{ $project->client->name }} · {{ $project->service->name }}</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('projects.edit', $project) }}"
                        class="px-4 py-2 bg-gray-200 dark:bg-dark-700 hover:bg-gray-300 dark:hover:bg-dark-600 text-gray-700 dark:text-white text-sm rounded-xl transition-colors">Edit</a>
                    <a href="{{ route('projects.index') }}"
                        class="px-4 py-2 text-gray-400 dark:text-gray-500 dark:text-dark-400 hover:text-gray-900 dark:hover:text-white text-sm transition-colors">←
                        Kembali</a>
                </div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                @php $sColor = ['brief' => 'dark', 'dp_paid' => 'cyan', 'on_progress' => 'blue', 'revision' => 'amber', 'waiting_client' => 'purple', 'completed' => 'emerald'][$project->status] ?? 'dark'; @endphp
                <div class="bg-gray-100 dark:bg-dark-800/50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 dark:text-dark-400 mb-1">Status</p><span
                        class="px-2.5 py-1 text-xs rounded-lg bg-{{ $sColor }}-500/10 text-{{ $sColor }}-400">{{ ucfirst(str_replace('_', ' ', $project->status)) }}</span>
                </div>
                <div class="bg-gray-100 dark:bg-dark-800/50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 dark:text-dark-400 mb-1">Progress</p>
                    <p class="text-lg font-bold text-white">{{ $project->progress_percentage }}%</p>
                    <div class="w-full bg-gray-200 dark:bg-dark-700 rounded-full h-1.5 mt-1">
                        <div class="bg-{{ $project->progress_percentage >= 100 ? 'emerald' : 'blue' }}-500 h-1.5 rounded-full transition-all"
                            style="width:{{ $project->progress_percentage }}%">
                        </div>
                    </div>
                </div>
                <div class="bg-gray-100 dark:bg-dark-800/50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 dark:text-dark-400 mb-1">Harga</p>
                    <p class="text-sm font-medium text-emerald-400">Rp {{ number_format($project->price, 0, ',', '.') }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-dark-800/50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 dark:text-dark-400 mb-1">Deadline</p>
                    <p
                        class="text-sm font-medium {{ $project->deadline < now() && $project->status !== 'completed' ? 'text-red-400' : 'text-white' }}">
                        {{ $project->deadline->format('d M Y') }}
                    </p>
                </div>
            </div>
            @if($project->description)
            <p class="text-sm text-gray-600 dark:text-dark-300">{{ $project->description }}</p>@endif
        </div>

        {{-- Create Invoice CTA — appears when project is 100% or completed --}}
        @if($project->progress_percentage >= 100 || $project->status === 'completed')
            <div
                class="glass rounded-2xl p-6 mb-6 border border-emerald-500/20 bg-gradient-to-r from-emerald-500/5 to-teal-500/5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-base font-bold text-gray-900 dark:text-white">Project Selesai! 🎉</h4>
                            <p class="text-sm text-gray-500 dark:text-dark-400 mt-0.5">Buat invoice untuk project ini senilai
                                <span class="text-emerald-400 font-medium">Rp
                                    {{ number_format($project->price, 0, ',', '.') }}</span>
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('invoices.create', ['project_id' => $project->id, 'client_id' => $project->client_id, 'item_desc' => $project->title, 'item_price' => $project->price]) }}"
                        class="px-5 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white text-sm font-medium rounded-xl shadow-lg shadow-emerald-500/20 transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Buat Invoice
                    </a>
                </div>

                {{-- Show existing invoices for this project if any --}}
                @if($project->invoices && $project->invoices->count() > 0)
                    <div class="mt-4 pt-4 border-t border-emerald-500/10">
                        <p class="text-xs text-gray-500 dark:text-dark-400 mb-2">Invoice terkait project ini:</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($project->invoices as $inv)
                                <a href="{{ route('invoices.show', $inv) }}"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 dark:bg-dark-800/50 hover:bg-gray-200 dark:hover:bg-dark-700 rounded-lg text-xs font-medium transition-colors
                                                                        {{ $inv->status === 'paid' ? 'text-emerald-400' : ($inv->status === 'overdue' ? 'text-red-400' : 'text-gray-600 dark:text-dark-300') }}">
                                    <span>{{ $inv->invoice_number }}</span>
                                    <span
                                        class="px-1.5 py-0.5 rounded text-[10px] bg-{{ $inv->status === 'paid' ? 'emerald' : ($inv->status === 'overdue' ? 'red' : 'gray') }}-500/10">
                                        {{ ucfirst($inv->status) }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Milestones -->
        <div class="glass rounded-2xl p-6 mb-6">
            <h4 class="font-semibold text-white mb-4">Milestone</h4>
            <div class="space-y-3 mb-4">
                @forelse($project->milestones as $ms)
                    <div class="flex items-center gap-4 p-3 rounded-xl bg-gray-100 dark:bg-dark-800/50">
                        <form method="POST" action="{{ route('projects.milestones.toggle', [$project, $ms]) }}">@csrf
                            @method('PATCH')
                            <button
                                class="w-6 h-6 rounded-md border {{ $ms->status == 'done' ? 'bg-emerald-500 border-emerald-500 text-white' : 'border-dark-500 text-transparent hover:border-blue-500' }} flex items-center justify-center transition-colors">✓</button>
                        </form>
                        <div class="flex-1">
                            <p class="text-sm {{ $ms->status == 'done' ? 'text-dark-400 line-through' : 'text-white' }}">
                                {{ $ms->title }}
                            </p>
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
                    class="flex-1 px-4 py-2 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                <input type="date" name="due_date" required
                    class="px-4 py-2 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm rounded-xl transition-colors">Tambah</button>
            </form>
        </div>
    </div>
@endsection