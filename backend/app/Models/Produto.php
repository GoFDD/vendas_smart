<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $table = 'produtos';

    protected $fillable = ['marca_id', 'nome_produto', 'descricao', 'preco'];

    public function marca()
    {
        return $this->belongsTo(Marca::class, 'marca_id');
    }

    public function itensVenda()
    {
        return $this->hasMany(ItemVenda::class, 'produto_id');
    }
}
