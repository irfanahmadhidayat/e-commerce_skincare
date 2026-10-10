<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['nama_toko', 'kontak', 'alamat', 'deskripsi', 'rekening'])]
class DataToko extends Model
{
    protected $table = 'data_toko';

    protected function casts(): array
    {
        return [
            'rekening' => 'array',
        ];
    }
}
