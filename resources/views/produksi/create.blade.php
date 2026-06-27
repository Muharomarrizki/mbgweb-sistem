@extends('layouts.app')

@section('title', 'Catat Produksi MBG')

@section('content')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
        Catat Produksi MBG (Stock Out)
    </h2>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <strong>Terjadi Kesalahan!</strong>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('produksi.store') }}" method="POST">
                        @csrf
                        
                        <!-- Informasi Produksi -->
                        <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Informasi Produksi</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Produksi</label>
                                <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Menu</label>
                                <input type="text" name="menu_nama" value="{{ old('menu_nama') }}" required placeholder="Contoh: Nasi Ayam Teriyaki"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jumlah Porsi</label>
                                <input type="number" name="jumlah_porsi" value="{{ old('jumlah_porsi') }}" required min="1" placeholder="3500"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>

                        <!-- Bahan Baku (Stock Out) -->
                        <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Penggunaan Bahan Baku (Keluar Gudang)</h3>
                        <div class="mb-4 text-sm text-gray-500 bg-blue-50 p-3 rounded-md border border-blue-100">
                            <strong>Perhatian:</strong> Stok gudang akan otomatis dipotong sesuai dengan jumlah bahan baku yang Anda masukkan di bawah ini. Pastikan jumlahnya akurat sesuai standar porsi masakan.
                        </div>

                        <div class="overflow-x-auto mb-4">
                            <table class="min-w-full divide-y divide-gray-200" id="items_table">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bahan Baku (Sisa Stok)</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty Digunakan</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-20">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" id="items_body">
                                    <!-- Dynamic rows will be inserted here -->
                                </tbody>
                            </table>
                        </div>

                        <button type="button" onclick="addItemRow()" class="btn btn-outline mb-6">
                            + Tambah Bahan Baku
                        </button>

                        <div class="form-group">
                            <label class="form-label">Catatan Khusus (Opsional)</label>
                            <textarea name="catatan" rows="3" class="form-input">{{ old('catatan') }}</textarea>
                        </div>

                        <div class="mt-8 flex justify-end gap-3">
                            <a href="{{ route('produksi.index') }}" class="btn btn-outline">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan & Potong Stok</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    @php
        $mappedBahan = $bahanBakus->map(function($b) {
            return [
                'id' => $b->id,
                'nama_bahan' => $b->nama_bahan,
                'satuan' => $b->satuan,
                'stok' => $b->stokGudang ? $b->stokGudang->qty_aktual : 0
            ];
        });
    @endphp
    <!-- Template for JS -->
    <script>
        const bahanBakus = @json($mappedBahan);

        let rowIdx = 0;

        function addItemRow() {
            let options = '<option value="">Pilih Bahan Baku...</option>';
            bahanBakus.forEach(b => {
                options += `<option value="${b.id}" data-satuan="${b.satuan}" data-stok="${b.stok}">${b.nama_bahan} (Stok: ${b.stok} ${b.satuan})</option>`;
            });

            const tr = document.createElement('tr');
            tr.id = `row_${rowIdx}`;
            
            tr.innerHTML = `
                <td class="px-4 py-3">
                    <select name="items[${rowIdx}][bahan_baku_id]" onchange="updateSatuan(${rowIdx})" required
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        ${options}
                    </select>
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center">
                        <input type="number" step="0.01" min="0.01" name="items[${rowIdx}][qty_digunakan]" required
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <span id="satuan_${rowIdx}" class="ml-2 text-sm text-gray-500 w-12">-</span>
                    </div>
                </td>
                <td class="px-4 py-3">
                    <button type="button" onclick="removeRow(${rowIdx})" class="text-red-600 hover:text-red-900 font-medium text-sm">Hapus</button>
                </td>
            `;
            
            document.getElementById('items_body').appendChild(tr);
            rowIdx++;
        }

        function removeRow(idx) {
            document.getElementById(`row_${idx}`).remove();
        }

        function updateSatuan(idx) {
            const select = document.querySelector(`select[name="items[${idx}][bahan_baku_id]"]`);
            const option = select.options[select.selectedIndex];
            const satuan = option.dataset.satuan || '-';
            document.getElementById(`satuan_${idx}`).innerText = satuan;
        }

        // Add 1 default row on load
        document.addEventListener('DOMContentLoaded', function() {
            addItemRow();
        });
    </script>
@endsection
