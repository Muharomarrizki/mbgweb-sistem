@extends('layouts.app')

@section('title', 'Edit Supplier')
@section('subtitle', 'Perbarui data supplier')

@section('content')
<div class="data-table-wrapper" style="max-width:700px;">
    <div class="data-table-header">
        <h3>Edit Supplier: {{ $supplier->nama }}</h3>
        <a href="{{ route('suppliers.index') }}" class="btn btn-outline btn-sm">← Kembali</a>
    </div>
    <div style="padding:1.5rem;">
        <form method="POST" action="{{ route('suppliers.update', $supplier) }}">
            @csrf
            @method('PUT')
            @include('suppliers._form', ['supplier' => $supplier])
            <div style="display:flex; gap:0.75rem; justify-content:flex-end; margin-top:1.5rem; padding-top:1rem; border-top:1px solid var(--border);">
                <a href="{{ route('suppliers.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">Perbarui Supplier</button>
            </div>
        </form>
    </div>
</div>
@endsection
