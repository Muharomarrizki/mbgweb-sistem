<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>PO {{ $purchaseOrder->no_po }}</title>
    <style>
        @page { margin: 40px; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1e293b; line-height: 1.5; }

        .header { display: table; width: 100%; border-bottom: 3px solid #4f46e5; padding-bottom: 15px; margin-bottom: 20px; }
        .header-left { display: table-cell; vertical-align: top; }
        .header-left h1 { font-size: 20px; color: #4f46e5; font-weight: 800; margin-bottom: 2px; }
        .header-left p { color: #64748b; font-size: 10px; }
        .header-right { display: table-cell; text-align: right; vertical-align: top; }
        .header-right .po-number { font-size: 16px; font-weight: 800; color: #0f172a; margin-bottom: 2px; }
        .header-right .po-status { display: inline-block; padding: 2px 10px; border-radius: 100px; font-size: 9px; font-weight: 700; background: #d1fae5; color: #065f46; margin-top: 3px; }

        .info-grid { display: table; width: 100%; margin-bottom: 20px; }
        .info-col { display: table-cell; width: 50%; vertical-align: top; }
        .info-block { margin-bottom: 10px; }
        .info-label { font-size: 9px; color: #94a3b8; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; }
        .info-value { font-size: 11px; font-weight: 600; margin-top: 2px; }

        table.items { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table.items th { background: #f1f5f9; padding: 8px 10px; text-align: left; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; font-weight: 700; border-bottom: 2px solid #e2e8f0; }
        table.items td { padding: 8px 10px; border-bottom: 1px solid #f1f5f9; font-size: 10px; }
        table.items tbody tr:last-child td { border-bottom: 2px solid #e2e8f0; }
        table.items .text-right { text-align: right; }
        table.items tfoot td { padding: 10px; font-weight: 800; font-size: 12px; }

        .total-row { background: #f8fafc; }

        .footer { margin-top: 40px; display: table; width: 100%; }
        .sign-col { display: table-cell; width: 33.33%; text-align: center; padding: 10px; vertical-align: bottom; }
        .sign-title { font-size: 9px; color: #64748b; margin-bottom: 60px; }
        .sign-name { font-weight: bold; font-size: 11px; text-decoration: underline; margin-bottom: 3px; }
        .sign-line { font-size: 10px; color: #1e293b; }

        .notes { margin-top: 15px; padding: 10px; background: #f8fafc; border-radius: 5px; font-size: 10px; }
        .notes-title { font-weight: 700; margin-bottom: 3px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <h1>SPPG Sukasejati 2</h1>
            <p>Badan Gizi Nasional</p>
        </div>
        <div class="header-right">
            <div class="po-number">{{ $purchaseOrder->no_po }}</div>
            <div style="font-size:10px; color:#64748b;">Purchase Order</div>
            <div class="po-status">{{ $purchaseOrder->status }}</div>
        </div>
    </div>

    <div class="info-grid">
        <div class="info-col">
            <div class="info-block">
                <div class="info-label">Supplier</div>
                <div class="info-value">{{ $purchaseOrder->supplier->nama }}</div>
                <div style="font-size:10px; color:#64748b;">{{ $purchaseOrder->supplier->alamat }}</div>
                <div style="font-size:10px; color:#64748b;">PIC: {{ $purchaseOrder->supplier->pic }} | Telp: {{ $purchaseOrder->supplier->telepon }}</div>
            </div>
        </div>
        <div class="info-col" style="text-align:right;">
            <div class="info-block">
                <div class="info-label">Tanggal PO</div>
                <div class="info-value">{{ $purchaseOrder->tanggal->format('d F Y') }}</div>
            </div>
            @if($purchaseOrder->supplier->npwp)
            <div class="info-block">
                <div class="info-label">NPWP Supplier</div>
                <div class="info-value">{{ $purchaseOrder->supplier->npwp }}</div>
            </div>
            @endif
        </div>
    </div>

    <table class="items">
        <thead>
            <tr>
                <th style="width:5%;">No</th>
                <th style="width:35%;">Bahan Baku</th>
                <th style="width:10%;">Satuan</th>
                <th class="text-right" style="width:15%;">Qty</th>
                <th class="text-right" style="width:17%;">Harga Satuan</th>
                <th class="text-right" style="width:18%;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($purchaseOrder->items as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td style="font-weight:600;">{{ $item->bahanBaku->nama_bahan }}</td>
                <td>{{ $item->bahanBaku->satuan }}</td>
                <td class="text-right">{{ number_format($item->qty, 2) }}</td>
                <td class="text-right">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                <td class="text-right" style="font-weight:600;">Rp {{ number_format($item->qty * $item->harga_satuan, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="5" class="text-right">TOTAL</td>
                <td class="text-right" style="color:#4f46e5;">Rp {{ number_format($purchaseOrder->total_harga, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    @if($purchaseOrder->catatan)
    <div class="notes">
        <div class="notes-title">Catatan:</div>
        {{ $purchaseOrder->catatan }}
    </div>
    @endif

    <div class="footer">
        <div class="sign-col">
            <div class="sign-title">Dibuat oleh</div>
            <div class="sign-name">Pram</div>
            <div class="sign-line">Pengawas Keuangan</div>
        </div>
    
        <div class="sign-col">
            <div class="sign-title">Disetujui oleh</div>
            <div class="sign-name">Ir. Ahmad Subarjo</div>
            <div class="sign-line">Kepala SPPG</div>
        </div>
        
        <div class="sign-col">
            <div class="sign-title">Diterima oleh</div>
            <div class="sign-name">( .................................... )</div>
            <div class="sign-line">Supplier</div>
        </div>
    </div>
</body>
</html>