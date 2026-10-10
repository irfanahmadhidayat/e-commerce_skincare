<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class PesananController extends Controller
{
    public function index(): View
    {
        return view('admin.pesanan.index', [
            'pesanan' => Pesanan::with(['customer', 'pembayaran'])->latest()->get(),
        ]);
    }

    public function verifikasiPembayaran(Pesanan $pesanan): RedirectResponse
    {
        $pesanan->update(['status' => 'dibayar']);

        if ($pesanan->pembayaran) {
            $pesanan->pembayaran->update(['status' => 'terverifikasi']);
        }

        return back()->with('sukses', 'Pembayaran diverifikasi.');
    }

    public function tolakPembayaran(Pesanan $pesanan): RedirectResponse
    {
        $pesanan->update(['status' => 'dibatalkan']);

        if ($pesanan->pembayaran) {
            $pesanan->pembayaran->update(['status' => 'gagal']);
        }

        return back()->with('sukses', 'Pembayaran ditolak.');
    }
}
