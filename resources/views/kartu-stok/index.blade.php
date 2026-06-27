@extends('layouts.app')

@section('title', 'Kartu Stok (Audit)')
@section('subtitle', 'Pilih bahan baku untuk melihat riwayat mutasi stok')

@section('content')
<div class="data-table-wrapper">
    <div class="data-table-header">
        <h3>Pilih Bahan Baku</h3>
        <form method="GET" action="{{ route('kartu-stok.index') }}">
            <div class="search-bar">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                <input type="text" name="search" placeholder="Cari bahan baku..." value="{{ request('search') }}">
            </div>
        </form>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Bahan Baku</th>
                <th>Kategori</th>
                <th>Satuan</th>
                <th>Stok Saat Ini</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bahanBaku as $i => $bahan)
            <tr>
                <td>{{ $bahanBaku->firstItem() + $i }}</td>
                <td style="font-weight:600;">{{ $bahan->nama_bahan }}</td>
                <td><span class="badge badge-draft">{{ $bahan->kategori }}</span></td>
                <td>{{ $bahan->satuan }}</td>
                <td style="font-weight:700;">{{ number_format($bahan->stokGudang?->qty_aktual ?? 0, 2) }}</td>
                <td>
                    <a href="{{ route('kartu-stok.show', $bahan->id) }}" class="btn btn-outline btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Lihat Kartu Stok
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center; color:var(--text-light); padding:2rem;">
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
