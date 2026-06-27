@extends('layouts.app')

@section('title', 'Pengaturan Anggaran')

@section('content')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
        Pengaturan Sistem
    </h2>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden max-w-2xl">
                <div class="p-6 border-b border-gray-200">
                    
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                            {{ session('success') }}
                        </div>
                    @endif

                    <h3 class="text-lg font-bold mb-4">Pengaturan Anggaran Bulanan</h3>
                    <p class="text-gray-500 text-sm mb-6">Anggaran ini digunakan sebagai batas maksimal pengeluaran bulanan (Invoice + Operasional) untuk menghitung sisa anggaran di Dashboard dan Laporan Keuangan.</p>

                    <form action="{{ route('settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group mb-4">
                            <label class="form-label font-bold text-gray-700">Anggaran Bulanan (Rp)</label>
                            <input type="number" name="anggaran_bulanan" value="{{ old('anggaran_bulanan', $anggaran) }}" required min="0" class="form-input text-lg font-bold text-gray-900 mt-1">
                            @error('anggaran_bulanan')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mt-6 flex">
                            <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
