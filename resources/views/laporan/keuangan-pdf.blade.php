<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan & Arus Kas</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 20px; }
        .header p { margin: 5px 0 0; color: #666; }
        
        .summary-box { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .summary-box td { padding: 10px; border: 1px solid #ddd; text-align: center; width: 25%; }
        .summary-box .title { font-size: 10px; color: #666; text-transform: uppercase; }
        .summary-box .value { font-size: 14px; font-weight: bold; margin-top: 5px; }
        
        table.data { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.data th, table.data td { border: 1px solid #ccc; padding: 8px; }
        table.data th { background-color: #f5f5f5; text-align: left; font-weight: bold; }
        table.data td.right { text-align: right; }
        table.data tfoot td { font-weight: bold; background-color: #f5f5f5; }
        
        .footer { margin-top: 50px; text-align: right; }
        .footer p { margin: 0 0 60px 0; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN KEUANGAN & ARUS KAS</h1>
        <p>Satuan Pelayanan Pemenuhan Gizi (SPPG) Sukasejati 2</p>
        <p>Periode: {{ date('F Y', mktime(0,0,0,$bulan,1,$tahun)) }}</p>
    </div>

    <table class="summary-box">
        <tr>
            <td>
                <div class="title">Anggaran Bulanan</div>
                <div class="value">Rp {{ number_format($anggaran, 0, ',', '.') }}</div>
            </td>
            <td>
                <div class="title">Bahan Baku</div>
                <div class="value">Rp {{ number_format($totalInvoice, 0, ',', '.') }}</div>
            </td>
            <td>
                <div class="title">Operasional</div>
                <div class="value">Rp {{ number_format($totalOpex, 0, ',', '.') }}</div>
            </td>
            <td>
                <div class="title">Sisa Anggaran</div>
                <div class="value" style="{{ $sisaAnggaran < 0 ? 'color: red;' : 'color: green;' }}">Rp {{ number_format($sisaAnggaran, 0, ',', '.') }}</div>
            </td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th width="15%">Tanggal</th>
                <th width="45%">Keterangan Transaksi</th>
                <th width="20%">Kategori</th>
                <th width="20%" class="right">Nominal Keluar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksis as $t)
            <tr>
                <td>{{ \Carbon\Carbon::parse($t['tanggal'])->format('d/m/Y') }}</td>
                <td>{{ $t['keterangan'] }}</td>
                <td>{{ $t['kategori'] }}</td>
                <td class="right">Rp {{ number_format($t['nominal'], 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center; padding: 20px;">Tidak ada data pengeluaran pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="right">TOTAL PENGELUARAN :</td>
                <td class="right" style="color: red;">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>


</body>
</html>
