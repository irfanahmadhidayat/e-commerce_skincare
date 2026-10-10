<?php

use App\Services\Keranjang;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.store')] class extends Component {
    public array $jumlah = [];

    public function mount(Keranjang $keranjang): void
    {
        $this->jumlah = $keranjang->semua();
    }

    public function ubah(int $produkId): void
    {
        $keranjang = app(Keranjang::class);
        $keranjang->ubah($produkId, $this->jumlah[$produkId] ?? 1);
        $this->jumlah = $keranjang->semua();

        $this->dispatch('keranjang-updated');
    }

    public function hapus(int $produkId): void
    {
        $keranjang = app(Keranjang::class);
        $keranjang->hapus($produkId);
        $this->jumlah = $keranjang->semua();

        $this->dispatch('keranjang-updated');
    }

    public function with(Keranjang $keranjang): array
    {
        return [
            'items' => $keranjang->items(),
            'total' => $keranjang->total(),
        ];
    }
}; ?>

<div>
    <h1 class="text-2xl font-bold text-gray-800">Keranjang Belanja</h1>

    @if (empty($items))
        <p class="mt-4 text-gray-500">
            Keranjangmu kosong. <a href="{{ route('catalog') }}" wire:navigate class="text-pink-600 underline">Mulai
                belanja</a>
        </p>
    @else
        <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-4">
                @foreach ($items as $item)
                    <div class="flex items-center gap-4 bg-white border rounded-xl p-4">
                        <img src="{{ $item['produk']->foto ? asset('storage/' . $item['produk']->foto) : 'https://placehold.co/80x80?text=Produk' }}"
                            alt="{{ $item['produk']->nama }}" class="w-20 h-20 object-cover rounded-lg">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-800">{{ $item['produk']->nama }}</h3>
                            <p class="text-sm text-gray-500">Rp
                                {{ number_format($item['produk']->harga, 0, ',', '.') }} / item</p>
                            <div class="mt-2 flex items-center gap-3">
                                <input type="number" min="1" max="{{ $item['produk']->stok }}"
                                    wire:model="jumlah.{{ $item['produk']->id }}"
                                    wire:change="ubah({{ $item['produk']->id }})"
                                    class="w-20 border rounded-lg px-3 py-1 text-center">
                                <button wire:click="hapus({{ $item['produk']->id }})"
                                    class="text-sm text-red-500 hover:underline">Hapus</button>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-800">Rp
                                {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <aside class="bg-white border rounded-xl p-6 h-fit">
                <h2 class="font-semibold text-gray-800">Ringkasan Belanja</h2>
                <div class="mt-4 border-t pt-4 flex justify-between font-bold">
                    <span>Total</span>
                    <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <a href="{{ route('checkout') }}" wire:navigate
                    class="mt-6 block text-center bg-pink-600 text-white rounded-lg px-4 py-3 hover:bg-pink-700">
                    Lanjut ke Checkout
                </a>
            </aside>
        </div>
    @endif
</div>
