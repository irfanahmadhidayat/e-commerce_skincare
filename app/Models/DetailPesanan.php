<?php

namespace App\Models;

use Database\Factories\DetailPesananFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['pesanan_id', 'produk_id', 'harga_satuan', 'jumlah'])]
class DetailPesanan extends Model
{
    /** @use HasFactory<DetailPesananFactory> */
    use HasFactory;

    protected $table = 'detail_pesanan';

    protected function casts(): array
    {
        return [
            'harga_satuan' => 'decimal:2',
        ];
    }

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
