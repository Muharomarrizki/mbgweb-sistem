@extends('layouts.app')

@section('title', 'Purchase Order')
@section('subtitle', 'Kelola Purchase Order')

@section('content')
<div class="data-table-wrapper">
    <div class="data-table-header">
        <h3>Daftar Purchase Order</h3>
        <div style="display:flex; gap:0.75rem; align-items:center; flex-wrap:wrap;">
            <form method="GET" action="{{ route('purchase-orders.index') }}" style="display:flex; gap:0.5rem;">
                <div class="search-bar">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <input type="text" name="search" placeholder="Cari no. PO / supplier..." value="{{ request('search') }}">
                </div>
                <select name="status" class="form-select" style="width:auto; min-width:140px;" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    @foreach(['Draft','Dikirim','Disetujui','Selesai'] as $status)
                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
            </form>
            @if(auth()->user()->hasRole('bendahara'))
            <a href="{{ route('purchase-orders.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Buat PO
            </a>
            @endif
        </div>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>No. PO</th>
                <th>Tanggal</th>
                <th>Supplier</th>
                <th>Status</th>
                <th>Total Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($purchaseOrders as $po)
            <tr>
                <td style="font-weight:700; color:var(--primary);">{{ $po->no_po }}</td>
                <td>{{ $po->tanggal->format('d/m/Y') }}</td>
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
                <td style="font-weight:600;">Rp {{ number_format($po->total_harga, 0, ',', '.') }}</td>
                <td>
                    <div style="display:flex; gap:0.4rem;">
                        <a href="{{ route('purchase-orders.show', $po) }}" class="btn btn-outline btn-sm">Detail</a>
                        @if(auth()->user()->hasRole('bendahara') && $po->status === 'Draft')
                        <a href="{{ route('purchase-orders.edit', $po) }}" class="btn btn-outline btn-sm">Edit</a>
                        @endif
                        <a href="{{ route('purchase-orders.pdf', $po) }}" class="btn btn-outline btn-sm" title="Download PDF">PDF</a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center; color:var(--text-light); padding:2rem;">
                    Belum ada Purchase Order.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($purchaseOrders->hasPages())
    <div class="pagination-wrapper">
        {{ $purchaseOrders->links() }}
    </div>
    @endif
</div>
@endsection
