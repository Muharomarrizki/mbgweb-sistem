@extends('layouts.app')

@section('title', 'Edit PO #' . $purchaseOrder->no_po)
@section('subtitle', 'Edit Purchase Order')

@section('content')
<div class="data-table-wrapper">
    <div class="data-table-header">
        <h3>Edit PO: {{ $purchaseOrder->no_po }}</h3>
        <a href="{{ route('purchase-orders.show', $purchaseOrder) }}" class="btn btn-outline btn-sm">← Kembali</a>
    </div>
    <div style="padding:1.5rem;">
        <form method="POST" action="{{ route('purchase-orders.update', $purchaseOrder) }}">
            @csrf
            @method('PUT')

            <div class="grid-cols-3">
                <div class="form-group">
                    <label class="form-label">No. PO</label>
                    <input type="text" class="form-input" value="{{ $purchaseOrder->no_po }}" disabled style="background:#f8fafc; font-weight:700; color:var(--primary);">
                </div>
                <div class="form-group">
                    <label class="form-label">Supplier *</label>
                    <select name="supplier_id" class="form-select" required>
                        @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ $purchaseOrder->supplier_id == $supplier->id ? 'selected' : '' }}>{{ $supplier->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal *</label>
                    <input type="date" name="tanggal" class="form-input" value="{{ $purchaseOrder->tanggal->format('Y-m-d') }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Catatan</label>
                <textarea name="catatan" class="form-input" rows="2">{{ $purchaseOrder->catatan }}</textarea>
            </div>

            <div style="margin-top:1.5rem;">
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem;">
                    <h3 style="font-size:1rem; font-weight:700;">Item PO</h3>
                    <button type="button" class="btn btn-success btn-sm" onclick="addItem()">+ Tambah Item</button>
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
                        @foreach($purchaseOrder->items as $i => $item)
                        <tr class="item-row" data-index="{{ $i }}">
                            <td>
                                <select name="items[{{ $i }}][bahan_baku_id]" class="form-select" required>
                                    @foreach($bahanBaku as $bahan)
                                    <option value="{{ $bahan->id }}" {{ $item->bahan_baku_id == $bahan->id ? 'selected' : '' }}>{{ $bahan->nama_bahan }} ({{ $bahan->satuan }})</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" name="items[{{ $i }}][qty]" class="form-input" value="{{ $item->qty }}" min="0.01" step="0.01" required oninput="calcSubtotal({{ $i }})"></td>
                            <td><input type="number" name="items[{{ $i }}][harga_satuan]" class="form-input" value="{{ $item->harga_satuan }}" min="0" step="0.01" required oninput="calcSubtotal({{ $i }})"></td>
                            <td><span class="subtotal" id="subtotal-{{ $i }}" style="font-weight:600;">Rp {{ number_format($item->qty * $item->harga_satuan, 0, ',', '.') }}</span></td>
                            <td><button type="button" class="btn btn-danger btn-sm" onclick="removeItem(this)">✕</button></td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" style="text-align:right; font-weight:700;">Total:</td>
                            <td colspan="2" style="font-weight:800; font-size:1.1rem; color:var(--primary);" id="grandTotal">Rp {{ number_format($purchaseOrder->total_harga, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div style="display:flex; gap:0.75rem; justify-content:flex-end; margin-top:1.5rem; padding-top:1rem; border-top:1px solid var(--border);">
                <a href="{{ route('purchase-orders.show', $purchaseOrder) }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let itemIndex = {{ count($purchaseOrder->items) }};
    const bahanBakuOptions = `@foreach($bahanBaku as $bahan)<option value="{{ $bahan->id }}">{{ $bahan->nama_bahan }} ({{ $bahan->satuan }})</option>@endforeach`;

    function addItem() {
        const row = document.createElement('tr');
        row.className = 'item-row';
        row.dataset.index = itemIndex;
        row.innerHTML = `
            <td><select name="items[${itemIndex}][bahan_baku_id]" class="form-select" required><option value="">Pilih</option>${bahanBakuOptions}</select></td>
            <td><input type="number" name="items[${itemIndex}][qty]" class="form-input" min="0.01" step="0.01" required oninput="calcSubtotal(${itemIndex})"></td>
            <td><input type="number" name="items[${itemIndex}][harga_satuan]" class="form-input" min="0" step="0.01" required oninput="calcSubtotal(${itemIndex})"></td>
            <td><span class="subtotal" id="subtotal-${itemIndex}" style="font-weight:600;">Rp 0</span></td>
            <td><button type="button" class="btn btn-danger btn-sm" onclick="removeItem(this)">✕</button></td>
        `;
        document.getElementById('itemsBody').appendChild(row);
        itemIndex++;
    }

    function removeItem(btn) {
        if (document.querySelectorAll('.item-row').length <= 1) return alert('Minimal 1 item.');
        btn.closest('tr').remove();
        calcGrandTotal();
    }

    function calcSubtotal(index) {
        const row = document.querySelector(`tr[data-index="${index}"]`);
        if (!row) return;
        const qty = parseFloat(row.querySelector(`[name="items[${index}][qty]"]`)?.value) || 0;
        const harga = parseFloat(row.querySelector(`[name="items[${index}][harga_satuan]"]`)?.value) || 0;
        document.getElementById(`subtotal-${index}`).textContent = 'Rp ' + (qty * harga).toLocaleString('id-ID');
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
