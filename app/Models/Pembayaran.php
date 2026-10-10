<?php

namespace App\Models;

use Database\Factories\PembayaranFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['pesanan_id', 'metode', 'nomor_rekening', 'nama_pengirim', 'rekening_pengirim', 'jumlah', 'tanggal_bayar', 'status', 'referensi', 'bukti'])]
class Pembayaran extends Model
{
    /** @use HasFactory<PembayaranFactory> */
    use HasFactory;

    protected $table = 'pembayaran';

    protected function casts(): array
    {
        return [
            'jumlah' => 'decimal:2',
            'tanggal_bayar' => 'date',
        ];
    }

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }
}
