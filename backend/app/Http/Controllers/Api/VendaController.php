<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Venda;
use Illuminate\Http\Request;

class VendaController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10); // Padrão: 10 por página
        $page = $request->input('page', 1); // Página atual, padrão 1

        $vendas = Venda::with('itensVenda.produto.marca')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json($vendas);
    }

    public function store(Request $request)
    {
        \Log::info('Dados recebidos na criação de venda:', $request->all());
        $data = $request->validate([
            'clienteId' => 'nullable|exists:clientes,id',
            'itens' => 'required|array',
            'itens.*.produtoId' => 'required|exists:produtos,id',
            'itens.*.quantidade' => 'required|integer|min:1',
        ]);

        $venda = new Venda;
        $venda->cliente_id = $data['clienteId'];
        $venda->total = 0; // Define total como 0 antes do save
        $venda->save();

        $total = 0;
        foreach ($data['itens'] as $itemData) {
            $produto = \App\Models\Produto::find($itemData['produtoId']);
            if (! $produto) {
                return response()->json(['error' => 'Produto não encontrado'], 404);
            }
            $subtotal = $produto->preco * $itemData['quantidade'];
            $total += $subtotal;

            $item = new \App\Models\ItemVenda;
            $item->venda_id = $venda->id;
            $item->produto_id = $itemData['produtoId'];
            $item->quantidade = $itemData['quantidade'];
            $item->subtotal = $subtotal;
            $item->save();
        }

        $venda->total = $total; // Atualiza total com o valor calculado
        $venda->save();

        return response()->json($venda->load('itensVenda.produto.marca'), 201);
    }

    public function show(Venda $venda)
    {

        if ($venda->cliente_id !== null && $venda->cliente_id !== auth()->id()) {
            return response()->json(['error' => 'Acesso não autorizado'], 403);
        }

        return $venda->load('itensVenda.produto');
    }
}
