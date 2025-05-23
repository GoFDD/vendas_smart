<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function index()
    {
        return Produto::with('marca')->get();
    }

    public function store(Request $request)
    {
        if (! $request->marca_id || ! $request->descricao || ! $request->preco) {
            return response()->json(['error' => 'Campos obrigatórios ausentes'], 400);
        }

        $produto = Produto::create([
            'marca_id' => $request->marca_id,
            'nome_produto' => $request->nome_produto,
            'descricao' => $request->descricao,
            'preco' => $request->preco,
        ]);

        return response()->json($produto, 201);
    }

    public function show(Produto $produto)
    {
        return $produto->load('marca');
    }

    public function update(Request $request, Produto $produto)
    {
        $data = [];
        if ($request->marca_id) {
            $data['marca_id'] = $request->marca_id;
        }
        if ($request->nome_produto) {
            $data['nome_produto'] = $request->nome_produto;
        }
        if ($request->descricao) {
            $data['descricao'] = $request->descricao;
        }
        if ($request->preco) {
            $data['preco'] = $request->preco;
        }

        if (empty($data)) {
            return response()->json(['error' => 'Nenhum dado para atualizar'], 400);
        }

        $produto->update($data);

        return $produto;
    }

    public function destroy(Produto $produto)
    {
        $produto->delete();

        return response()->json(['message' => 'Produto deletado']);
    }
}
