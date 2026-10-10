<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'jumlahProduk' => Produk::count(),
            'jumlahPesanan' => Pesanan::count(),
            'jumlahKategori' => Kategori::count(),
            'jumlahPengguna' => User::count(),
            'pesananMenunggu' => Pesanan::where('status', 'menunggu pembayaran')->count(),
        ]);
    }
}
