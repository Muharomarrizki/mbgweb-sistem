@extends('layouts.app')

@section('title', 'Detail Invoice')
@section('subtitle', $invoice->no_invoice)

@section('content')
<div class="grid-cols-3" style="gap:1.5rem; margin-bottom:1.5rem;">
    <!-- Info Supplier -->
    <div class="stat-card" style="grid-column: span 1;">
        <div class="stat-card-label" style="margin-bottom:0.75rem;">Kepada (Supplier)</div>
        <div style="font-size:1.1rem; font-weight:700; color:var(--text-primary);">{{ $invoice->purchaseOrder->supplier->nama }}</div>
        <div style="font-size:0.85rem; color:var(--text-secondary); margin-top:0.25rem;">
            {{ $invoice->purchaseOrder->supplier->alamat }}<br>
            Telp: {{ $invoice->purchaseOrder->supplier->telepon }}<br>
            NPWP: {{ $invoice->purchaseOrder->supplier->npwp }}
        </div>
    </div>

    <!-- Info Invoice -->
    <div class="stat-card" style="grid-column: span 2;">
        <div style="display:flex; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
            <div>
                <div class="stat-card-label">Status Pembayaran</div>
                @php
                    $badgeClass = match($invoice->status_bayar) {
                        'Lunas' => 'badge-selesai',
                        'Sebagian' => 'badge-sebagian',
                        'Belum Dibayar' => 'badge-belum',
                        default => 'badge-draft',
                    };
                @endphp
                <div style="margin-top:0.5rem;"><span class="badge {{ $badgeClass }}" style="font-size:0.85rem; padding:0.4rem 0.8rem;">{{ $invoice->status_bayar }}</span></div>
            </div>
            <div>
                <div class="stat-card-label">Referensi PO</div>
                <div style="font-weight:600; font-size:1rem; margin-top:0.25rem;">
                    <a href="{{ route('purchase-orders.show', $invoice->po_id) }}" style="color:var(--primary); text-decoration:none;">{{ $invoice->purchaseOrder->no_po }}</a>
                </div>
            </div>
            <div>
                <div class="stat-card-label">Tanggal Terbit</div>
                <div style="font-weight:600; font-size:1rem; margin-top:0.25rem;">{{ $invoice->tanggal_issued->format('d M Y') }}</div>
            </div>
            <div>
                <div class="stat-card-label">Jatuh Tempo</div>
                <div style="font-weight:600; font-size:1rem; margin-top:0.25rem; {{ $invoice->status_bayar !== 'Lunas' && $invoice->jatuh_tempo < now() ? 'color:var(--danger);' : '' }}">
                    {{ $invoice->jatuh_tempo->format('d M Y') }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="data-table-wrapper" style="margin-bottom:1.5rem;">
    <div class="data-table-header">
        <h3>Rincian Tagihan</h3>
        <div style="display:flex; gap:0.5rem;">
            @if(auth()->user()->hasRole('bendahara') && $invoice->status_bayar !== 'Lunas')
            <button class="btn btn-success" onclick="document.getElementById('modalBayar').classList.add('show')">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                </svg>
                Update Pembayaran
            </button>
            @endif
            <a href="{{ route('invoices.pdf', $invoice->id) }}" class="btn btn-outline" target="_blank">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                Unduh PDF
            </a>
            <a href="{{ route('invoices.index') }}" class="btn btn-outline">Kembali</a>
        </div>
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Bahan Baku</th>
                <th style="text-align:center;">Qty</th>
                <th style="text-align:right;">Harga Satuan</th>
                <th style="text-align:right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->purchaseOrder->items as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td style="font-weight:600;">{{ $item->bahanBaku->nama_bahan }}</td>
                <td style="text-align:center;">{{ number_format($item->qty, 2) }} {{ $item->bahanBaku->satuan }}</td>
                <td style="text-align:right;">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                <td style="text-align:right; font-weight:600;">Rp {{ number_format($item->qty * $item->harga_satuan, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot style="background:#f8fafc;">
            <tr>
                <td colspan="4" style="text-align:right; font-weight:600; padding:1rem 1.5rem;">Total Tagihan:</td>
                <td style="text-align:right; font-weight:800; font-size:1.1rem; color:var(--text-primary); padding:1rem 1.5rem;">
                    Rp {{ number_format($invoice->total_tagihan, 0, ',', '.') }}
                </td>
            </tr>
            @if($invoice->jumlah_dibayar > 0)
            <tr>
                <td colspan="4" style="text-align:right; font-weight:600; padding:0.5rem 1.5rem; color:var(--success);">Sudah Dibayar:</td>
                <td style="text-align:right; font-weight:700; color:var(--success); padding:0.5rem 1.5rem;">
                    - Rp {{ number_format($invoice->jumlah_dibayar, 0, ',', '.') }}
                </td>
            </tr>
            @endif
            @if($invoice->status_bayar !== 'Lunas')
            <tr>
                <td colspan="4" style="text-align:right; font-weight:700; padding:1rem 1.5rem; color:var(--danger);">Sisa Tagihan:</td>
                <td style="text-align:right; font-weight:800; font-size:1.15rem; color:var(--danger); padding:1rem 1.5rem;">
                    Rp {{ number_format($invoice->total_tagihan - $invoice->jumlah_dibayar, 0, ',', '.') }}
                </td>
            </tr>
            @endif
        </tfoot>
    </table>
    
    @if($invoice->catatan)
    <div style="padding:1.5rem; border-top:1px solid var(--border); background:#fffbeb;">
        <div style="font-weight:700; font-size:0.85rem; color:var(--warning); margin-bottom:0.25rem;">Catatan Pembayaran:</div>
        <p style="font-size:0.85rem; color:var(--text-secondary); margin:0;">{{ $invoice->catatan }}</p>
    </div>
    @endif
</div>

<!-- Modal Pembayaran -->
@if(auth()->user()->hasRole('bendahara') && $invoice->status_bayar !== 'Lunas')
<div class="modal-overlay" id="modalBayar">
    <div class="modal">
        <h3>Update Pembayaran</h3>
        <p>Silakan input total akumulasi nominal yang sudah dibayarkan ke supplier.</p>
        
        <form method="POST" action="{{ route('invoices.update-payment', $invoice->id) }}">
            @csrf
            @method('PATCH')
            
            <div class="form-group">
                <label class="form-label">Total Tagihan</label>
                <input type="text" class="form-input" value="Rp {{ number_format($invoice->total_tagihan, 0, ',', '.') }}" disabled style="background:#f1f5f9; font-weight:700;">
            </div>

            <div class="form-group">
                <label class="form-label">Nominal Dibayar (Akumulasi) *</label>
                <div style="position:relative;">
                    <span style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); font-weight:600; color:var(--text-secondary);">Rp</span>
                    <input type="number" name="jumlah_dibayar" class="form-input" style="padding-left:2.5rem;" min="0" max="{{ $invoice->total_tagihan }}" value="{{ old('jumlah_dibayar', $invoice->jumlah_dibayar) }}" required>
                </div>
                <div style="font-size:0.75rem; color:var(--text-light); margin-top:0.25rem;">Masukkan total akumulasi pembayaran. Sisa tagihan akan dihitung otomatis.</div>
                @error('jumlah_dibayar') <div class="form-error">{{ $message }}</div> @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">Catatan Pembayaran (Opsional)</label>
                <textarea name="catatan" class="form-input" rows="2" placeholder="Cth: Transfer Bank BCA ke Rek. Supplier">{{ old('catatan', $invoice->catatan) }}</textarea>
                @error('catatan') <div class="form-error">{{ $message }}</div> @enderror
            </div>
            
            <div class="modal-actions">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('modalBayar').classList.remove('show')">Batal</button>
                <button type="submit" class="btn btn-success">Simpan Pembayaran</button>
            </div>
        </form>
    </div>
</div>

@if($errors->any())
@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById('modalBayar').classList.add('show');
    });
</script>
@endpush
@endif
@endif

@endsection
