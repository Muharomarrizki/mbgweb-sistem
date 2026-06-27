@extends('layouts.app')

@section('title', 'Distribusi MBG ke Sekolah')

@section('content')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
        Distribusi MBG ke Sekolah
    </h2>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Form Input Distribusi -->
                @if(auth()->user()->hasRole('kepala_dapur'))
                <div class="md:col-span-1">
                    <div class="bg-white shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-bold mb-4 border-b pb-2">Catat Distribusi Baru</h3>
                        <form action="{{ route('distribusi.store') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Pilih Produksi (Menu)</label>
                                <select name="produksi_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    <option value="">Pilih Produksi...</option>
                                    @foreach($produksis as $prod)
                                        <option value="{{ $prod->id }}">
                                            {{ $prod->tanggal->format('d/m/Y') }} - {{ $prod->menu_nama }} (Sisa: {{ $prod->jumlah_porsi - $prod->distribusi->sum('jumlah_porsi') }} porsi)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Sekolah Tujuan</label>
                                <input type="text" name="sekolah_tujuan" required placeholder="Contoh: SDN Sukasejati 01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Jumlah Porsi</label>
                                    <input type="number" name="jumlah_porsi" required min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Jam Kirim</label>
                                    <input type="time" name="jam_kirim" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Nama Petugas Pengirim</label>
                                <input type="text" name="petugas_nama" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Catatan Khusus</label>
                                <textarea name="catatan" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"></textarea>
                            </div>

                            <button type="submit" class="w-full btn btn-primary justify-center mt-2">
                                Simpan Distribusi
                            </button>
                        </form>
                    </div>
                </div>
                @endif

                <!-- Tabel Riwayat Distribusi -->
                <div class="{{ auth()->user()->hasRole('kepala_dapur') ? 'md:col-span-2' : 'md:col-span-3' }}">
                    <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-bold">Riwayat Pengiriman / Distribusi</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tujuan</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Menu</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Porsi</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Petugas</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($distribusis as $dist)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $dist->created_at->format('d M Y') }}<br>
                                            <span class="text-xs text-indigo-600 font-medium">{{ $dist->jam_kirim ? \Carbon\Carbon::parse($dist->jam_kirim)->format('H:i') : '-' }} WIB</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ $dist->sekolah_tujuan }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $dist->produksi->menu_nama }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-bold">{{ number_format($dist->jumlah_porsi) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $dist->petugas_nama ?: '-' }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada data distribusi MBG.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
