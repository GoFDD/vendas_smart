<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    public function index()
    {
        return Marca::all();
    }

    public function store(Request $request)
    {
        if (! $request->nome) {
            return response()->json(['error' => 'Nome é obrigatório'], 400);
        }

        $marca = Marca::create(['nome' => $request->nome]);

        return response()->json($marca, 201);
    }

    public function show(Marca $marca)
    {
        return $marca;
    }

    public function update(Request $request, Marca $marca)
    {
        if (! $request->nome) {
            return response()->json(['error' => 'Nome é obrigatório'], 400);
        }

        $marca->update(['nome' => $request->nome]);

        return $marca;
    }

    public function destroy(Marca $marca)
    {
        $marca->delete();

        return response()->json(['message' => 'Marca deletada']);
    }
}
