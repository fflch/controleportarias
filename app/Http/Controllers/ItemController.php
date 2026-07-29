<?php

namespace App\Http\Controllers;

use App\Models\Foto;
use App\Models\Item;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\ItemRequest;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index()
    {
        $itens = Item::all();
        return view('itens.index', ['itens' => $itens]);
    }

    public function create()
    {
        return view('itens.create');
    }

    public function store(ItemRequest $request)
    {
        $item = new Item;
        $item->origem = $request->origem;
        $item->destino = $request->destino;
        $item->codpes = $request->codpes;
        $item->nome = $request->nome;
        $item->tipo_documento = $request->tipo_documento;
        $item->documento = $request->documento;
        $item->patrimonio = $request->patrimonio;
        $item->numero_serie = $request->numero_serie;
        $item->observacao = $request->observacao;
        $item->save();

        // salva fotos
        foreach ($request->input('fotos', []) as $fotoBase64) {
            if (empty($fotoBase64)) continue;
            if (strpos($fotoBase64, 'base64,') !== false) {
                $fotoBase64 = base64_decode(explode('base64,', $fotoBase64)[1]);
            }
            $nomeArquivo = Str::uuid() . '.jpg';
            Storage::disk('fotos')->put($nomeArquivo, $fotoBase64);
            Foto::create(['item_id' => $item->id, 'foto' => $nomeArquivo]);
        }

        return redirect('/itens')->with('alert-success', 'Item registrado com sucesso!');
    }

    public function show(Item $item)
    {
        $item->load('fotos');
        return view('itens.show', ['item' => $item]);
    }

    public function edit(Item $item)
    {
        $item->load('fotos');
        return view('itens.edit', ['item' => $item]);
    }

    public function update(ItemRequest $request, Item $item)
    {
        $item->origem = $request->origem;
        $item->destino = $request->destino;
        $item->codpes = $request->codpes;
        $item->nome = $request->nome;
        $item->tipo_documento = $request->tipo_documento;
        $item->documento = $request->documento;
        $item->patrimonio = $request->patrimonio;
        $item->numero_serie = $request->numero_serie;
        $item->observacao = $request->observacao;
        $item->save();

        // salva fotos
        foreach ($request->input('fotos', []) as $fotoBase64) {
            if (empty($fotoBase64)) continue;
            if (strpos($fotoBase64, 'base64,') !== false) {
                $fotoBase64 = base64_decode(explode('base64,', $fotoBase64)[1]);
            }
            $nomeArquivo = Str::uuid() . '.jpg';
            Storage::disk('fotos')->put($nomeArquivo, $fotoBase64);
            Foto::create(['item_id' => $item->id, 'foto' => $nomeArquivo]);
        }

        return redirect("/itens/{$item->id}")->with('alert-success', 'Registro editado com sucesso!');
    }

    

    public function destroy(Item $item)
    {
        foreach ($item->fotos as $foto) { 
            $foto->delete();
        }
        $item->delete();
        return redirect('/itens')->with('alert-success', 'Registro apagado com sucesso!');
    }
}
