<?php

use App\Models\Pesanan;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.store')] class extends Component
{
    public function with(): array
    {
        return [
            'pesanan' => Pesanan::where('customer_id', auth()->id())->latest()->get(),
        ];
    }
}; ?>

<div>
    <h1 class="text-2xl font-bold text-gray-800">Pesanan Saya</h1>
    <div class="mt-6 space-y-4">
        @forelse($pesanan as $p)
            <div class="bg-white border rounded-xl p-5 flex items-center justify-between">
                <div>
                    <p class="font-semibold text-gray-800">{{ $p->nomor }}</p>
                    <p class="text-sm text-gray-500">{{ $p->tanggal->format('d M Y') }}  Rp {{ number_format($p->total,  0, ',', '.') }}</p>
                </div>
                <a href="{{ route('pesanan.bayar', $p->nomor) }}" wire:navigate class="text-sm text-blue-600 hover:underline">
                    {{ $p->status }}
                </a>
            </div>
        @empty
            <p class="text-gray-500">Belum ada pesanan.</p>
        @endforelse
    </div>
</div>