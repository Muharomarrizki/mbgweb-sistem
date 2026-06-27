@extends('layouts.app')

@section('title', 'Laporan Keuangan & Arus Kas')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Laporan Keuangan Bulanan
        </h2>
        <div class="flex gap-2">
            <form method="GET" action="{{ route('laporan-keuangan.index') }}" class="flex gap-2">
                <select name="bulan" class="form-input text-sm py-1.5" onchange="this.form.submit()">
                    @for($i=1; $i<=12; $i++)
                        <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ $bulan == $i ? 'selected' : '' }}>
                            {{ date('F', mktime(0,0,0,$i,1)) }}
                        </option>
                    @endfor
                </select>
                <select name="tahun" class="form-input text-sm py-1.5" onchange="this.form.submit()">
                    @for($i=date('Y'); $i>=date('Y')-3; $i--)
                        <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </form>
            <a href="{{ route('laporan-keuangan.pdf', ['bulan' => $bulan, 'tahun' => $tahun]) }}" target="_blank" class="btn btn-outline border-gray-300 text-gray-700 bg-white hover:bg-gray-50 flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                    <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                </svg>
                Export PDF
            </a>
        </div>
    </div>

    <!-- Rekap Metrik -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm p-5 border-l-4 border-blue-500">
            <p class="text-sm text-gray-500 font-medium mb-1">Anggaran Bulanan</p>
            <h3 class="text-xl font-bold text-gray-800">Rp {{ number_format($anggaran, 0, ',', '.') }}</h3>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-5 border-l-4 border-orange-500">
            <p class="text-sm text-gray-500 font-medium mb-1">Pengeluaran Bahan Baku</p>
            <h3 class="text-xl font-bold text-gray-800">Rp {{ number_format($totalInvoice, 0, ',', '.') }}</h3>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-5 border-l-4 border-yellow-500">
            <p class="text-sm text-gray-500 font-medium mb-1">Pengeluaran Operasional</p>
            <h3 class="text-xl font-bold text-gray-800">Rp {{ number_format($totalOpex, 0, ',', '.') }}</h3>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-5 border-l-4 border-green-500">
            <p class="text-sm text-gray-500 font-medium mb-1">Sisa Anggaran</p>
            <h3 class="text-xl font-bold {{ $sisaAnggaran < 0 ? 'text-red-600' : 'text-green-600' }}">Rp {{ number_format($sisaAnggaran, 0, ',', '.') }}</h3>
        </div>
    </div>

    <!-- Tabel Rincian -->
    <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-bold text-gray-800">Rincian Arus Kas Keluar (Pengeluaran)</h3>
            <p class="text-sm text-gray-500">Periode: {{ date('F Y', mktime(0,0,0,$bulan,1,$tahun)) }}</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-white">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider bg-gray-100">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider bg-gray-100">Keterangan Transaksi</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider bg-gray-100">Kategori</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider bg-gray-100">Nominal Keluar</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($transaksis as $t)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($t['tanggal'])->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $t['keterangan'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($t['kategori'] === 'Bahan Baku')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Bahan Baku</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">{{ $t['kategori'] }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-bold text-right">Rp {{ number_format($t['nominal'], 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                            Tidak ada data pengeluaran pada periode ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-gray-50 font-bold">
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-right text-gray-700">Total Pengeluaran Bulan Ini:</td>
                        <td class="px-6 py-4 text-right text-red-600 text-lg">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection
