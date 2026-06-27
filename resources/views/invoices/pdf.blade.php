<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $invoice->no_invoice }}</title>
    <style>
        @page { margin: 40px; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12px; color: #333; }
        .header { display: table; width: 100%; border-bottom: 2px solid #6366f1; padding-bottom: 20px; margin-bottom: 20px; }
        .header-left { display: table-cell; vertical-align: middle; }
        .header-right { display: table-cell; text-align: right; vertical-align: middle; }
        .company-name { font-size: 24px; font-weight: bold; color: #1e1b4b; margin: 0; }
        .company-tag { font-size: 12px; color: #64748b; margin: 5px 0 0; }
        .invoice-title { font-size: 28px; font-weight: bold; color: #6366f1; margin: 0; text-transform: uppercase; }
        .info-section { display: table; width: 100%; margin-bottom: 30px; }
        .info-box { display: table-cell; width: 50%; vertical-align: top; }
        .info-title { font-weight: bold; color: #64748b; font-size: 10px; text-transform: uppercase; margin-bottom: 5px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .table th { background: #f8fafc; padding: 10px; text-align: left; border-bottom: 2px solid #e2e8f0; font-size: 11px; text-transform: uppercase; }
        .table td { padding: 10px; border-bottom: 1px solid #e2e8f0; }
        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }
        .font-bold { font-weight: bold; }
        .totals { width: 40%; float: right; margin-bottom: 40px; }
        .totals table { width: 100%; border-collapse: collapse; }
        .totals td { padding: 8px 0; border-bottom: 1px solid #e2e8f0; }
        .totals tr:last-child td { border-bottom: none; font-size: 16px; font-weight: bold; color: #1e1b4b; border-top: 2px solid #1e1b4b; padding-top: 10px; }
        .status-badge { display: inline-block; padding: 5px 10px; font-weight: bold; font-size: 14px; text-transform: uppercase; border: 2px solid #333; border-radius: 4px; }
        .status-badge.Lunas { border-color: #10b981; color: #10b981; }
        .status-badge.Sebagian { border-color: #f59e0b; color: #f59e0b; }
        .status-badge.Belum { border-color: #ef4444; color: #ef4444; }
        .footer { display: table; width: 100%; margin-top: 50px; page-break-inside: avoid; }
        .sign-col { display: table-cell; width: 50%; text-align: center; padding: 10px; }
        .sign-line { border-top: 1px solid #cbd5e1; margin-top: 60px; padding-top: 5px; font-size: 10px; font-weight: 600; }
        .sign-title { font-size: 9px; color: #64748b; }
        .sign-name { margin-top: 60px; font-weight: bold; font-size: 12px; }
        .clear { clear: both; }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <h1 class="company-name">SPPG Sukasejati 2</h1>
            <p class="company-tag">Badan Gizi Nasional</p>
        </div>
        <div class="header-right">
            <h1 class="invoice-title">INVOICE</h1>
            <p style="margin:5px 0 0; font-size:14px;"><strong>#{{ $invoice->no_invoice }}</strong></p>
        </div>
    </div>

    <div class="info-section">
        <div class="info-box">
            <div class="info-title">Tagihan Kepada (Supplier):</div>
            <p style="margin:0; font-weight:bold; font-size:14px;">{{ $invoice->purchaseOrder->supplier->nama }}</p>
            <p style="margin:5px 0 0; color:#475569;">
                {{ $invoice->purchaseOrder->supplier->alamat }}<br>
                Telp: {{ $invoice->purchaseOrder->supplier->telepon }}<br>
                NPWP: {{ $invoice->purchaseOrder->supplier->npwp }}
            </p>
        </div>
        <div class="info-box" style="padding-left: 20px;">
            <table style="width:100%; border-collapse:collapse;">
                <tr>
                    <td style="padding-bottom:5px;" class="info-title">Referensi PO:</td>
                    <td style="padding-bottom:5px;" class="font-bold">{{ $invoice->purchaseOrder->no_po }}</td>
                </tr>
                <tr>
                    <td style="padding-bottom:5px;" class="info-title">Tanggal Terbit:</td>
                    <td style="padding-bottom:5px;">{{ $invoice->tanggal_issued->format('d F Y') }}</td>
                </tr>
                <tr>
                    <td style="padding-bottom:5px;" class="info-title">Jatuh Tempo:</td>
                    <td style="padding-bottom:5px; color:#ef4444;" class="font-bold">{{ $invoice->jatuh_tempo->format('d F Y') }}</td>
                </tr>
                <tr>
                    <td style="padding-top:10px;" colspan="2">
                        @php
                            $stClass = str_replace(' Dibayar', '', $invoice->status_bayar);
                        @endphp
                        <span class="status-badge {{ $stClass }}">{{ $invoice->status_bayar }}</span>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Deskripsi / Bahan Baku</th>
                <th class="text-center">Qty</th>
                <th class="text-right">Harga Satuan</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->purchaseOrder->items as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td class="font-bold">{{ $item->bahanBaku->nama_bahan }}</td>
                <td class="text-center">{{ number_format($item->qty, 2) }} {{ $item->bahanBaku->satuan }}</td>
                <td class="text-right">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                <td class="text-right font-bold">Rp {{ number_format($item->qty * $item->harga_satuan, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <table>
            <tr>
                <td>Subtotal</td>
                <td class="text-right">Rp {{ number_format($invoice->total_tagihan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Pembayaran Diterima</td>
                <td class="text-right" style="color:#10b981;">(Rp {{ number_format($invoice->jumlah_dibayar, 0, ',', '.') }})</td>
            </tr>
            <tr>
                <td>Sisa Tagihan</td>
                <td class="text-right">Rp {{ number_format($invoice->total_tagihan - $invoice->jumlah_dibayar, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>
    
    <div class="clear"></div>
    
    @if($invoice->catatan)
    <div style="margin-top:20px;">
        <p class="info-title">Catatan Pembayaran:</p>
        <p style="font-style:italic; color:#64748b; margin-top:5px;">{{ $invoice->catatan }}</p>
    </div>
    @endif

    <div class="footer">
        <div class="sign-col">
            <div class="sign-title">Disetujui oleh</div>
            <div class="sign-name">Ir. Ahmad Subarjo</div>
            <div style="font-size:10px; font-weight:600; margin-top:5px;">Kepala SPPG</div>
        </div>
        
        <div class="sign-col">
            <div class="sign-title">Diterima oleh</div>
            <div class="sign-name">( .................................... )</div>
            <div style="font-size:10px; font-weight:600; margin-top:5px;">Supplier</div>
        </div>
    </div>
</body>
</html>
