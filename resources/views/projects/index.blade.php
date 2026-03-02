@extends('layouts.app')
@section('title', 'Project Management')
@section('subtitle', 'Kelola project Spandiv')
@section('header-actions')
    <a href="{{ route('projects.create') }}"
        class="px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 text-white text-sm font-medium rounded-xl shadow-lg shadow-blue-500/20 transition-all flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>Tambah Project</a>
@endsection
@section('content')
    <div class="glass rounded-2xl p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-3 items-center">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari project..."
                class="flex-1 min-w-[200px] px-4 py-2 bg-dark-800/50 border border-dark-600/50 rounded-xl text-white text-sm placeholder-dark-500 focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
            <select name="status"
                class="px-4 py-2 bg-dark-800/50 border border-dark-600/50 rounded-xl text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                <option value="">Semua Status</option>
                @foreach(['brief' => 'Brief', 'dp_paid' => 'DP Paid', 'on_progress' => 'On Progress', 'revision' => 'Revisi', 'waiting_client' => 'Waiting Client', 'completed' => 'Completed'] as $v => $l)
                    <option value="{{ $v }}" {{ request('status') == $v ? 'selected' : '' }}>{{ $l }}</option>
                @endforeach
            </select>
            <button type="submit"
                class="px-4 py-2 bg-dark-700 hover:bg-dark-600 text-white text-sm rounded-xl transition-colors">Filter</button>
        </form>
    </div>
    <div class="glass rounded-2xl overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="border-b border-dark-700/50">
                    <th class="text-left text-xs font-medium text-dark-400 uppercase px-6 py-4">Project</th>
                    <th class="text-left text-xs font-medium text-dark-400 uppercase px-6 py-4">Client</th>
                    <th class="text-left text-xs font-medium text-dark-400 uppercase px-6 py-4">Status</th>
                    <th class="text-left text-xs font-medium text-dark-400 uppercase px-6 py-4">Progress</th>
                    <th class="text-left text-xs font-medium text-dark-400 uppercase px-6 py-4">Deadline</th>
                    <th class="text-left text-xs font-medium text-dark-400 uppercase px-6 py-4">Harga</th>
                    <th class="text-right text-xs font-medium text-dark-400 uppercase px-6 py-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark-700/30">
                @forelse($projects as $project)
                    @php $sColor = ['brief' => 'dark', 'dp_paid' => 'cyan', 'on_progress' => 'blue', 'revision' => 'amber', 'waiting_client' => 'purple', 'completed' => 'emerald'][$project->status] ?? 'dark'; @endphp
                    <tr class="table-row">
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-white">{{ $project->title }}</p>
                            <p class="text-xs text-dark-400">{{ $project->service->name }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-dark-300">{{ $project->client->name }}</td>
                        <td class="px-6 py-4"><span
                                class="px-2.5 py-1 text-xs rounded-lg bg-{{ $sColor }}-500/10 text-{{ $sColor }}-400">{{ ucfirst(str_replace('_', ' ', $project->status)) }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-20 bg-dark-700 rounded-full h-1.5">
                                    <div class="bg-blue-500 h-1.5 rounded-full"
                                        style="width:{{ $project->progress_percentage }}%"></div>
                                </div><span class="text-xs text-dark-400">{{ $project->progress_percentage }}%</span>
                            </div>
                        </td>
                        <td
                            class="px-6 py-4 text-sm {{ $project->deadline < now() && $project->status !== 'completed' ? 'text-red-400' : 'text-dark-300' }}">
                            {{ $project->deadline->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-dark-300">Rp {{ number_format($project->price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('projects.show', $project) }}"
                                    class="p-1.5 text-dark-400 hover:text-blue-400"><svg class="w-4 h-4" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg></a>
                                <a href="{{ route('projects.edit', $project) }}"
                                    class="p-1.5 text-dark-400 hover:text-amber-400"><svg class="w-4 h-4" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg></a>
                                <button type="button" onclick="confirmDelete('{{ route('projects.destroy', $project) }}')"
                                    class="p-1.5 text-dark-400 hover:text-red-400 transition-colors" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty <tr>
                    <td colspan="7" class="text-center text-dark-400 py-8">Belum ada project</td>
                </tr>@endforelse
            </tbody>
        </table>
        @if($projects->hasPages())
        <div class="px-6 py-4 border-t border-dark-700/50">{{ $projects->withQueryString()->links() }}</div>@endif
    </div>
@endsection