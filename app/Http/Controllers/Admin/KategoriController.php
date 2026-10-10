<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index(): View
    {
        return view('admin.kategori.index', [
            'kategori' => Kategori::withCount('produk')->orderBy('nama')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Kategori::create($request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'slug' => ['required', 'string', 'max:100', 'unique:kategori,slug'],
            'deskripsi' => ['nullable', 'string'],
            'aktif' => ['boolean'],
        ]));

        return back()->with('sukses', 'Kategori ditambahkan.');
    }

    public function toggle(Kategori $kategori): RedirectResponse
    {
        if ($kategori->produk()->where('aktif', true)->exists()) {
            return back()->with('error', 'Kategori masih punya produk aktif.');
        }

        $kategori->update(['aktif' => ! $kategori->aktif]);

        return back()->with('sukses', 'Status kategori diubah.');
    }
}
