<?php

use App\Models\Kategori;
use App\Models\Produk;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.store')] class extends Component
{
    public ?string $kategori = null;

    public function mount(): void
    {
        $kategori = request()->query('kategori');

        $this->kategori = is_string($kategori) && $kategori !== '' ? $kategori : null;
    }

    public function with(): array
    {
        $query = Produk::query()->where('aktif', true);

        if ($this->kategori) {
            $query->whereHas('kategori', fn ($q) => $q->where('slug', $this->kategori));
        }

        return [
            'produk' => $query->orderBy('nama')->get(),
            'kategoriList' => Kategori::where('aktif', true)->get(),
        ];
    }
}; ?>

<div>
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-2xl font-bold text-gray-800">Katalog Produk</h1>
        <select wire:model.live="kategori" class="border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white shadow-sm focus:border-pink-400 focus:outline-none">
            <option value="">Semua Kategori</option>
            @foreach($kategoriList as $k)
                <option value="{{ $k->slug }}">{{ $k->nama }}</option>
            @endforeach
        </select>
    </div>

    @if ($produk->isEmpty())
        <div class="text-center py-16 text-gray-500">Tidak ada produk di kategori ini.</div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-4">
            @foreach($produk as $p)
                <x-produk-card :produk="$p" />
            @endforeach
        </div>
    @endif
</div>