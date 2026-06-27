@extends('layouts.app')

@section('title', 'Stok Gudang')
@section('subtitle', 'Pantau ketersediaan stok bahan baku')

@section('content')
<div class="data-table-wrapper">
    <div class="data-table-header">
        <h3>Daftar Stok Gudang</h3>
        <div style="display:flex; gap:0.75rem; align-items:center; flex-wrap:wrap;">
            <form method="GET" action="{{ route('stok-gudang.index') }}">
                <div class="search-bar">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <input type="text" name="search" placeholder="Cari bahan baku..." value="{{ request('search') }}">
                </div>
            </form>
            @if(auth()->user()->hasRole('admin_gudang'))
            <button class="btn btn-primary" onclick="document.getElementById('modalPenyesuaian').classList.add('show')">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                Penyesuaian Manual
            </button>
            @endif
        </div>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Bahan Baku</th>
                <th>Kategori</th>
                <th>Stok Aktual</th>
                <th>Batas Minimum</th>
                <th>Status</th>
                <th>Kartu Stok</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stokGudang as $i => $stok)
            @php
                $qty = $stok->qty_aktual;
                $min = $stok->bahanBaku->stok_minimum;
                $status = 'Aman';
                $badge = 'badge-selesai';
                if ($qty <= 0) {
                    $status = 'Habis';
                    $badge = 'badge-belum';
                } elseif ($qty <= $min) {
                    $status = 'Rendah';
                    $badge = 'badge-sebagian';
                }
            @endphp
            <tr>
                <td>{{ $stokGudang->firstItem() + $i }}</td>
                <td style="font-weight:600;">{{ $stok->bahanBaku->nama_bahan }}</td>
                <td><span class="badge badge-draft">{{ $stok->bahanBaku->kategori }}</span></td>
                <td style="font-weight:700; font-size:1rem;">{{ number_format($qty, 2) }} <span style="font-size:0.75rem; font-weight:400; color:var(--text-light);">{{ $stok->bahanBaku->satuan }}</span></td>
                <td>{{ number_format($min, 2) }} {{ $stok->bahanBaku->satuan }}</td>
                <td><span class="badge {{ $badge }}">{{ $status }}</span></td>
                <td>
                    <a href="{{ route('kartu-stok.show', $stok->bahanBaku->id) }}" class="btn btn-outline btn-sm">Lihat Riwayat</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center; color:var(--text-light); padding:2rem;">
                    Belum ada data stok gudang. Stok otomatis dibuat saat PO selesai.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($stokGudang->hasPages())
    <div class="pagination-wrapper">
        {{ $stokGudang->links() }}
    </div>
    @endif
</div>

<!-- Modal Penyesuaian Stok -->
@if(auth()->user()->hasRole('admin_gudang'))
<div class="modal-overlay" id="modalPenyesuaian">
    <div class="modal">
        <h3>Penyesuaian Stok Manual</h3>
        <p>Gunakan untuk mencatat barang rusak, hilang, opname, atau bonus.</p>
        
        <form method="POST" action="{{ route('stok-gudang.penyesuaian') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Bahan Baku *</label>
                <select name="bahan_baku_id" class="form-select" required>
                    <option value="">Pilih Bahan Baku</option>
                    @foreach($bahanBaku as $bahan)
                    <option value="{{ $bahan->id }}">{{ $bahan->nama_bahan }}</option>
                    @endforeach
                </select>
                @error('bahan_baku_id') <div class="form-error">{{ $message }}</div> @enderror
            </div>
            
            <div class="grid-cols-2" style="gap:1rem;">
                <div class="form-group">
                    <label class="form-label">Jenis *</label>
                    <select name="jenis_penyesuaian" class="form-select" required>
                        <option value="Masuk">Masuk (+)</option>
                        <option value="Keluar">Keluar (-)</option>
                    </select>
                    @error('jenis_penyesuaian') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Kuantitas *</label>
                    <input type="number" name="qty" class="form-input" min="0.01" step="0.01" required>
                    @error('qty') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Keterangan / Alasan *</label>
                <textarea name="keterangan" class="form-input" rows="2" placeholder="Misal: Barang kedaluwarsa 2 Kg" required></textarea>
                @error('keterangan') <div class="form-error">{{ $message }}</div> @enderror
            </div>
            
            <div class="modal-actions">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('modalPenyesuaian').classList.remove('show')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Penyesuaian</button>
            </div>
        </form>
    </div>
</div>

@if($errors->any())
@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById('modalPenyesuaian').classList.add('show');
    });
</script>
@endpush
@endif
@endif

@endsection
