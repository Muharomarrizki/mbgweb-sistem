@extends('layouts.app')

@section('title', 'Tambah Supplier')
@section('subtitle', 'Tambah data supplier baru')

@section('content')
<div class="data-table-wrapper" style="max-width:700px;">
    <div class="data-table-header">
        <h3>Form Tambah Supplier</h3>
        <a href="{{ route('suppliers.index') }}" class="btn btn-outline btn-sm">← Kembali</a>
    </div>
    <div style="padding:1.5rem;">
        <form method="POST" action="{{ route('suppliers.store') }}">
            @csrf
            @include('suppliers._form')
            <div style="display:flex; gap:0.75rem; justify-content:flex-end; margin-top:1.5rem; padding-top:1rem; border-top:1px solid var(--border);">
                <a href="{{ route('suppliers.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Supplier</button>
            </div>
        </form>
    </div>
</div>
@endsection
