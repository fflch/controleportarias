<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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

    protected function base64(): Attribute
    {
        return Attribute::make(
            get: fn () => 'data:image/jpeg;base64,' . base64_encode(Storage::disk('fotos')->get($this->foto)),
        );
    }
}
