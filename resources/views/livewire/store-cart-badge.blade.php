<?php

use App\Services\Keranjang;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component
{
    public int $jumlah = 0;

    public function mount(): void
    {
        $this->jumlah = app(Keranjang::class)->count();
    }

    #[On('keranjang-updated')]
    public function perbarui(): void
    {
        $this->jumlah = app(Keranjang::class)->count();
    }
}; ?>

<a href="{{ route('cart') }}" wire:navigate class="relative text-gray-700 hover:text-pink-600" title="Keranjang">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
    </svg>
    @if ($jumlah > 0)
        <span class="absolute -top-1.5 -right-2 inline-flex items-center justify-center min-w-5 h-5 px-1.5 text-xs font-semibold text-white bg-pink-600 rounded-full">{{ $jumlah }}</span>
    @endif
</a>