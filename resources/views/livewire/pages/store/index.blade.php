<?php

use App\Models\Kategori;
use App\Models\Produk;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.store')] class extends Component
{
    public function with(): array
    {
        return [
            'produkTerbaru' => Produk::where('aktif', true)->latest()->take(8)->get(),
            'kategoriList' => Kategori::where('aktif', true)->get(),
        ];
    }
}; ?>

<div>
    <section class="text-center py-16 bg-gradient-to-r from-pink-50 to-white rounded-3xl px-6">
        <h1 class="text-3xl font-bold text-gray-800">Selamat Datang di Toko Skincare</h1>
        <p class="mt-3 text-gray-500 max-w-xl mx-auto">Rawat kulitmu dengan produk pilihan, tanpa tanya stok lagi.</p>
        <a href="{{ route('catalog') }}" wire:navigate class="inline-block mt-6 bg-pink-600 text-white px-6 py-3 rounded-full font-medium hover:bg-pink-700 hover:shadow-lg transition">Lihat Katalog</a>
    </section>

    <section class="mt-12">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Kategori</h2>
            <a href="{{ route('catalog') }}" wire:navigate class="text-sm text-pink-600 hover:underline">Lihat Semua</a>
        </div>
        <div class="flex flex-wrap gap-2">
            @foreach($kategoriList as $k)
                <a href="{{ route('catalog', ['kategori' => $k->slug]) }}" wire:navigate class="px-4 py-2 bg-white border border-gray-200 rounded-full text-sm text-gray-600 hover:border-pink-400 hover:text-pink-600 hover:bg-pink-50 transition">
                    {{ $k->nama }}
                </a>
            @endforeach
        </div>
    </section>

    <section class="mt-10">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Produk Terbaru</h2>
            <a href="{{ route('catalog') }}" wire:navigate class="text-sm text-pink-600 hover:underline">Lihat Semua</a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-4">
            @foreach($produkTerbaru as $p)
                <x-produk-card :produk="$p" />
            @endforeach
        </div>
    </section>
</div>