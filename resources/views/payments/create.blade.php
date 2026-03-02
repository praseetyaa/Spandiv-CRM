@extends('layouts.app')
@section('title', 'Catat Pembayaran')
@section('content')
    <div class="max-w-2xl">
        <div class="glass rounded-2xl p-8">
            <form method="POST" action="{{ route('payments.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div><label class="block text-sm font-medium text-dark-300 mb-2">Invoice <span
                            class="text-red-400">*</span></label>
                    <select name="invoice_id" required
                        class="w-full px-4 py-2.5 bg-dark-800/50 border border-dark-600/50 rounded-xl text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                        <option value="">Pilih Invoice</option>
                        @foreach($invoices as $inv)<option value="{{ $inv->id }}" {{ ($selectedInvoice && $selectedInvoice->id == $inv->id) || old('invoice_id') == $inv->id ? 'selected' : '' }}>
                            {{ $inv->invoice_number }} — {{ $inv->client->name }} (Rp
                            {{ number_format($inv->total_amount - $inv->paid_amount, 0, ',', '.') }} sisa)</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div><label class="block text-sm font-medium text-dark-300 mb-2">Jumlah (Rp) <span
                                class="text-red-400">*</span></label><input type="number" name="amount"
                            value="{{ old('amount') }}" required
                            class="w-full px-4 py-2.5 bg-dark-800/50 border border-dark-600/50 rounded-xl text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                    </div>
                    <div><label class="block text-sm font-medium text-dark-300 mb-2">Metode <span
                                class="text-red-400">*</span></label><select name="method" required
                            class="w-full px-4 py-2.5 bg-dark-800/50 border border-dark-600/50 rounded-xl text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                            <option value="transfer">Transfer</option>
                            <option value="cash">Cash</option>
                            <option value="e-wallet">E-Wallet</option>
                        </select></div>
                    <div><label class="block text-sm font-medium text-dark-300 mb-2">Tanggal <span
                                class="text-red-400">*</span></label><input type="date" name="payment_date"
                            value="{{ old('payment_date', now()->format('Y-m-d')) }}" required
                            class="w-full px-4 py-2.5 bg-dark-800/50 border border-dark-600/50 rounded-xl text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                    </div>
                    <div><label class="block text-sm font-medium text-dark-300 mb-2">Bukti Pembayaran</label><input
                            type="file" name="proof_file" accept=".jpg,.jpeg,.png,.pdf"
                            class="w-full px-4 py-2.5 bg-dark-800/50 border border-dark-600/50 rounded-xl text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-blue-500/20 file:text-blue-400 file:text-xs">
                    </div>
                </div>
                <div><label class="block text-sm font-medium text-dark-300 mb-2">Catatan</label><textarea name="notes"
                        rows="2"
                        class="w-full px-4 py-2.5 bg-dark-800/50 border border-dark-600/50 rounded-xl text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">{{ old('notes') }}</textarea>
                </div>
                <div class="flex items-center gap-3 pt-4">
                    <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-emerald-600 to-cyan-600 text-white text-sm font-medium rounded-xl shadow-lg shadow-emerald-500/20 transition-all">Catat
                        Pembayaran</button>
                    <a href="{{ route('payments.index') }}"
                        class="px-6 py-2.5 bg-dark-700 hover:bg-dark-600 text-white text-sm font-medium rounded-xl transition-colors">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection