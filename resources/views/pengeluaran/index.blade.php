@extends('layouts.app')

@section('title', 'Pengeluaran Operasional (Opex)')

@section('content')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
        Pengeluaran Operasional (Luar Bahan Baku)
    </h2>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- Form Input -->
                @if(auth()->user()->hasRole('bendahara'))
                <div class="md:col-span-1">
                    <div class="bg-white shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-bold mb-4 border-b pb-2">Catat Pengeluaran Baru</h3>
                        <form action="{{ route('pengeluaran.store') }}" method="POST">
                            @csrf
                            <div class="form-group mb-4">
                                <label class="form-label">Tanggal</label>
                                <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required class="form-input text-sm">
                            </div>
                            
                            <div class="form-group mb-4">
                                <label class="form-label">Kategori</label>
                                <select name="kategori" required class="form-input text-sm">
                                    <option value="">Pilih Kategori...</option>
                                    <option value="Gaji Karyawan">Gaji Karyawan</option>
                                    <option value="Tagihan Gas & Listrik">Tagihan Gas & Listrik</option>
                                    <option value="Transportasi">Transportasi</option>
                                    <option value="Perlengkapan Kebersihan">Perlengkapan Kebersihan</option>
                                    <option value="Lain-lain">Lain-lain</option>
                                </select>
                            </div>

                            <div class="form-group mb-4">
                                <label class="form-label">Nominal (Rp)</label>
                                <input type="number" name="nominal" required min="1" class="form-input text-sm" placeholder="Contoh: 150000">
                            </div>

                            <div class="form-group mb-4">
                                <label class="form-label">Keterangan Tambahan</label>
                                <textarea name="keterangan" rows="2" class="form-input text-sm" placeholder="Opsional"></textarea>
                            </div>

                            <button type="submit" class="w-full btn btn-primary justify-center mt-2">
                                Simpan Pengeluaran
                            </button>
                        </form>
                    </div>
                </div>
                @endif

                <!-- Tabel Data -->
                <div class="{{ auth()->user()->hasRole('bendahara') ? 'md:col-span-2' : 'md:col-span-3' }}">
                    <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                            <h3 class="text-lg font-bold">Riwayat Pengeluaran Operasional</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nominal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($pengeluarans as $peng)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $peng->tanggal->format('d M Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $peng->kategori }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-bold">Rp {{ number_format($peng->nominal, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $peng->keterangan ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            @if(auth()->user()->hasRole('bendahara'))
                                            <form action="{{ route('pengeluaran.destroy', $peng) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data pengeluaran ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 px-2">Hapus</button>
                                            </form>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada riwayat pengeluaran.</td>
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
