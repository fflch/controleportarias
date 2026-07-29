<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Foto extends Model
{
    protected $fillable = ['item_id', 'foto'];

    protected static function booted()
    {
        static::deleting(function (Foto $foto) {
            Storage::disk('fotos')->delete($foto->foto);
        });
    }
}
