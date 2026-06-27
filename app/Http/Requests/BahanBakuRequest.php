<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BahanBakuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_bahan' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'satuan' => 'required|string|max:50',
            'harga_terakhir' => 'nullable|numeric|min:0',
            'stok_minimum' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_bahan.required' => 'Nama bahan baku wajib diisi.',
            'kategori.required' => 'Kategori wajib dipilih.',
            'satuan.required' => 'Satuan wajib dipilih.',
        ];
    }
}
