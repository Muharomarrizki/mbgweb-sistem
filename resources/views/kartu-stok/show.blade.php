@extends('layouts.app')

@section('title', 'Kartu Stok: ' . $bahanBaku->nama_bahan)
@section('subtitle', 'Riwayat mutasi stok bahan baku')

@section('content')
<div class="data-table-wrapper">
    <div class="data-table-header">
        <div>
            <h3 style="margin-bottom:0.25rem;">{{ $bahanBaku->nama_bahan }}</h3>
            <div style="font-size:0.85rem; color:var(--text-secondary);">
                Stok Saat Ini: <strong style="color:var(--text-primary);">{{ number_format($bahanBaku->stokGudang?->qty_aktual ?? 0, 2) }} {{ $bahanBaku->satuan }}</strong> 
                | Batas Minimum: <strong style="color:var(--text-primary);">{{ number_format($bahanBaku->stok_minimum, 2) }} {{ $bahanBaku->satuan }}</strong>
            </div>
        </div>
        <div style="display:flex; gap:0.5rem; align-items:center;">
            <form method="GET" action="{{ route('kartu-stok.show', $bahanBaku->id) }}" style="display:flex; gap:0.5rem;">
                <select name="bulan" class="form-select" style="width:auto; min-width:120px;" onchange="this.form.submit()">
                    <option value="">Semua Bulan</option>
                    @for($m=1; $m<=12; $m++)
                    <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                    </option>
                    @endfor
                </select>
                <select name="tahun" class="form-select" style="width:auto; min-width:100px;" onchange="this.form.submit()">
                    <option value="">Semua Tahun</option>
                    @for($y=date('Y'); $y>=date('Y')-3; $y--)
                    <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </form>
            <a href="{{ route('kartu-stok.index') }}" class="btn btn-outline btn-sm">← Kembali</a>
        </div>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Waktu</th>
                <th>Jenis Mutasi</th>
                <th style="text-align:right;">Masuk (+)</th>
                <th style="text-align:right;">Keluar (-)</th>
                <th style="text-align:right;">Sisa Stok</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($riwayat as $log)
            <tr>
                <td style="white-space:nowrap;">
                    <div style="font-weight:600;">{{ $log->created_at->format('d M Y') }}</div>
                    <div style="font-size:0.75rem; color:var(--text-light);">{{ $log->created_at->format('H:i') }} WIB</div>
                </td>
                <td>
                    @php
                        $badgeClass = match($log->jenis_transaksi) {
                            'Masuk' => 'badge-selesai',
                            'Keluar' => 'badge-belum',
                            default => 'badge-draft',
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ $log->jenis_transaksi }}</span>
                </td>
                <td style="text-align:right; color:var(--success); font-weight:600;">
                    {{ $log->jenis_transaksi === 'Masuk' ? '+ ' . number_format($log->qty, 2) : '-' }}
                </td>
                <td style="text-align:right; color:var(--danger); font-weight:600;">
                    {{ $log->jenis_transaksi === 'Keluar' ? '- ' . number_format($log->qty, 2) : '-' }}
                </td>
                <td style="text-align:right; font-weight:700;">{{ number_format($log->saldo_akhir, 2) }}</td>
                <td style="font-size:0.85rem;">{{ $log->keterangan }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center; color:var(--text-light); padding:2rem;">
                    Belum ada riwayat mutasi untuk bahan baku ini pada periode terpilih.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($riwayat->hasPages())
    <div class="pagination-wrapper">
        {{ $riwayat->links() }}
    </div>
    @endif
</div>
@endsection
