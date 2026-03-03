<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            color: #1e293b;
            line-height: 1.5;
        }

        .container {
            padding: 40px;
        }

        /* Header */
        .header {
            display: table;
            width: 100%;
            margin-bottom: 30px;
            border-bottom: 3px solid #3b82f6;
            padding-bottom: 20px;
        }

        .header-left {
            display: table-cell;
            vertical-align: top;
            width: 50%;
        }

        .header-right {
            display: table-cell;
            vertical-align: top;
            width: 50%;
            text-align: right;
        }

        .company-name {
            font-size: 20px;
            font-weight: bold;
            color: #3b82f6;
            margin-bottom: 4px;
        }

        .company-subtitle {
            font-size: 10px;
            color: #64748b;
            margin-bottom: 8px;
        }

        .company-info {
            font-size: 10px;
            color: #64748b;
        }

        .invoice-title {
            font-size: 32px;
            font-weight: bold;
            color: #3b82f6;
            margin-bottom: 6px;
        }

        .invoice-number {
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            font-family: 'DejaVu Sans Mono', monospace;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 8px;
        }

        .status-paid {
            background: #d1fae5;
            color: #065f46;
        }

        .status-sent {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-draft {
            background: #f1f5f9;
            color: #475569;
        }

        .status-partial {
            background: #fef3c7;
            color: #92400e;
        }

        .status-overdue {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Client Info */
        .meta-row {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }

        .meta-left {
            display: table-cell;
            vertical-align: top;
            width: 50%;
        }

        .meta-right {
            display: table-cell;
            vertical-align: top;
            width: 50%;
            text-align: right;
        }

        .meta-label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .meta-value {
            font-size: 12px;
            color: #1e293b;
        }

        .client-name {
            font-size: 16px;
            font-weight: bold;
            color: #1e293b;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }

        .items-table thead th {
            background: #f8fafc;
            border-top: 2px solid #e2e8f0;
            border-bottom: 2px solid #e2e8f0;
            padding: 10px 12px;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            font-weight: 600;
        }

        .items-table thead th:first-child {
            text-align: left;
        }

        .items-table thead th.text-center {
            text-align: center;
        }

        .items-table thead th.text-right {
            text-align: right;
        }

        .items-table tbody td {
            padding: 12px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 12px;
        }

        .items-table tbody td.text-center {
            text-align: center;
        }

        .items-table tbody td.text-right {
            text-align: right;
        }

        .items-table tbody tr:last-child td {
            border-bottom: 2px solid #e2e8f0;
        }

        /* Totals */
        .totals-wrapper {
            display: table;
            width: 100%;
        }

        .totals-spacer {
            display: table-cell;
            width: 55%;
        }

        .totals-box {
            display: table-cell;
            width: 45%;
        }

        .total-row {
            display: table;
            width: 100%;
            margin-bottom: 6px;
        }

        .total-label {
            display: table-cell;
            text-align: left;
            padding: 4px 0;
            color: #64748b;
            font-size: 12px;
        }

        .total-value {
            display: table-cell;
            text-align: right;
            padding: 4px 0;
            font-size: 12px;
            color: #1e293b;
        }

        .grand-total-row {
            display: table;
            width: 100%;
            border-top: 2px solid #3b82f6;
            margin-top: 8px;
            padding-top: 8px;
        }

        .grand-total-label {
            display: table-cell;
            text-align: left;
            font-size: 14px;
            font-weight: bold;
            color: #1e293b;
        }

        .grand-total-value {
            display: table-cell;
            text-align: right;
            font-size: 16px;
            font-weight: bold;
            color: #3b82f6;
        }

        .discount-value {
            color: #dc2626;
        }

        /* Notes */
        .notes-section {
            margin-top: 30px;
            padding: 16px;
            background: #f8fafc;
            border-radius: 8px;
            border-left: 3px solid #3b82f6;
        }

        .notes-label {
            font-size: 10px;
            text-transform: uppercase;
            color: #64748b;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .notes-text {
            font-size: 11px;
            color: #475569;
        }

        /* Footer */
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 16px;
        }

        /* Payment Info */
        .payment-info {
            margin-top: 20px;
        }

        .payment-title {
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .payment-detail {
            font-size: 11px;
            color: #475569;
        }
    </style>
</head>

<body>
    <div class="container">
        {{-- Header --}}
        <div class="header">
            <div class="header-left">
                <div class="company-name">{{ $invoice->company?->name ?? 'My Company' }}</div>
                <div class="company-subtitle">{{ $invoice->company?->address ?? '' }}</div>
                <div class="company-info">
                    {{ $invoice->company?->phone ?? '' }}<br>
                    {{ $invoice->company?->email ?? '' }}
                </div>
            </div>
            <div class="header-right">
                <div class="invoice-title">INVOICE</div>
                <div class="invoice-number">{{ $invoice->invoice_number }}</div>
                <div class="status-badge status-{{ $invoice->status }}">{{ strtoupper($invoice->status) }}</div>
            </div>
        </div>

        {{-- Client & Dates --}}
        <div class="meta-row">
            <div class="meta-left">
                <div class="meta-label">Ditagihkan Kepada</div>
                <div class="client-name">{{ $invoice->client->name }}</div>
                @if($invoice->client->business_name)
                    <div class="meta-value">{{ $invoice->client->business_name }}</div>
                @endif
                @if($invoice->client->email)
                    <div class="meta-value">{{ $invoice->client->email }}</div>
                @endif
                @if($invoice->client->phone)
                    <div class="meta-value">{{ $invoice->client->phone }}</div>
                @endif
            </div>
            <div class="meta-right">
                <div style="margin-bottom: 12px;">
                    <div class="meta-label">Tanggal Invoice</div>
                    <div class="meta-value" style="font-weight: 600;">{{ $invoice->issue_date->format('d F Y') }}</div>
                </div>
                <div style="margin-bottom: 12px;">
                    <div class="meta-label">Jatuh Tempo</div>
                    <div class="meta-value"
                        style="font-weight: 600; {{ $invoice->due_date < now() && $invoice->status !== 'paid' ? 'color: #dc2626;' : '' }}">
                        {{ $invoice->due_date->format('d F Y') }}</div>
                </div>
                @if($invoice->project)
                    <div>
                        <div class="meta-label">Project</div>
                        <div class="meta-value">{{ $invoice->project->title }}</div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Items Table --}}
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th>Deskripsi</th>
                    <th class="text-center" style="width: 60px;">Qty</th>
                    <th class="text-right" style="width: 130px;">Harga Satuan</th>
                    <th class="text-right" style="width: 130px;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->description }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                        <td class="text-right" style="font-weight: 600;">Rp {{ number_format($item->amount, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Totals --}}
        <div class="totals-wrapper">
            <div class="totals-spacer"></div>
            <div class="totals-box">
                <div class="total-row">
                    <div class="total-label">Subtotal</div>
                    <div class="total-value">Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</div>
                </div>
                <div class="total-row">
                    <div class="total-label">PPN ({{ $invoice->tax_rate }}%)</div>
                    <div class="total-value">Rp {{ number_format($invoice->tax_amount, 0, ',', '.') }}</div>
                </div>
                @if($invoice->discount > 0)
                    <div class="total-row">
                        <div class="total-label">Diskon</div>
                        <div class="total-value discount-value">- Rp {{ number_format($invoice->discount, 0, ',', '.') }}
                        </div>
                    </div>
                @endif
                <div class="grand-total-row">
                    <div class="grand-total-label">Grand Total</div>
                    <div class="grand-total-value">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</div>
                </div>
                @if($invoice->paid_amount > 0)
                    <div class="total-row" style="margin-top: 10px;">
                        <div class="total-label">Terbayar</div>
                        <div class="total-value" style="color: #059669;">Rp
                            {{ number_format($invoice->paid_amount, 0, ',', '.') }}</div>
                    </div>
                    <div class="total-row">
                        <div class="total-label" style="font-weight: 600;">Sisa Tagihan</div>
                        <div class="total-value" style="font-weight: 600; color: #dc2626;">Rp
                            {{ number_format($invoice->total_amount - $invoice->paid_amount, 0, ',', '.') }}</div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Notes --}}
        @if($invoice->notes)
            <div class="notes-section">
                <div class="notes-label">Catatan</div>
                <div class="notes-text">{{ $invoice->notes }}</div>
            </div>
        @endif

        {{-- Payment Info --}}
        <div class="payment-info">
            <div class="payment-title">Informasi Pembayaran</div>
            <div class="payment-detail">
                Pembayaran dapat dilakukan melalui transfer bank atau metode lain yang disepakati.<br>
                Mohon sertakan nomor invoice <strong>{{ $invoice->invoice_number }}</strong> pada keterangan transfer.
            </div>
        </div>

        {{-- Footer --}}
        <div class="footer">
            Invoice ini dibuat secara digital dan sah tanpa tanda tangan.<br>
            &copy; {{ date('Y') }} {{ $invoice->company?->name ?? 'My Company' }}. All rights reserved.
        </div>
    </div>
</body>

</html>