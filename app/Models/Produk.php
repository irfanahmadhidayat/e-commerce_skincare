<?php

namespace App\Models;

use Database\Factories\ProdukFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['kategori_id', 'nama', 'slug', 'deskripsi', 'harga', 'stok', 'foto', 'aktif'])]
class Produk extends Model
{
    /** @use HasFactory<ProdukFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'produk';

    protected function casts(): array
    {
        return [
            'harga' => 'decimal:2',
            'aktif' => 'boolean',
        ];
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
}
