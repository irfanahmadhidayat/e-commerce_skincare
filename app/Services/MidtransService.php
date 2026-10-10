<?php

namespace App\Services;

use App\Models\Pesanan;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = (bool) config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function snapToken(Pesanan $pesanan): string
    {
        $params = [
            'transaction_details' => [
                'order_id' => $pesanan->nomor,
                'gross_amount' => (int) round($pesanan->total),
            ],
            'finish_redirect_url' => route('pesanan.bayar', $pesanan->nomor),
            'item_details' => $pesanan->detailPesanan->map(fn ($d) => [
                'id' => $d->produk_id,
                'price' => (int) round($d->harga_satuan),
                'quantity' => $d->jumlah,
                'name' => Str::limit($d->produk->nama, 50),
            ])->all(),
            'customer_details' => [
                'first_name' => $pesanan->nama_penerima,
                'phone' => $pesanan->no_hp,
                'billing_address' => [
                    'address' => $pesanan->alamat,
                ],
            ],
        ];

        return Snap::getSnapToken($params);
    }
}
