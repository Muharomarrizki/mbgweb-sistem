@extends('layouts.app')

@section('title', 'Daftar Invoice')
@section('subtitle', 'Kelola tagihan dari Purchase Order')

@section('content')
<div class="data-table-wrapper">
    <div class="data-table-header">
        <h3>Invoice Masuk</h3>
        <div style="display:flex; gap:0.75rem; align-items:center; flex-wrap:wrap;">
            <form method="GET" action="{{ route('invoices.index') }}" style="display:flex; gap:0.5rem;">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="Belum Dibayar" {{ request('status') == 'Belum Dibayar' ? 'selected' : '' }}>Belum Dibayar</option>
                    <option value="Sebagian" {{ request('status') == 'Sebagian' ? 'selected' : '' }}>Sebagian</option>
                    <option value="Lunas" {{ request('status') == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                </select>
                <div class="search-bar">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <input type="text" name="search" placeholder="Cari No Invoice / PO..." value="{{ request('search') }}">
                </div>
            </form>
        </div>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>No Invoice</th>
                <th>Supplier & PO</th>
                <th>Tgl Terbit</th>
                <th>Jatuh Tempo</th>
                <th style="text-align:right;">Total Tagihan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoices as $inv)
            <tr>
                <td style="font-weight:700;">{{ $inv->no_invoice }}</td>
                <td>
                    <div style="font-weight:600;">{{ $inv->purchaseOrder->supplier->nama }}</div>
                    <div style="font-size:0.75rem; color:var(--text-light);">{{ $inv->purchaseOrder->no_po }}</div>
                </td>
                <td>{{ $inv->tanggal_issued->format('d M Y') }}</td>
                <td>
                    <span style="{{ $inv->status_bayar !== 'Lunas' && $inv->jatuh_tempo < now() ? 'color:var(--danger); font-weight:600;' : '' }}">
                        {{ $inv->jatuh_tempo->format('d M Y') }}
                    </span>
                </td>
                <td style="text-align:right;">
                    <div style="font-weight:700;">Rp {{ number_format($inv->total_tagihan, 0, ',', '.') }}</div>
                    @if($inv->jumlah_dibayar > 0 && $inv->status_bayar !== 'Lunas')
                    <div style="font-size:0.75rem; color:var(--warning);">Dibayar: Rp {{ number_format($inv->jumlah_dibayar, 0, ',', '.') }}</div>
                    @endif
                </td>
                <td>
                    @php
                        $badgeClass = match($inv->status_bayar) {
                            'Lunas' => 'badge-selesai',
                            'Sebagian' => 'badge-sebagian',
                            'Belum Dibayar' => 'badge-belum',
                            default => 'badge-draft',
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ $inv->status_bayar }}</span>
                </td>
                <td>
                    <a href="{{ route('invoices.show', $inv->id) }}" class="btn btn-outline btn-sm">Lihat Detail</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center; color:var(--text-light); padding:2rem;">
                    Belum ada invoice. Invoice otomatis terbuat ketika PO berstatus Selesai.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($invoices->hasPages())
    <div class="pagination-wrapper">
        {{ $invoices->links() }}
    </div>
    @endif
</div>
@endsection
