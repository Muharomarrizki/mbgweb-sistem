<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        $anggaran = Setting::where('key', 'anggaran_bulanan')->value('value') ?? 50000000;
        return view('settings.edit', compact('anggaran'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'anggaran_bulanan' => 'required|numeric|min:0',
        ]);

        Setting::updateOrCreate(
            ['key' => 'anggaran_bulanan'],
            ['value' => $validated['anggaran_bulanan']]
        );

        return redirect()->back()->with('success', 'Pengaturan Anggaran Bulanan berhasil disimpan.');
    }
}
