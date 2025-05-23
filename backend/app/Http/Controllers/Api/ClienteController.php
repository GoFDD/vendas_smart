<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        return Cliente::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:clientes,email',
            'telefone' => 'nullable|string|max:20',
            'cpf_cnpj' => 'required|unique:clientes,cpf_cnpj',
            'tipo_cliente' => 'required|in:pessoa_fisica,empresa',
            'endereco' => 'nullable|string',
        ]);

        $cliente = Cliente::create($validated);

        return response()->json($cliente, 201);
    }

    public function show(Cliente $cliente)
    {
        return $cliente;
    }

    public function update(Request $request, Cliente $cliente)
    {
        $validated = $request->validate([
            'nome' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:clientes,email,'.$cliente->id,
            'telefone' => 'nullable|string|max:20',
            'cpf_cnpj' => 'sometimes|unique:clientes,cpf_cnpj,'.$cliente->id,
            'tipo_cliente' => 'sometimes|in:pessoa_fisica,empresa',
            'endereco' => 'nullable|string',
        ]);

        $cliente->update($validated);

        return $cliente;
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();

        return response()->json(['message' => 'Cliente deletado']);
    }

    public function vendas(Cliente $cliente)
    {
        $vendas = $cliente->vendas()->with('itens.produto')->get();

        return response()->json($vendas);
    }
}
