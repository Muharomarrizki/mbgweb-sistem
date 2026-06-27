@extends('layouts.app')

@section('title', 'Buat Purchase Order')
@section('subtitle', 'Buat PO baru ke supplier')

@section('content')
<div class="data-table-wrapper">
    <div class="data-table-header">
        <h3>Form Purchase Order Baru</h3>
        <a href="{{ route('purchase-orders.index') }}" class="btn btn-outline btn-sm">← Kembali</a>
    </div>
    <div style="padding:1.5rem;">
        <form method="POST" action="{{ route('purchase-orders.store') }}" id="poForm">
            @csrf

            <div class="grid-cols-3">
                <div class="form-group">
                    <label class="form-label">No. PO</label>
                    <input type="text" class="form-input" value="{{ $noPo }}" disabled style="background:#f8fafc; font-weight:700; color:var(--primary);">
                </div>
                <div class="form-group">
                    <label class="form-label">Supplier *</label>
                    <select name="supplier_id" class="form-select" required>
                        <option value="">Pilih Supplier</option>
                        @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->nama }}</option>
                        @endforeach
                    </select>
                    @error('supplier_id') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal *</label>
                    <input type="date" name="tanggal" class="form-input" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                    @error('tanggal') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Catatan</label>
                <textarea name="catatan" class="form-input" rows="2" placeholder="Catatan opsional...">{{ old('catatan') }}</textarea>
            </div>

            <!-- Items -->
            <div style="margin-top:1.5rem;">
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem;">
                    <h3 style="font-size:1rem; font-weight:700;">Item PO</h3>
                    <button type="button" class="btn btn-success btn-sm" onclick="addItem()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Tambah Item
                    </button>
                </div>

                <table class="data-table" id="itemsTable">
                    <thead>
                        <tr>
                            <th style="width:35%;">Bahan Baku</th>
                            <th style="width:15%;">Qty</th>
                            <th style="width:20%;">Harga Satuan (Rp)</th>
                            <th style="width:20%;">Subtotal</th>
                            <th style="width:10%;"></th>
                        </tr>
                    </thead>
                    <tbody id="itemsBody">
                        <tr class="item-row" data-index="0">
                            <td>
                                <select name="items[0][bahan_baku_id]" class="form-select" required onchange="updateHarga(this, 0)">
                                    <option value="">Pilih Bahan</option>
                                    @foreach($bahanBaku as $bahan)
                                    <option value="{{ $bahan->id }}" data-harga="{{ $bahan->harga_terakhir }}" data-satuan="{{ $bahan->satuan }}">{{ $bahan->nama_bahan }} ({{ $bahan->satuan }})</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" name="items[0][qty]" class="form-input" min="0.01" step="0.01" required oninput="calcSubtotal(0)"></td>
                            <td><input type="number" name="items[0][harga_satuan]" class="form-input" min="0" step="0.01" required oninput="calcSubtotal(0)"></td>
                            <td><span class="subtotal" id="subtotal-0" style="font-weight:600;">Rp 0</span></td>
                            <td><button type="button" class="btn btn-danger btn-sm" onclick="removeItem(this)">✕</button></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" style="text-align:right; font-weight:700;">Total:</td>
                            <td colspan="2" style="font-weight:800; font-size:1.1rem; color:var(--primary);" id="grandTotal">Rp 0</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div style="display:flex; gap:0.75rem; justify-content:flex-end; margin-top:1.5rem; padding-top:1rem; border-top:1px solid var(--border);">
                <a href="{{ route('purchase-orders.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan PO (Draft)</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let itemIndex = 1;
    const bahanBakuOptions = `@foreach($bahanBaku as $bahan)<option value="{{ $bahan->id }}" data-harga="{{ $bahan->harga_terakhir }}" data-satuan="{{ $bahan->satuan }}">{{ $bahan->nama_bahan }} ({{ $bahan->satuan }})</option>@endforeach`;

    function addItem() {
        const row = document.createElement('tr');
        row.className = 'item-row';
        row.dataset.index = itemIndex;
        row.innerHTML = `
            <td>
                <select name="items[${itemIndex}][bahan_baku_id]" class="form-select" required onchange="updateHarga(this, ${itemIndex})">
                    <option value="">Pilih Bahan</option>
                    ${bahanBakuOptions}
                </select>
            </td>
            <td><input type="number" name="items[${itemIndex}][qty]" class="form-input" min="0.01" step="0.01" required oninput="calcSubtotal(${itemIndex})"></td>
            <td><input type="number" name="items[${itemIndex}][harga_satuan]" class="form-input" min="0" step="0.01" required oninput="calcSubtotal(${itemIndex})"></td>
            <td><span class="subtotal" id="subtotal-${itemIndex}" style="font-weight:600;">Rp 0</span></td>
            <td><button type="button" class="btn btn-danger btn-sm" onclick="removeItem(this)">✕</button></td>
        `;
        document.getElementById('itemsBody').appendChild(row);
        itemIndex++;
    }

    function removeItem(btn) {
        const rows = document.querySelectorAll('.item-row');
        if (rows.length <= 1) {
            alert('Minimal harus ada 1 item.');
            return;
        }
        btn.closest('tr').remove();
        calcGrandTotal();
    }

    function updateHarga(select, index) {
        const option = select.options[select.selectedIndex];
        const harga = option.dataset.harga || 0;
        const row = select.closest('tr');
        row.querySelector(`[name="items[${index}][harga_satuan]"]`).value = harga;
        calcSubtotal(index);
    }

    function calcSubtotal(index) {
        const row = document.querySelector(`tr[data-index="${index}"]`);
        if (!row) return;
        const qty = parseFloat(row.querySelector(`[name="items[${index}][qty]"]`)?.value) || 0;
        const harga = parseFloat(row.querySelector(`[name="items[${index}][harga_satuan]"]`)?.value) || 0;
        const subtotal = qty * harga;
        const el = document.getElementById(`subtotal-${index}`);
        if (el) el.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
        calcGrandTotal();
    }

    function calcGrandTotal() {
        let total = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const idx = row.dataset.index;
            const qty = parseFloat(row.querySelector(`[name="items[${idx}][qty]"]`)?.value) || 0;
            const harga = parseFloat(row.querySelector(`[name="items[${idx}][harga_satuan]"]`)?.value) || 0;
            total += qty * harga;
        });
        document.getElementById('grandTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
    }
</script>
@endpush
@endsection
