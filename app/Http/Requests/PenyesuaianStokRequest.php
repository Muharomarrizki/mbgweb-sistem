<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PenyesuaianStokRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Hanya Admin Gudang yang boleh melakukan penyesuaian stok
        return $this->user()->hasRole('admin_gudang');
    }

    public function rules(): array
    {
        return [
            'bahan_baku_id' => 'required|exists:bahan_baku,id',
            'jenis_penyesuaian' => 'required|in:Masuk,Keluar',
            'qty' => 'required|numeric|min:0.01',
            'keterangan' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'bahan_baku_id.required' => 'Bahan baku wajib dipilih.',
            'jenis_penyesuaian.required' => 'Jenis penyesuaian wajib dipilih.',
            'qty.required' => 'Kuantitas wajib diisi.',
            'qty.min' => 'Kuantitas minimal 0.01.',
            'keterangan.required' => 'Keterangan/alasan wajib diisi.',
        ];
    }
}
