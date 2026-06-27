<div class="form-group">
    <label class="form-label">Nama Bahan Baku *</label>
    <input type="text" name="nama_bahan" class="form-input" value="{{ old('nama_bahan', $bahanBaku->nama_bahan ?? '') }}" required>
    @error('nama_bahan') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="grid-cols-2">
    <div class="form-group">
        <label class="form-label">Kategori *</label>
        <input type="text" name="kategori" list="kategori_list" class="form-input" value="{{ old('kategori', $bahanBaku->kategori ?? '') }}" required placeholder="Pilih atau ketik kategori baru...">
        <datalist id="kategori_list">
            @foreach(['Beras','Ayam','Daging','Ikan','Sayur','Bumbu','Minyak','Telur','Susu'] as $kat)
            <option value="{{ $kat }}">
            @endforeach
        </datalist>
        <div style="font-size:0.75rem; color:var(--text-light); margin-top:0.25rem;">Pilih dari daftar atau ketik kategori baru.</div>
        @error('kategori') <div class="form-error">{{ $message }}</div> @enderror
    </div>
    <div class="form-group">
        <label class="form-label">Satuan *</label>
        <input type="text" name="satuan" list="satuan_list" class="form-input" value="{{ old('satuan', $bahanBaku->satuan ?? '') }}" required placeholder="Pilih atau ketik satuan baru...">
        <datalist id="satuan_list">
            @foreach(['Kg','Gram','Liter','Ml','Butir','Pcs','Bungkus','Karton','Ikat','Papan','Keranjang','Jligen','Botol','Kaleng','Pack','Dus'] as $sat)
            <option value="{{ $sat }}">
            @endforeach
        </datalist>
        <div style="font-size:0.75rem; color:var(--text-light); margin-top:0.25rem;">Pilih dari daftar atau ketik satuan baru.</div>
        @error('satuan') <div class="form-error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="grid-cols-2">
    <div class="form-group">
        <label class="form-label">Harga Terakhir (Rp)</label>
        <input type="number" name="harga_terakhir" class="form-input" value="{{ old('harga_terakhir', $bahanBaku->harga_terakhir ?? 0) }}" min="0" step="0.01">
        @error('harga_terakhir') <div class="form-error">{{ $message }}</div> @enderror
    </div>
    <div class="form-group">
        <label class="form-label">Stok Minimum</label>
        <input type="number" name="stok_minimum" class="form-input" value="{{ old('stok_minimum', $bahanBaku->stok_minimum ?? 0) }}" min="0">
        @error('stok_minimum') <div class="form-error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="form-group">
    <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer;">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $bahanBaku->is_active ?? true) ? 'checked' : '' }}>
        <span class="form-label" style="margin-bottom:0;">Bahan Baku Aktif</span>
    </label>
</div>
