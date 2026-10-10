<?php

use App\Models\DataToko;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Services\MidtransService;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.store')] class extends Component
{
    public Pesanan $pesanan;
    public string $metode = 'midtrans';

    public string $nama_pengirim = '';
    public string $rekening_pengirim = '';
    public string $nomor_rekening = '';
    public string $jumlah = '';
    public string $tanggal_bayar = '';

    public function mount(Pesanan $pesanan): void
    {
        $this->pesanan = $pesanan;

        $status = request()->query('transaction_status');

        if (in_array($status, ['capture', 'settlement'])) {
            if ($pesanan->status !== 'dibayar') {
                $pesanan->update(['status' => 'dibayar']);
            }

            session()->flash('bayar_ok', 'Pembayaran berhasil. Terima kasih!');
        } elseif ($status === 'pending') {
            session()->flash('bayar_info', 'Pembayaran sedang diproses., mohon tunggu verifikasi.');
        } elseif (in_array($status, ['deny', 'cancel', 'expire', 'failure', 'error'])) {
            session()->flash('bayar_error', 'Pembayaran gagal/dibatalkan. Silakan coba lagi.');
        }
    }

    public function bayarMidtrans(MidtransService $midtrans): void
    {
        $this->dispatch('snap-token', token: $midtrans->snapToken($this->pesanan));
    }

    public function simpanKonfirmasi(): void
    {
        $this->validate([
            'nama_pengirim' => ['required', 'string'],
            'rekening_pengirim' => ['required', 'string'],
            'nomor_rekening' => ['required', 'string'],
            'jumlah' => ['required', 'numeric', 'min:1'],
            'tanggal_bayar' => ['required', 'date'],
        ]);

        Pembayaran::create([
            'pesanan_id' => $this->pesanan->id,
            'metode' => 'transfer',
            'nomor_rekening' => $this->nomor_rekening,
            'nama_pengirim' => $this->nama_pengirim,
            'rekening_pengirim' => $this->rekening_pengirim,
            'jumlah' => $this->jumlah,
            'tanggal_bayar' => $this->tanggal_bayar,
            'status' => 'menunggu',
        ]);

        session()->flash('konfirmasi_ok', 'Konfirmasi pembayaran terkirim, menunggu verifikasi admin.');
    }

    public function with(): array
    {
        return [
            'rekeningToko' => DataToko::find(1)?->rekening ?? [],
        ];
    }
}; ?>

<div class="max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800">Pembayaran</h1>

    @if (session('bayar_ok'))
        <p class="mt-4 bg-green-50 text-green-600 border border-green-200 rounded-lg px-4 py-3">{{ session('bayar_ok') }}</p>
    @endif
    @if (session('bayar_info'))
        <p class="mt-4 bg-yellow-50 text-yellow-700 border border-yellow-200 rounded-lg px-4 py-3">{{ session('bayar_info') }}</p>
    @endif
    @if (session('bayar_error'))
        <p class="mt-4 bg-red-50 text-red-600 border border-red-200 rounded-lg px-4 py-3">{{ session('bayar_error') }}</p>
    @endif

    <div class="mt-4 bg-white border rounded-xl p-6 flex justify-between text-sm">
        <span>Nomor Pesanan: <strong>{{ $pesanan->nomor }}</strong></span>
        <span>Total: <strong>Rp {{ number_format($pesanan->total,  0, ',', '.') }}</strong></span>
        <span>Status: <strong class="text-yellow-600">{{ $pesanan->status }}</strong></span>
    </div>

    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-8">
        {{-- Metode Midtrans --}}
        <div class="bg-white border rounded-xl p-6">
            <h2 class="font-semibold text-gray-800">1. Bayar Online (Midtrans)</h2>
            <p class="mt-2 text-sm text-gray-500">VA, QRIS, e-Wallet, kartu. Pembayaran diverifikasi otomatis.</p>
            <button wire:click="bayarMidtrans" class="mt-4 w-full bg-blue-600 text-white rounded-lg px-4 py-3 hover:bg-blue-700">Bayar via Midtrans</button>
        </div>

        {{-- Metode Manual --}}
        <div class="bg-white border rounded-xl p-6">
            <h2 class="font-semibold text-gray-800">2. Transfer Bank (Manual)</h2>
            <p class="mt-2 text-sm text-gray-500">Transfer ke rekening toko, lalu kirim konfirmasi:</p>

            <div class="mt-3 text-sm bg-gray-50 rounded-lg p-4 space-y-1">
                @forelse($rekeningToko as $r)
                    <p>
                        <strong>{{ $r['bank'] }}</strong> {{ $r['nomor'] }}
                        @if (! empty($r['atas_nama'])) a.n. {{ $r['atas_nama'] }} @endif
                    </p>
                @empty
                    <p class="text-gray-500">Rekening toko belum diisi admin.</p>
                @endforelse
            </div>

            @if (session('konfirmasi_ok'))
                <p class="mt-3 text-green-600 text-sm">{{ session('konfirmasi_ok') }}</p>
            @endif

            <form wire:submit="simpanKonfirmasi" class="mt-4 space-y-3">
                <input type="text" wire:model="nama_pengirim" placeholder="Nama pengirim" class="w-full border rounded-lg px-3 py-2 text-sm">
                @error('nama_pengirim') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                <input type="text" wire:model="rekening_pengirim" placeholder="Rekening pengirim" class="w-full border rounded-lg px-3 py-2 text-sm">
                @error('rekening_pengirim') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                <input type="text" wire:model="nomor_rekening" placeholder="Rekening tujuan (BCA/Mandiri)" class="w-full border rounded-lg px-3 py-2 text-sm">
                @error('nomor_rekening') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                <input type="text" wire:model="jumlah" placeholder="Jumlah transfer" class="w-full border rounded-lg px-3 py-2 text-sm">
                @error('jumlah') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                <input type="date" wire:model="tanggal_bayar" class="w-full border rounded-lg px-3 py-2 text-sm">
                @error('tanggal_bayar') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                <button type="submit" class="w-full bg-green-600 text-white rounded-lg px-4 py-3 hover:bg-green-700">Kirim Konfirmasi</button>
            </form>
        </div>
    </div>
</div>

{{-- Snap.js --}}
@push('js')
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>
@endpush

@script
<script>
    $wire.on('snap-token', (data) => {
        const finishUrl = @json(route('pesanan.bayar', $pesanan->nomor));

        window.snap.pay(data.token, {
            onSuccess: function (result) {
                window.location.href = finishUrl + '?order_id=' + encodeURIComponent(result.order_id) + '&transaction_status=capture';
            },
            onPending: function (result) {
                window.location.href = finishUrl + '?order_id=' + encodeURIComponent(result.order_id) + '&transaction_status=pending';
            },
            onError: function (result) {
                window.location.href = finishUrl + '?order_id=' + encodeURIComponent(result.order_id) + '&transaction_status=error';
            },
        });
    });
</script>
@endscript