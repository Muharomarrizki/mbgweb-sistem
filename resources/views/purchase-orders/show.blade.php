@extends('layouts.app')

@section('title', 'Detail PO #' . $purchaseOrder->no_po)
@section('subtitle', 'Detail Purchase Order')

@section('content')
<div style="display:flex; gap:1.5rem; flex-wrap:wrap;">
    <!-- PO Detail Card -->
    <div style="flex:1; min-width:300px;">
        <div class="data-table-wrapper">
            <div class="data-table-header">
                <h3>{{ $purchaseOrder->no_po }}</h3>
                <div style="display:flex; gap:0.5rem; align-items:center;">
                    @php
                        $badgeClass = match($purchaseOrder->status) {
                            'Draft' => 'badge-draft',
                            'Dikirim' => 'badge-dikirim',
                            'Disetujui' => 'badge-disetujui',
                            'Selesai' => 'badge-selesai',
                            default => 'badge-draft',
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }}" style="font-size:0.85rem; padding:0.3rem 0.85rem;">{{ $purchaseOrder->status }}</span>
                    <a href="{{ route('purchase-orders.pdf', $purchaseOrder) }}" class="btn btn-outline btn-sm">📄 PDF</a>
                    <a href="{{ route('purchase-orders.index') }}" class="btn btn-outline btn-sm">← Kembali</a>
                </div>
            </div>

            <div style="padding:1.5rem;">
                <div class="grid-cols-3" style="margin-bottom:1.5rem;">
                    <div>
                        <div style="font-size:0.75rem; color:var(--text-light); font-weight:600; text-transform:uppercase; margin-bottom:0.25rem;">Supplier</div>
                        <div style="font-weight:600;">{{ $purchaseOrder->supplier->nama }}</div>
                        <div style="font-size:0.8rem; color:var(--text-secondary);">{{ $purchaseOrder->supplier->pic }}</div>
                    </div>
                    <div>
                        <div style="font-size:0.75rem; color:var(--text-light); font-weight:600; text-transform:uppercase; margin-bottom:0.25rem;">Tanggal PO</div>
                        <div style="font-weight:600;">{{ $purchaseOrder->tanggal->format('d F Y') }}</div>
                    </div>
                    <div>
                        <div style="font-size:0.75rem; color:var(--text-light); font-weight:600; text-transform:uppercase; margin-bottom:0.25rem;">Total Harga</div>
                        <div style="font-weight:800; font-size:1.15rem; color:var(--primary);">Rp {{ number_format($purchaseOrder->total_harga, 0, ',', '.') }}</div>
                    </div>
                </div>

                @if($purchaseOrder->catatan)
                <div style="padding:0.75rem 1rem; background:#f8fafc; border-radius:10px; margin-bottom:1.5rem;">
                    <div style="font-size:0.75rem; color:var(--text-light); font-weight:600; margin-bottom:0.25rem;">Catatan</div>
                    <div style="font-size:0.85rem;">{{ $purchaseOrder->catatan }}</div>
                </div>
                @endif

                <!-- Items Table -->
                <table class="data-table" style="border:1px solid var(--border); border-radius:10px; overflow:hidden;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Bahan Baku</th>
                            <th>Qty</th>
                            <th>Harga Satuan</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchaseOrder->items as $i => $item)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td style="font-weight:600;">{{ $item->bahanBaku->nama_bahan }}</td>
                            <td>{{ number_format($item->qty, 2) }} {{ $item->bahanBaku->satuan }}</td>
                            <td>Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                            <td style="font-weight:600;">Rp {{ number_format($item->qty * $item->harga_satuan, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background:#f8fafc;">
                            <td colspan="4" style="text-align:right; font-weight:700; border-top:2px solid var(--border);">Total:</td>
                            <td style="font-weight:800; font-size:1.05rem; color:var(--primary); border-top:2px solid var(--border);">Rp {{ number_format($purchaseOrder->total_harga, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>

                <!-- Invoice Info -->
                @if($purchaseOrder->invoice)
                <div style="margin-top:1.5rem; padding:1rem; border:1px solid var(--border); border-radius:12px; background:#f8fafc;">
                    <div style="display:flex; align-items:center; justify-content:space-between;">
                        <div>
                            <div style="font-size:0.75rem; color:var(--text-light); font-weight:600; text-transform:uppercase;">Invoice Terkait</div>
                            <div style="font-weight:700; color:var(--primary);">{{ $purchaseOrder->invoice->no_invoice }}</div>
                        </div>
                        <span class="badge {{ $purchaseOrder->invoice->status_bayar === 'Lunas' ? 'badge-lunas' : ($purchaseOrder->invoice->status_bayar === 'Sebagian' ? 'badge-sebagian' : 'badge-belum') }}">
                            {{ $purchaseOrder->invoice->status_bayar }}
                        </span>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Status & Actions Sidebar -->
    @if(auth()->user()->hasRole('bendahara'))
    <div style="width:280px;">
        <div class="data-table-wrapper">
            <div class="data-table-header">
                <h3>Ubah Status</h3>
            </div>
            <div style="padding:1.25rem;">
                @php
                    $allowedTransitions = match($purchaseOrder->status) {
                        'Draft' => ['Dikirim'],
                        'Dikirim' => ['Disetujui', 'Draft'],
                        'Disetujui' => ['Selesai', 'Dikirim'],
                        'Selesai' => [],
                        default => [],
                    };
                @endphp

                <!-- Status Flow -->
                <div style="margin-bottom:1.25rem;">
                    @foreach(['Draft', 'Dikirim', 'Disetujui', 'Selesai'] as $idx => $step)
                    <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:0.75rem;">
                        <div style="width:28px; height:28px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:0.7rem; font-weight:700;
                            {{ $purchaseOrder->status === $step ? 'background:var(--primary); color:white;' : (array_search($step, ['Draft','Dikirim','Disetujui','Selesai']) <= array_search($purchaseOrder->status, ['Draft','Dikirim','Disetujui','Selesai']) ? 'background:var(--success); color:white;' : 'background:#e2e8f0; color:var(--text-light);') }}">
                            {{ $idx + 1 }}
                        </div>
                        <span style="font-size:0.85rem; font-weight:{{ $purchaseOrder->status === $step ? '700' : '400' }}; color:{{ $purchaseOrder->status === $step ? 'var(--primary)' : 'var(--text-secondary)' }};">
                            {{ $step }}
                        </span>
                    </div>
                    @endforeach
                </div>

                @if(count($allowedTransitions) > 0)
                <div style="display:flex; flex-direction:column; gap:0.5rem;">
                    @foreach($allowedTransitions as $transition)
                    <form method="POST" action="{{ route('purchase-orders.update-status', $purchaseOrder) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="{{ $transition }}">
                        <button type="submit" class="btn {{ $transition === 'Selesai' ? 'btn-success' : 'btn-primary' }} btn-sm" style="width:100%;"
                            onclick="return confirm('Ubah status ke {{ $transition }}?{{ $transition === 'Selesai' ? ' Stok akan bertambah dan Invoice otomatis dibuat.' : '' }}')">
                            → {{ $transition }}
                            @if($transition === 'Selesai')
                            <span style="font-size:0.7rem; opacity:0.8;">(+Stok +Invoice)</span>
                            @endif
                        </button>
                    </form>
                    @endforeach
                </div>
                @else
                <div style="text-align:center; color:var(--success); font-weight:600; font-size:0.85rem;">
                    ✅ PO telah selesai
                </div>
                @endif

                @if($purchaseOrder->status === 'Draft')
                <div style="margin-top:1rem; padding-top:1rem; border-top:1px solid var(--border);">
                    <form method="POST" action="{{ route('purchase-orders.destroy', $purchaseOrder) }}" onsubmit="return confirm('Yakin ingin menghapus PO ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" style="width:100%;">Hapus PO</button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
