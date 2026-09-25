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

    public function store(ItemRequest $request, FotoController $fotoController)
    {
        $item = new Item;
        $item->fill($request->validated());
        $item->save();

        $fotoController->salvarFotos($request->input('fotos', []), $item);

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

    public function update(ItemRequest $request, Item $item, FotoController $fotoController)
    {
        $item->fill($request->validated());
        $item->save();

        $fotoController->salvarFotos($request->input('fotos', []), $item);

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
