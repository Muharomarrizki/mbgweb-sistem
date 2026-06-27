@extends('layouts.app')

@section('title', 'Edit Bahan Baku')
@section('subtitle', 'Perbarui data bahan baku')

@section('content')
<div class="data-table-wrapper" style="max-width:700px;">
    <div class="data-table-header">
        <h3>Edit: {{ $bahanBaku->nama_bahan }}</h3>
        <a href="{{ route('bahan-baku.index') }}" class="btn btn-outline btn-sm">← Kembali</a>
    </div>
    <div style="padding:1.5rem;">
        <form method="POST" action="{{ route('bahan-baku.update', $bahanBaku) }}">
            @csrf
            @method('PUT')
            @include('bahan-baku._form', ['bahanBaku' => $bahanBaku])
            <div style="display:flex; gap:0.75rem; justify-content:flex-end; margin-top:1.5rem; padding-top:1rem; border-top:1px solid var(--border);">
                <a href="{{ route('bahan-baku.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">Perbarui Bahan Baku</button>
            </div>
        </form>
    </div>
</div>
@endsection
