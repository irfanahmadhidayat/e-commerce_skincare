<?php

namespace App\Models;

use Database\Factories\KategoriFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nama', 'slug', 'deskripsi', 'aktif'])]
class Kategori extends Model
{
    /** @use HasFactory<KategoriFactory> */
    use HasFactory;

    protected $table = 'kategori';

    protected function casts(): array
    {
        return [
            'aktif' => 'boolean',
        ];
    }

    public function produk(): HasMany
    {
        return $this->hasMany(Produk::class, 'kategori_id');
    }
}
