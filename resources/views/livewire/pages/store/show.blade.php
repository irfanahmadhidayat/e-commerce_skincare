<?php

use App\Models\Produk;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.store')] class extends Component
{
    public Produk $produk;
    public int $jumlah = 1;

    public function mount(Produk $produk): void
    {
        $this->produk = $produk;
    }

    public function tambahKeKeranjang(): void
    {
        $this->validate([
            'jumlah' => ['required', 'integer', 'min:1', 'max:'.$this->produk->stok],
        ]);

        app(\App\Services\Keranjang::class)->tambah($this->produk->id, $this->jumlah);

        $this->dispatch('keranjang-updated');

        session()->flash('cart_success', 'Produk ditambahkan ke keranjang!');
    }
}; ?>

<div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-start">
    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
        <img src="{{ $this->produk->foto ? asset('storage/'.$this->produk->foto) : 'https://placehold.co/600x600?text=Produk' }}"
             alt="{{ $this->produk->nama }}"
             class="w-full h-96 object-cover">
    </div>
    <div>
        <h1 class="text-2xl font-bold text-gray-800">{{ $this->produk->nama }}</h1>
        <p class="mt-3 text-3xl font-bold text-pink-600">Rp {{ number_format($this->produk->harga,  0, ',', '.') }}</p>

        <span class="inline-block mt-4 px-3 py-1 rounded-full text-sm {{ $this->produk->stok > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
            {{ $this->produk->stok > 0 ? 'Tersedia ('.$this->produk->stok.' stok)' : 'Stok Habis' }}
        </span>

        <div class="mt-6 bg-white border border-gray-200 rounded-xl p-4">
            <h3 class="font-semibold text-gray-800 mb-2">Deskripsi</h3>
            <p class="text-gray-600 leading-relaxed">{{ $this->produk->deskripsi }}</p>
        </div>

        @auth
            <form wire:submit="tambahKeKeranjang" class="mt-8">
                <div class="flex items-center gap-3">
                    <input type="number" wire:model="jumlah" min="1" max="{{ $this->produk->stok }}"
                           class="w-24 border rounded-lg px-3 py-2 text-center"
                           {{ $this->produk->stok <= 0 ? 'disabled' : '' }}>
                    <button type="submit" class="bg-pink-600 text-white px-6 py-3 rounded-lg hover:bg-pink-700 disabled:opacity-50 disabled:cursor-not-allowed"
                            {{ $this->produk->stok <= 0 ? 'disabled' : '' }}>
                        Tambah ke Keranjang
                    </button>
                </div>
                @if (session('cart_success'))
                    <p class="mt-2 text-green-600 text-sm">{{ session('cart_success') }}</p>
                @endif
                @error('jumlah') <p class="mt-2 text-red-500 text-sm">{{ $message }}</p> @enderror
            </form>
        @else
            <a href="{{ route('login') }}" class="inline-block mt-8 bg-pink-600 text-white px-6 py-3 rounded-lg hover:bg-pink-700">Masuk untuk Menambahkan ke Keranjang</a>
        @endauth
    </div>
</div>