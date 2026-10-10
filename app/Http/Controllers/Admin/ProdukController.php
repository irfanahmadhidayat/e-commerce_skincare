<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Intervention\Image\ImageManager;

class ProdukController extends Controller
{
    public function index(): View
    {
        return view('admin.produk.index', ['produk' => Produk::with('kategori')->latest()->get()]);
    }

    public function create(): View
    {
        return view('admin.produk.form', [
            'kategoriList' => Kategori::where('aktif', true)->get(),
            'produk' => null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['foto'] = $this->simpanFoto($request);
        $data['aktif'] = $request->boolean('aktif');

        Produk::create($data);

        return redirect()->route('admin.produk.index')->with('sukses', 'Produk ditambahkan.');
    }

    public function edit(Produk $produk): View
    {
        return view('admin.produk.form', [
            'kategoriList' => Kategori::where('aktif', true)->get(),
            'produk' => $produk,
        ]);
    }

    public function update(Request $request, Produk $produk): RedirectResponse
    {
        $data = $this->validated($request, $produk);
        $data['aktif'] = $request->boolean('aktif');

        if ($foto = $this->simpanFoto($request)) {
            $data['foto'] = $foto;
        }

        $produk->update($data);

        return redirect()->route('admin.produk.index')->with('sukses', 'Produk diperbarui.');
    }

    public function destroy(Produk $produk): RedirectResponse
    {
        $produk->delete();

        return redirect()->route('admin.produk.index')->with('sukses', 'Produk dihapus.');
    }

    protected function validated(Request $request, ?Produk $produk = null): array
    {
        $slugRule = Rule::unique('produk', 'slug');

        if ($produk) {
            $slugRule->ignore($produk->getKey());
        }

        return $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'slug' => ['required', 'string', 'max:150', $slugRule],
            'kategori_id' => ['required', 'exists:kategori,id'],
            'deskripsi' => ['nullable', 'string'],
            'harga' => ['required', 'numeric', 'min:0'],
            'stok' => ['required', 'integer', 'min:0'],
        ]);
    }

    protected function simpanFoto(Request $request): ?string
    {
        if (! $request->hasFile('foto')) {
            return null;
        }

        $nama = 'produk/'.Str::uuid().'.webp';

        ImageManager::gd()
            ->read($request->file('foto'))
            ->scaleDown(800)
            ->toWebp(85)
            ->save(storage_path('app/public/'.$nama));

        return $nama;
    }
}
