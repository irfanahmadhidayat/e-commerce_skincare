<?php

use App\Models\DetailPesanan;
use App\Models\Pesanan;
use App\Services\Keranjang;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.store')] class extends Component
{
    public string $nama_penerima = '';
    public string $no_hp = '';
    public string $alamat = '';
    public ?string $catatan = null;

    public function mount(): void
    {
        $user = auth()->user();
        $this->nama_penerima = $user->name;
        $this->no_hp = $user->no_hp ?? '';
        $this->alamat = $user->alamat ?? '';
    }

    public function with(Keranjang $keranjang): array
    {
        return [
            'items' => $keranjang->items(),
            'subtotal' => $keranjang->total(),
            'ongkir' => 0,
            'total' => $keranjang->total(),
        ];
    }

    public function simpan(Keranjang $keranjang): void
    {
        $this->validate([
            'nama_penerima' => ['required', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'max:25'],
            'alamat' => ['required', 'string'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ]);

        $items = $keranjang->items();

        if (empty($items)) {
            $this->redirect(route('catalog', absolute: false));
            return;
        }

        foreach ($items as $item) {
            if ($item['jumlah'] > $item['produk']->stok) {
                session()->flash('checkout_error', 'Stok "'.$item['produk']->nama.'" tidak mencukupi.');
                return;
            }
        }

        DB::transaction(function () use ($items, $keranjang) {
            $pesanan = Pesanan::create([
                'customer_id' => auth()->id(),
                'nomor' => 'ARL-'.now()->format('ymdHis').'-'.random_int(100, 999),
                'tanggal' => now(),
                'status' => 'menunggu pembayaran',
                'subtotal' => $keranjang->total(),
                'ongkir' => 0,
                'total' => $keranjang->total(),
                'nama_penerima' => $this->nama_penerima,
                'no_hp' => $this->no_hp,
                'alamat' => $this->alamat,
                'catatan' => $this->catatan,
            ]);

            foreach ($items as $item) {
                DetailPesanan::create([
                    'pesanan_id' => $pesanan->id,
                    'produk_id' => $item['produk']->id,
                    'harga_satuan' => $item['produk']->harga,
                    'jumlah' => $item['jumlah'],
                ]);
            }

            $keranjang->hapusSemua();

            $this->redirect(route('pesanan.sukses', $pesanan->nomor, absolute: false));
        });
    }
}; ?>

<div>
    <h1 class="text-2xl font-bold text-gray-800">Checkout</h1>

    @if (session('checkout_error'))
        <p class="mt-4 bg-red-50 text-red-600 border border-red-200 rounded-lg px-4 py-3">{{ session('checkout_error') }}</p>
    @endif

    @if (empty($items))
        <p class="mt-4 text-gray-500">Keranjang kosong. <a href="{{ route('catalog') }}" wire:navigate class="text-pink-600 underline">Pilih produk dulu</a></p>
    @else
        <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-8">
            <form wire:submit="simpan" class="lg:col-span-2 bg-white border rounded-xl p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Penerima *</label>
                    <input type="text" wire:model="nama_penerima" class="mt-1 w-full border rounded-lg px-3 py-2">
                    @error('nama_penerima') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">No. HP *</label>
                    <input type="text" wire:model="no_hp" class="mt-1 w-full border rounded-lg px-3 py-2">
                    @error('no_hp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Alamat *</label>
                    <textarea wire:model="alamat" rows="3" class="mt-1 w-full border rounded-lg px-3 py-2"></textarea>
                    @error('alamat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Catatan (opsional)</label>
                    <textarea wire:model="catatan" rows="2" class="mt-1 w-full border rounded-lg px-3 py-2"></textarea>
                </div>
                <button type="submit" class="w-full bg-pink-600 text-white rounded-lg py-3 hover:bg-pink-700">Buat Pesanan</button>
            </form>

            <aside class="bg-white border rounded-xl p-6 h-fit">
                <h2 class="font-semibold text-gray-800">Ringkasan Pesanan</h2>
                <div class="mt-4 space-y-3 text-sm">
                    @foreach($items as $item)
                        <div class="flex justify-between">
                            <span>{{ $item['produk']->nama }} x{{ $item['jumlah'] }}</span>
                            <span>Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 border-t pt-4 space-y-2 text-sm">
                    <div class="flex justify-between"><span>Subtotal</span><span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between"><span>Ongkir</span><span>Rp {{ number_format($ongkir, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between font-bold"><span>Total</span><span>Rp {{ number_format($total, 0, ',', '.') }}</span></div>
                </div>
            </aside>
        </div>
    @endif
</div>