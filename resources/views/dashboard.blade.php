@extends('layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Ringkasan aktivitas SPPG Sukasejati 2')

@section('content')
<!-- Stats Grid -->
<div class="grid-cols-4" style="margin-bottom: 1.5rem;">
    <div class="stat-card primary">
        <div class="stat-card-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
            </svg>
        </div>
        <div class="stat-card-value">{{ $totalPoBulanIni }}</div>
        <div class="stat-card-label">PO Bulan Ini</div>
    </div>

    <div class="stat-card success">
        <div class="stat-card-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35" />
            </svg>
        </div>
        <div class="stat-card-value">{{ $totalSupplierAktif }}</div>
        <div class="stat-card-label">Supplier Aktif</div>
    </div>

    <div class="stat-card accent">
        <div class="stat-card-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
            </svg>
        </div>
        <div class="stat-card-value">Rp {{ number_format($nilaiStok, 0, ',', '.') }}</div>
        <div class="stat-card-label">Nilai Stok Gudang</div>
    </div>

    <div class="stat-card warning">
        <div class="stat-card-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
            </svg>
        </div>
        <div class="stat-card-value">Rp {{ number_format($nilaiInvoiceBelumBayar, 0, ',', '.') }}</div>
        <div class="stat-card-label">Invoice Belum Lunas</div>
    </div>
</div>

<!-- Additional Stats Row -->
<div class="grid-cols-2" style="margin-bottom: 1.5rem;">
    <div class="stat-card danger">
        <div style="display:flex; align-items:center; justify-content:space-between;">
            <div>
                <div class="stat-card-label">Total Pengeluaran (Termasuk Bahan Baku)</div>
                <div class="stat-card-value" style="font-size:1.5rem;">Rp {{ number_format($totalPengeluaranBulanIni + $totalInvoiceBulanIni, 0, ',', '.') }}</div>
            </div>
            <div class="stat-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        <div style="margin-top: 10px; padding-top: 10px; border-top: 1px solid rgba(0,0,0,0.1); display:flex; justify-content:space-between;">
            <span class="stat-card-label">Sisa Anggaran:</span>
            <span class="stat-card-value" style="font-size: 1rem;">Rp {{ number_format($anggaranBulanIni - ($totalPengeluaranBulanIni + $totalInvoiceBulanIni), 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="stat-card primary">
        <div style="display:flex; align-items:center; justify-content:space-between;">
            <div>
                <div class="stat-card-label">Invoice Belum Dibayar</div>
                <div class="stat-card-value" style="font-size:1.5rem;">{{ $totalInvoiceBelumBayar }} Invoice</div>
            </div>
            <div class="stat-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z" />
                </svg>
            </div>
        </div>
    </div>
</div>

<div class="grid-cols-2">
    <!-- Recent PO -->
    <div class="data-table-wrapper">
        <div class="data-table-header">
            <h3>Purchase Order Terbaru</h3>
            @if(auth()->user()->hasAnyRole(['bendahara', 'admin_gudang', 'pengawas']))
            <a href="{{ route('purchase-orders.index') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
            @endif
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>No. PO</th>
                    <th>Supplier</th>
                    <th>Status</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentPo as $po)
                <tr>
                    <td style="font-weight:600;">{{ $po->no_po }}</td>
                    <td>{{ $po->supplier->nama }}</td>
                    <td>
                        @php
                            $badgeClass = match($po->status) {
                                'Draft' => 'badge-draft',
                                'Dikirim' => 'badge-dikirim',
                                'Disetujui' => 'badge-disetujui',
                                'Selesai' => 'badge-selesai',
                                default => 'badge-draft',
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $po->status }}</span>
                    </td>
                    <td>Rp {{ number_format($po->total_harga, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center; color:var(--text-light); padding:2rem;">
                        Belum ada Purchase Order
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Low Stock Alert -->
    <div class="data-table-wrapper">
        <div class="data-table-header">
            <h3>⚠️ Stok Rendah</h3>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Bahan Baku</th>
                    <th>Stok Aktual</th>
                    <th>Minimum</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lowStockItems as $item)
                <tr>
                    <td style="font-weight:600;">{{ $item->bahanBaku->nama_bahan }}</td>
                    <td>{{ number_format($item->qty_aktual, 0) }} {{ $item->bahanBaku->satuan }}</td>
                    <td>{{ number_format($item->bahanBaku->stok_minimum, 0) }} {{ $item->bahanBaku->satuan }}</td>
                    <td><span class="badge badge-belum">Rendah</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center; color:var(--text-light); padding:2rem;">
                        ✅ Semua stok aman
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
