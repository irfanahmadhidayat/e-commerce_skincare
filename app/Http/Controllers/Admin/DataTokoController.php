<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataToko;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DataTokoController extends Controller
{
    public function edit(): View
    {
        return view('admin.toko.edit', ['toko' => DataToko::firstOrCreate(['id' => 1], ['nama_toko' => 'Toko Skincare'])]);
    }

    public function update(Request $request): RedirectResponse
    {
        $toko = DataToko::firstOrCreate(['id' => 1], ['nama_toko' => 'Toko Skincare']);

        $validated = $request->validate([
            'nama_toko' => ['required', 'string', 'max:150'],
            'kontak' => ['nullable', 'string', 'max:50'],
            'alamat' => ['nullable', 'string'],
            'deskripsi' => ['nullable', 'string'],
            'rekening' => ['nullable', 'array'],
            'rekening.*.bank' => ['nullable', 'string', 'max:50'],
            'rekening.*.nomor' => ['nullable', 'string', 'max:30'],
            'rekening.*.atas_nama' => ['nullable', 'string', 'max:150'],
        ]);

        $rekening = collect($validated['rekening'] ?? [])
            ->filter(fn ($r) => ($r['bank'] ?? '') !== '' || ($r['nomor'] ?? '') !== '')
            ->values()
            ->map(fn ($r) => [
                'bank' => $r['bank'] ?? null,
                'nomor' => $r['nomor'] ?? null,
                'atas_nama' => $r['atas_nama'] ?? null,
            ])
            ->all();

        $toko->update([
            'nama_toko' => $validated['nama_toko'],
            'kontak' => $validated['kontak'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'deskripsi' => $validated['deskripsi'] ?? null,
            'rekening' => $rekening,
        ]);

        return redirect()->route('admin.toko.edit')->with('sukses', 'Data toko disimpan.');
    }
}
