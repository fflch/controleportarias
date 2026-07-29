<?php

namespace App\Http\Controllers;

use App\Models\Foto;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FotoController extends Controller
{
    public function show(Foto $foto)
    {
        $foto = Storage::disk('fotos')->get($foto->foto);
        return response($foto)->header('Content-Type', 'image/jpeg');
    }

    public function store(Request $request, Item $item)
    {
        $fotosData = $request->input('fotos', []);

        foreach ($fotosData as $fotoBase64) {
            if (empty($fotoBase64)) continue;

            if (strpos($fotoBase64, 'base64,') !== false) {
                $fotoBase64 = base64_decode(explode('base64,', $fotoBase64)[1]);
            }

            $nomeArquivo = Str::uuid() . '.jpg';
            Storage::disk('fotos')->put($nomeArquivo, $fotoBase64);

            Foto::create([
                'item_id' => $item->id,
                'foto' => $nomeArquivo,
            ]);
        }

        return back()->with('alert-success', 'Fotos salvas com sucesso!');
    }

    public function destroy(Foto $foto)
    {
        $item = $foto->item_id;
        $foto->delete();
        return back()->with('alert-success', 'Foto removida com sucesso!');
    }
}
