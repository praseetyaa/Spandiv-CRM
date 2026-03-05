@extends('layouts.app')
@section('title', 'Catat Pembayaran')
@section('content')
    <div class="">
        {{-- Selected Invoice Info --}}
        @if($selectedInvoice)
            <div class="mb-6 p-4 bg-blue-500/10 border border-blue-500/20 rounded-xl">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-500/15 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $selectedInvoice->invoice_number }} — {{ $selectedInvoice->client->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-dark-400">
                            Total: Rp {{ number_format($selectedInvoice->total_amount, 0, ',', '.') }}
                            · Terbayar: Rp {{ number_format($selectedInvoice->paid_amount, 0, ',', '.') }}
                            · <span class="text-amber-400 font-medium">Sisa: Rp {{ number_format($selectedInvoice->total_amount - $selectedInvoice->paid_amount, 0, ',', '.') }}</span>
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div class="glass rounded-2xl p-8">
            <form method="POST" action="{{ route('payments.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Invoice <span class="text-red-400">*</span></label>
                    <select name="invoice_id" required id="invoiceSelect"
                        class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                        <option value="">Pilih Invoice</option>
                        @foreach($invoices as $inv)
                            <option value="{{ $inv->id }}"
                                data-remaining="{{ $inv->total_amount - $inv->paid_amount }}"
                                {{ ($selectedInvoice && $selectedInvoice->id == $inv->id) || old('invoice_id') == $inv->id ? 'selected' : '' }}>
                                {{ $inv->invoice_number }} — {{ $inv->client->name }} (Rp {{ number_format($inv->total_amount - $inv->paid_amount, 0, ',', '.') }} sisa)
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Jumlah (Rp) <span class="text-red-400">*</span></label>
                        <input type="number" name="amount" id="amountInput"
                            value="{{ old('amount', $selectedInvoice ? $selectedInvoice->total_amount - $selectedInvoice->paid_amount : '') }}" required
                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Metode <span class="text-red-400">*</span></label>
                        <select name="method" required
                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                            <option value="transfer">Transfer</option>
                            <option value="cash">Cash</option>
                            <option value="e-wallet">E-Wallet</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Tanggal <span class="text-red-400">*</span></label>
                        <input type="date" name="payment_date"
                            value="{{ old('payment_date', now()->format('Y-m-d')) }}" required
                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Bukti Pembayaran</label>
                        <input type="file" name="proof_file" accept=".jpg,.jpeg,.png,.pdf"
                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-blue-500/20 file:text-blue-400 file:text-xs">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Catatan</label>
                    <textarea name="notes" rows="2"
                        class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">{{ old('notes') }}</textarea>
                </div>
                <div class="flex items-center gap-3 pt-4">
                    <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-emerald-600 to-cyan-600 text-white text-sm font-medium rounded-xl shadow-lg shadow-emerald-500/20 transition-all">Catat Pembayaran</button>
                    <a href="{{ route('payments.index') }}"
                        class="px-6 py-2.5 bg-gray-200 dark:bg-dark-700 hover:bg-gray-300 dark:hover:bg-dark-600 text-gray-700 dark:text-white text-sm font-medium rounded-xl transition-colors">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Auto-fill amount when invoice is selected
    document.getElementById('invoiceSelect').addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        const remaining = selected.dataset.remaining;
        if (remaining) {
            document.getElementById('amountInput').value = Math.round(remaining);
        }
    });
</script>
@endpush