@extends('layouts.app')

@section('title', 'Detail Produksi MBG')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Produksi MBG
        </h2>
        <a href="{{ route('produksi.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">&larr; Kembali ke Daftar</a>
    </div>    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-bold border-b pb-2 mb-4">Informasi Produksi</h3>
                            <table class="w-full text-sm">
                                <tr>
                                    <td class="py-2 text-gray-500 w-1/3">Tanggal Produksi</td>
                                    <td class="py-2 font-medium">{{ $produksi->tanggal->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2 text-gray-500">Nama Menu</td>
                                    <td class="py-2 font-medium text-lg text-indigo-600">{{ $produksi->menu_nama }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2 text-gray-500">Jumlah Porsi</td>
                                    <td class="py-2 font-medium">{{ number_format($produksi->jumlah_porsi) }} Porsi</td>
                                </tr>
                                <tr>
                                    <td class="py-2 text-gray-500">Dibuat Oleh</td>
                                    <td class="py-2 font-medium">{{ $produksi->createdBy->username ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2 text-gray-500">Catatan Khusus</td>
                                    <td class="py-2">{{ $produksi->catatan ?: '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <h3 class="text-lg font-bold border-b pb-2 mb-4 mt-8">Bahan Baku yang Digunakan (Stock Out)</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bahan Baku</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Digunakan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($produksi->items as $i => $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $i + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ $item->bahanBaku->nama_bahan }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->bahanBaku->kategori }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                        {{ number_format($item->qty_digunakan, 2) }} {{ $item->bahanBaku->satuan }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">Tidak ada data bahan baku</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
