<div class="form-group">
    <label class="form-label">Nama Supplier *</label>
    <input type="text" name="nama" class="form-input" value="{{ old('nama', $supplier->nama ?? '') }}" required>
    @error('nama') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="grid-cols-2">
    <div class="form-group">
        <label class="form-label">PIC (Person in Charge)</label>
        <input type="text" name="pic" class="form-input" value="{{ old('pic', $supplier->pic ?? '') }}">
        @error('pic') <div class="form-error">{{ $message }}</div> @enderror
    </div>
    <div class="form-group">
        <label class="form-label">Telepon</label>
        <input type="text" name="telepon" class="form-input" value="{{ old('telepon', $supplier->telepon ?? '') }}">
        @error('telepon') <div class="form-error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="form-group">
    <label class="form-label">NPWP</label>
    <input type="text" name="npwp" class="form-input" value="{{ old('npwp', $supplier->npwp ?? '') }}">
    @error('npwp') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Alamat</label>
    <textarea name="alamat" class="form-input" rows="3">{{ old('alamat', $supplier->alamat ?? '') }}</textarea>
    @error('alamat') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer;">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $supplier->is_active ?? true) ? 'checked' : '' }}>
        <span class="form-label" style="margin-bottom:0;">Supplier Aktif</span>
    </label>
</div>
