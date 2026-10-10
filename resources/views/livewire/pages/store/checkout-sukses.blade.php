<?php

use App\Models\Pesanan;
use App\Services\Keranjang;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.store')] class extends Component
{
    public Pesanan $pesanan;

    public function mount(Pesanan $pesanan): void
    {
        $this->pesanan = $pesanan;
    }
}; ?>

<div class="max-w-2xl mx-auto text-center bg-white border rounded-xl p-10">
    <h1 class="text-2xl font-bold text-green-600">Pesanan Berhasil!</h1>
    <p class="mt-2 text-gray-600">Terima kasih, pesananmu sudah tersimpan.</p>

    <div class="mt-6 text-left bg-gray-50 rounded-lg p-6 space-y-2 text-sm">
        <p>Nomor Pesanan: <strong>{{ $pesanan->nomor }}</strong></p>
        <p>Status: <strong class="text-yellow-600">{{ $pesanan->status }}</strong></p>
        <p>Total: <strong>Rp {{ number_format($pesanan->total,  0, ',', '.') }}</strong></p>
    </div>

    <div class="mt-6 flex justify-center gap-4">
        <a href="{{ route('pesanan.bayar', $pesanan->nomor) }}" wire:navigate class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">Lanjut ke Pembayaran</a>
        <a href="{{ route('catalog') }}" wire:navigate class="bg-pink-600 text-white px-6 py-3 rounded-lg hover:bg-pink-700">Lihat Katalog</a>
    </div>
</div>