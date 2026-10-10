<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransNotificationController extends Controller
{
    public function handle(Request $request): void
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = (bool) config('services.midtrans.is_production');

        $payload = json_decode($request->getContent(), true);

        $expected = hash(
            'sha512',
            ($payload['order_id'] ?? '').
            ($payload['status_code'] ?? '').
            ($payload['gross_amount'] ?? '').
            Config::$serverKey,
        );

        if (! is_string($payload['signature_key'] ?? null) || ! hash_equals($expected, $payload['signature_key'])) {
            abort(403);
        }

        $notif = new Notification;
        $status = (string) $notif->transaction_status;
        $orderId = (string) $notif->order_id;

        $pesanan = Pesanan::where('nomor', $orderId)->first();

        if ($pesanan === null) {
            abort(404);
        }

        if (in_array($status, ['capture', 'settlement'])) {
            $pesanan->update(['status' => 'dibayar']);

            Pembayaran::updateOrCreate(
                ['pesanan_id' => $pesanan->id],
                [
                    'metode' => (string) $notif->payment_type,
                    'referensi' => (string) $notif->transaction_id,
                    'jumlah' => $pesanan->total,
                    'status' => 'terverifikasi',
                ],
            );
        } elseif ($status === 'pending') {
            $pesanan->update(['status' => 'menunggu pembayaran']);
        } elseif (in_array($status, ['deny', 'cancel', 'expire', 'failure'])) {
            $pesanan->update(['status' => 'dibatalkan']);
        }
    }
}
