@extends('layouts.app')
@section('title', 'Edit Invoice')
@section('subtitle', $invoice->invoice_number)

@section('content')
    <div class="max-w-5xl">
        <div class="glass rounded-2xl p-8">
            <form method="POST" action="{{ route('invoices.update', $invoice) }}" id="invoiceForm">
                @csrf
                @method('PUT')

                {{-- Invoice Info --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Client <span
                                class="text-red-400">*</span></label>
                        <select name="client_id" required
                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                            <option value="">Pilih Client</option>
                            @foreach($clients as $c)
                                <option value="{{ $c->id }}" {{ old('client_id', $invoice->client_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Project (opsional)</label>
                        <select name="project_id"
                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                            <option value="">Tidak terkait project</option>
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}" {{ old('project_id', $invoice->project_id) == $p->id ? 'selected' : '' }}>{{ $p->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Subscription (opsional)</label>
                        <select name="subscription_id"
                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                            <option value="">Tidak terkait subscription</option>
                            @foreach($subscriptions as $s)
                                <option value="{{ $s->id }}" {{ old('subscription_id', $invoice->subscription_id) == $s->id ? 'selected' : '' }}>{{ $s->service->name ?? '' }} - {{ $s->client->name ?? '' }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Invoice Number</label>
                        <input type="text" value="{{ $invoice->invoice_number }}" disabled
                            class="w-full px-4 py-2.5 bg-dark-800/30 border border-dark-700/50 rounded-xl text-dark-400 text-sm cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Due Date</label>
                        <input type="date" name="due_date"
                            value="{{ old('due_date', $invoice->due_date->format('Y-m-d')) }}" required
                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Status <span
                                class="text-red-400">*</span></label>
                        <select name="status" required
                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                            @foreach(['draft' => 'Draft', 'sent' => 'Sent', 'partial' => 'Partial', 'paid' => 'Paid', 'overdue' => 'Overdue'] as $v => $l)
                                <option value="{{ $v }}" {{ old('status', $invoice->status) == $v ? 'selected' : '' }}>{{ $l }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Line Items --}}
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Rincian Item</h3>
                        <button type="button" onclick="addItem()"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm rounded-xl transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Item
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full" id="itemsTable">
                            <thead>
                                <tr class="border-b border-dark-600/50">
                                    <th
                                        class="text-left text-xs font-semibold text-dark-400 uppercase tracking-wider py-3 px-2 w-12">
                                        No</th>
                                    <th
                                        class="text-left text-xs font-semibold text-dark-400 uppercase tracking-wider py-3 px-2">
                                        Deskripsi</th>
                                    <th
                                        class="text-center text-xs font-semibold text-dark-400 uppercase tracking-wider py-3 px-2 w-24">
                                        Qty</th>
                                    <th
                                        class="text-right text-xs font-semibold text-dark-400 uppercase tracking-wider py-3 px-2 w-44">
                                        Harga Satuan</th>
                                    <th
                                        class="text-right text-xs font-semibold text-dark-400 uppercase tracking-wider py-3 px-2 w-44">
                                        Jumlah</th>
                                    <th class="w-12"></th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody">
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Totals --}}
                <div class="flex justify-end mb-8">
                    <div class="w-full md:w-96 space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-dark-400">Subtotal</span>
                            <span class="text-white font-medium" id="subtotalDisplay">Rp 0</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center gap-2">
                                <span class="text-dark-400">PPN</span>
                                <input type="number" name="tax_rate" value="{{ old('tax_rate', $invoice->tax_rate) }}"
                                    step="0.01" min="0" max="100" onchange="recalculate()"
                                    class="w-20 px-2 py-1 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-lg text-gray-900 dark:text-white text-sm text-center focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                                <span class="text-dark-400">%</span>
                            </div>
                            <span class="text-white font-medium" id="taxDisplay">Rp 0</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-dark-400">Diskon</span>
                            <input type="number" name="discount" value="{{ old('discount', $invoice->discount) }}" step="1"
                                min="0" onchange="recalculate()"
                                class="w-36 px-3 py-1.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-lg text-gray-900 dark:text-white text-sm text-right focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                        </div>
                        <div class="border-t border-dark-600/50 pt-3 flex items-center justify-between">
                            <span class="text-white font-semibold text-lg">Grand Total</span>
                            <span class="text-white font-bold text-xl gradient-text" id="totalDisplay">Rp 0</span>
                        </div>
                    </div>
                </div>

                {{-- Notes --}}
                <div class="mb-8">
                    <label class="block text-sm font-medium text-gray-600 dark:text-dark-300 mb-2">Catatan</label>
                    <textarea name="notes" rows="2"
                        class="w-full px-4 py-2.5 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">{{ old('notes', $invoice->notes) }}</textarea>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3 pt-4 border-t border-dark-600/30">
                    <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 text-white text-sm font-medium rounded-xl shadow-lg shadow-blue-500/20 transition-all">Update
                        Invoice</button>
                    <a href="{{ route('invoices.show', $invoice) }}"
                        class="px-6 py-2.5 bg-gray-200 dark:bg-dark-700 hover:bg-gray-300 dark:hover:bg-dark-600 text-gray-700 dark:text-white text-sm font-medium rounded-xl transition-colors">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let itemIndex = 0;

        function addItem(desc = '', qty = 1, price = 0) {
            const tbody = document.getElementById('itemsBody');
            const row = document.createElement('tr');
            row.className = 'border-b border-dark-700/30 item-row';
            row.dataset.index = itemIndex;
            row.innerHTML = `
            <td class="py-3 px-2 text-sm text-dark-400 item-number"></td>
            <td class="py-3 px-2">
                <input type="text" name="items[${itemIndex}][description]" value="${desc}" required placeholder="Deskripsi jasa/item..." class="w-full px-3 py-2 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-lg text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
            </td>
            <td class="py-3 px-2">
                <input type="number" name="items[${itemIndex}][quantity]" value="${qty}" min="1" required onchange="recalculate()" class="w-full px-3 py-2 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-lg text-gray-900 dark:text-white text-sm text-center focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
            </td>
            <td class="py-3 px-2">
                <input type="number" name="items[${itemIndex}][unit_price]" value="${price}" min="0" required step="1000" onchange="recalculate()" class="w-full px-3 py-2 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-lg text-gray-900 dark:text-white text-sm text-right focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
            </td>
            <td class="py-3 px-2 text-right text-sm text-gray-900 dark:text-white font-medium item-amount">Rp 0</td>
            <td class="py-3 px-2">
                <button type="button" onclick="removeItem(this)" class="text-dark-500 hover:text-red-400 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </td>
        `;
            tbody.appendChild(row);
            itemIndex++;
            renumberItems();
            recalculate();
        }

        function removeItem(btn) {
            const rows = document.querySelectorAll('.item-row');
            if (rows.length <= 1) return;
            btn.closest('tr').remove();
            renumberItems();
            recalculate();
        }

        function renumberItems() {
            document.querySelectorAll('.item-row').forEach((row, i) => {
                row.querySelector('.item-number').textContent = i + 1;
            });
        }

        function recalculate() {
            let subtotal = 0;
            document.querySelectorAll('.item-row').forEach(row => {
                const qty = parseFloat(row.querySelector('input[name*="quantity"]').value) || 0;
                const price = parseFloat(row.querySelector('input[name*="unit_price"]').value) || 0;
                const amount = qty * price;
                subtotal += amount;
                row.querySelector('.item-amount').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
            });

            const taxRate = parseFloat(document.querySelector('input[name="tax_rate"]').value) || 0;
            const discount = parseFloat(document.querySelector('input[name="discount"]').value) || 0;
            const taxAmount = subtotal * (taxRate / 100);
            const total = Math.max(0, subtotal + taxAmount - discount);

            document.getElementById('subtotalDisplay').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal);
            document.getElementById('taxDisplay').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(taxAmount));
            document.getElementById('totalDisplay').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(total));
        }

        // Load existing items
        @foreach($invoice->items as $item)
            addItem(@json($item->description), {{ $item->quantity }}, {{ $item->unit_price }});
        @endforeach

        @if($invoice->items->isEmpty())
            addItem();
        @endif
    </script>
@endpush