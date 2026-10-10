<?php

namespace App\Models;

use Database\Factories\PesananFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['customer_id', 'nomor', 'tanggal', 'status', 'subtotal', 'ongkir', 'total', 'nama_penerima', 'no_hp', 'alamat', 'catatan'])]
class Pesanan extends Model
{
    /** @use HasFactory<PesananFactory> */
    use HasFactory;

    protected $table = 'pesanan';

    protected function casts(): array
    {
        return [
            'tanggal' => 'datetime',
            'subtotal' => 'decimal:2',
            'ongkir' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function detailPesanan(): HasMany
    {
        return $this->hasMany(DetailPesanan::class, 'pesanan_id');
    }

    public function pembayaran(): HasOne
    {
        return $this->hasOne(Pembayaran::class, 'pesanan_id');
    }

    protected static function booted(): void
    {
        static::updated(function (Pesanan $pesanan): void {
            if ($pesanan->status !== 'dibayar' || $pesanan->getOriginal('status') === 'dibayar') {
                return;
            }

            foreach ($pesanan->detailPesanan as $detail) {
                $detail->produk?->decrement('stok', $detail->jumlah);
            }
        });
    }
}
