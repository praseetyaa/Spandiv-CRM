@extends('layouts.app')
@section('title', 'Detail Invoice')
@section('subtitle', $invoice->invoice_number)

@section('header-actions')
    <div class="flex items-center gap-2">
        <a href="{{ route('invoices.pdf', $invoice) }}"
            class="px-4 py-2 bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-500 hover:to-pink-500 text-white text-sm font-medium rounded-xl shadow-lg shadow-red-500/20 transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Download PDF
        </a>
        <a href="{{ route('invoices.edit', $invoice) }}"
            class="px-4 py-2 bg-dark-700 hover:bg-dark-600 text-white text-sm rounded-xl transition-colors">Edit</a>
    </div>
@endsection

@section('content')
    {{-- Invoice Document --}}
    <div class="max-w-4xl mx-auto">
        <div class="glass rounded-2xl overflow-hidden">
            {{-- Invoice Header --}}
            <div class="bg-gradient-to-r from-blue-600/20 to-purple-600/20 p-8 border-b border-dark-700/30">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center gap-3 mb-3">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-white">Spandiv Digital Solutions</h2>
                                <p class="text-xs text-dark-300">Digital Agency & Creative Studio</p>
                            </div>
                        </div>
                        <p class="text-xs text-dark-400 leading-relaxed">Indonesia<br>hello@spandiv.com</p>
                    </div>
                    <div class="text-right">
                        <h1 class="text-3xl font-bold gradient-text mb-2">INVOICE</h1>
                        <p class="text-sm text-white font-mono">{{ $invoice->invoice_number }}</p>
                        @php $ic = ['paid' => 'emerald', 'partial' => 'amber', 'overdue' => 'red', 'sent' => 'blue', 'draft' => 'dark'][$invoice->status] ?? 'dark'; @endphp
                        <span
                            class="inline-block mt-2 px-3 py-1 text-xs font-medium rounded-lg bg-{{ $ic }}-500/10 text-{{ $ic }}-400 border border-{{ $ic }}-500/20">{{ strtoupper($invoice->status) }}</span>
                    </div>
                </div>
            </div>

            {{-- Invoice Meta --}}
            <div class="p-8 border-b border-dark-700/30">
                <div class="grid grid-cols-2 gap-8">
                    <div>
                        <p class="text-xs text-dark-500 uppercase tracking-wider font-semibold mb-2">Ditagihkan Kepada</p>
                        <p class="text-white font-semibold text-lg">{{ $invoice->client->name }}</p>
                        @if($invoice->client->business_name)
                            <p class="text-dark-300 text-sm">{{ $invoice->client->business_name }}</p>
                        @endif
                        @if($invoice->client->email)
                            <p class="text-dark-400 text-sm mt-1">{{ $invoice->client->email }}</p>
                        @endif
                        @if($invoice->client->phone)
                            <p class="text-dark-400 text-sm">{{ $invoice->client->phone }}</p>
                        @endif
                    </div>
                    <div class="text-right space-y-2">
                        <div>
                            <p class="text-xs text-dark-500 uppercase tracking-wider">Tanggal Invoice</p>
                            <p class="text-white text-sm font-medium">{{ $invoice->issue_date->format('d F Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-dark-500 uppercase tracking-wider">Jatuh Tempo</p>
                            <p
                                class="text-sm font-medium {{ $invoice->due_date < now() && $invoice->status !== 'paid' ? 'text-red-400' : 'text-white' }}">
                                {{ $invoice->due_date->format('d F Y') }}</p>
                        </div>
                        @if($invoice->project)
                            <div>
                                <p class="text-xs text-dark-500 uppercase tracking-wider">Project</p>
                                <p class="text-white text-sm">{{ $invoice->project->title }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Line Items Table --}}
            <div class="p-8 border-b border-dark-700/30">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-dark-600/50">
                            <th class="text-left text-xs font-semibold text-dark-400 uppercase tracking-wider py-3 w-12">No
                            </th>
                            <th class="text-left text-xs font-semibold text-dark-400 uppercase tracking-wider py-3">
                                Deskripsi</th>
                            <th class="text-center text-xs font-semibold text-dark-400 uppercase tracking-wider py-3 w-20">
                                Qty</th>
                            <th class="text-right text-xs font-semibold text-dark-400 uppercase tracking-wider py-3 w-40">
                                Harga Satuan</th>
                            <th class="text-right text-xs font-semibold text-dark-400 uppercase tracking-wider py-3 w-40">
                                Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoice->items as $index => $item)
                            <tr class="border-b border-dark-700/20">
                                <td class="py-4 text-sm text-dark-400">{{ $index + 1 }}</td>
                                <td class="py-4 text-sm text-white">{{ $item->description }}</td>
                                <td class="py-4 text-sm text-dark-300 text-center">{{ $item->quantity }}</td>
                                <td class="py-4 text-sm text-dark-300 text-right">Rp
                                    {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                <td class="py-4 text-sm text-white font-medium text-right">Rp
                                    {{ number_format($item->amount, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-dark-400 text-sm">Belum ada item rincian</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Totals Section --}}
            <div class="p-8">
                <div class="flex justify-end">
                    <div class="w-80 space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-dark-400">Subtotal</span>
                            <span class="text-white">Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-dark-400">PPN ({{ $invoice->tax_rate }}%)</span>
                            <span class="text-white">Rp {{ number_format($invoice->tax_amount, 0, ',', '.') }}</span>
                        </div>
                        @if($invoice->discount > 0)
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-dark-400">Diskon</span>
                                <span class="text-red-400">- Rp {{ number_format($invoice->discount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        <div class="border-t border-dark-600/50 pt-3 flex items-center justify-between">
                            <span class="text-white font-bold text-lg">Grand Total</span>
                            <span class="text-2xl font-bold gradient-text">Rp
                                {{ number_format($invoice->total_amount, 0, ',', '.') }}</span>
                        </div>
                        @if($invoice->paid_amount > 0)
                            <div class="flex items-center justify-between text-sm pt-2">
                                <span class="text-dark-400">Terbayar</span>
                                <span class="text-emerald-400 font-medium">Rp
                                    {{ number_format($invoice->paid_amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-dark-400">Sisa</span>
                                <span class="text-amber-400 font-semibold">Rp
                                    {{ number_format($invoice->total_amount - $invoice->paid_amount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Notes --}}
        @if($invoice->notes)
            <div class="glass rounded-2xl p-6 mt-6">
                <h4 class="text-sm font-semibold text-dark-300 mb-2">Catatan</h4>
                <p class="text-sm text-dark-400">{{ $invoice->notes }}</p>
            </div>
        @endif

        {{-- Payment History --}}
        <div class="glass rounded-2xl p-6 mt-6">
            <div class="flex items-center justify-between mb-4">
                <h4 class="font-semibold text-white">Riwayat Pembayaran</h4>
                @if($invoice->status !== 'paid')
                    <a href="{{ route('payments.create', ['invoice_id' => $invoice->id]) }}"
                        class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm rounded-xl transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Catat Pembayaran
                    </a>
                @endif
            </div>
            <div class="space-y-3">
                @forelse($invoice->payments as $pay)
                    <div class="bg-dark-800/50 rounded-xl p-4 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-white">Rp {{ number_format($pay->amount, 0, ',', '.') }}</p>
                            <p class="text-xs text-dark-400">{{ $pay->payment_date->format('d M Y') }} ·
                                {{ ucfirst(str_replace('-', ' ', $pay->method)) }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-dark-400 text-center py-4">Belum ada pembayaran</p>
                @endforelse
            </div>
        </div>

        {{-- Back --}}
        <div class="mt-6">
            <a href="{{ route('invoices.index') }}" class="text-dark-400 hover:text-white text-sm transition-colors">←
                Kembali ke daftar invoice</a>
        </div>
    </div>
@endsection