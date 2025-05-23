<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $fillable = ['nome', 'email', 'telefone', 'cpf_cnpj', 'tipo_cliente', 'endereco', 'data_cadastro'];

    public function vendas()
    {
        return $this->hasMany(Venda::class, 'cliente_id');
    }
}
