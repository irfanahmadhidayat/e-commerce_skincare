<?php

namespace App\Services;

use App\Models\Produk;

class Keranjang
{
    public function key(): string
    {
        return 'keranjang';
    }

    public function semua(): array
    {
        return session()->get($this->key(), []);
    }

    public function tambah(int $produkId, int $jumlah = 1): void
    {
        $produk = Produk::findOrFail($produkId);

        if ($produk->stok <= 0) {
            return;
        }

        $items = $this->semua();
        $lama = $items[$produkId] ?? 0;
        $items[$produkId] = min($lama + $jumlah, $produk->stok);
        session()->put($this->key(), $items);
    }

    public function ubah(int $produkId, int $jumlah): void
    {
        $produk = Produk::findOrFail($produkId);
        $items = $this->semua();

        if ($jumlah <= 0) {
            unset($items[$produkId]);
        } else {
            $items[$produkId] = min($jumlah, $produk->stok);
        }

        session()->put($this->key(), $items);
    }

    public function hapus(int $produkId): void
    {
        $items = $this->semua();
        unset($items[$produkId]);
        session()->put($this->key(), $items);
    }

    public function hapusSemua(): void
    {
        session()->forget($this->key());
    }

    public function items(): array
    {
        $items = $this->semua();

        if (empty($items)) {
            return [];
        }

        $produk = Produk::whereIn('id', array_keys($items))->get()->keyBy('id');

        return $produk->map(fn ($p) => [
            'produk' => $p,
            'jumlah' => $items[$p->id],
            'subtotal' => $p->harga * $items[$p->id],
        ])->values()->all();
    }

    public function total(): float
    {
        return array_sum(array_column($this->items(), 'subtotal'));
    }

    public function count(): int
    {
        return array_sum($this->semua());
    }
}
