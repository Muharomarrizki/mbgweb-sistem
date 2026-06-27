@extends('layouts.app')

@section('title', 'Data Supplier')
@section('subtitle', 'Kelola data supplier')

@section('content')
<div class="data-table-wrapper">
    <div class="data-table-header">
        <h3>Daftar Supplier</h3>
        <div style="display:flex; gap:0.75rem; align-items:center; flex-wrap:wrap;">
            <form method="GET" action="{{ route('suppliers.index') }}">
                <div class="search-bar">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <input type="text" name="search" placeholder="Cari supplier..." value="{{ request('search') }}">
                </div>
            </form>
            <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Supplier
            </a>
        </div>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Supplier</th>
                <th>PIC</th>
                <th>Telepon</th>
                <th>NPWP</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($suppliers as $i => $supplier)
            <tr>
                <td>{{ $suppliers->firstItem() + $i }}</td>
                <td>
                    <div style="font-weight:600;">{{ $supplier->nama }}</div>
                    <div style="font-size:0.75rem; color:var(--text-light);">{{ Str::limit($supplier->alamat, 40) }}</div>
                </td>
                <td>{{ $supplier->pic ?? '-' }}</td>
                <td>{{ $supplier->telepon ?? '-' }}</td>
                <td style="font-size:0.78rem;">{{ $supplier->npwp ?? '-' }}</td>
                <td>
                    <span class="badge {{ $supplier->is_active ? 'badge-selesai' : 'badge-draft' }}">
                        {{ $supplier->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td>
                    <div style="display:flex; gap:0.4rem;">
                        <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-outline btn-sm">Edit</a>
                        <form method="POST" action="{{ route('suppliers.destroy', $supplier) }}" onsubmit="return confirm('Yakin ingin menghapus supplier ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center; color:var(--text-light); padding:2rem;">
                    Belum ada data supplier.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($suppliers->hasPages())
    <div class="pagination-wrapper">
        {{ $suppliers->links() }}
    </div>
    @endif
</div>
@endsection
