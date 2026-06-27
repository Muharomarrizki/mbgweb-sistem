@extends('layouts.app')

@section('title', 'Data Bahan Baku')
@section('subtitle', 'Kelola data bahan baku')

@section('content')
<div class="data-table-wrapper">
    <div class="data-table-header">
        <h3>Daftar Bahan Baku</h3>
        <div style="display:flex; gap:0.75rem; align-items:center; flex-wrap:wrap;">
            <form method="GET" action="{{ route('bahan-baku.index') }}" style="display:flex; gap:0.5rem;">
                <div class="search-bar">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <input type="text" name="search" placeholder="Cari bahan baku..." value="{{ request('search') }}">
                </div>
                <select name="kategori" class="form-select" style="width:auto; min-width:140px;" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach(['Beras','Ayam','Daging','Ikan','Sayur','Bumbu','Minyak','Telur','Susu','Lainnya'] as $kat)
                    <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                    @endforeach
                </select>
            </form>
            <a href="{{ route('bahan-baku.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Bahan Baku
            </a>
        </div>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Bahan</th>
                <th>Kategori</th>
                <th>Satuan</th>
                <th>Harga Terakhir</th>
                <th>Stok Min.</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bahanBaku as $i => $bahan)
            <tr>
                <td>{{ $bahanBaku->firstItem() + $i }}</td>
                <td style="font-weight:600;">{{ $bahan->nama_bahan }}</td>
                <td><span class="badge badge-dikirim">{{ $bahan->kategori }}</span></td>
                <td>{{ $bahan->satuan }}</td>
                <td>Rp {{ number_format($bahan->harga_terakhir, 0, ',', '.') }}</td>
                <td>{{ $bahan->stok_minimum }} {{ $bahan->satuan }}</td>
                <td>
                    <span class="badge {{ $bahan->is_active ? 'badge-selesai' : 'badge-draft' }}">
                        {{ $bahan->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td>
                    <div style="display:flex; gap:0.4rem;">
                        <a href="{{ route('bahan-baku.edit', $bahan) }}" class="btn btn-outline btn-sm">Edit</a>
                        <form method="POST" action="{{ route('bahan-baku.destroy', $bahan) }}" onsubmit="return confirm('Yakin ingin menghapus bahan baku ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center; color:var(--text-light); padding:2rem;">
                    Belum ada data bahan baku.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($bahanBaku->hasPages())
    <div class="pagination-wrapper">
        {{ $bahanBaku->links() }}
    </div>
    @endif
</div>
@endsection
