<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemVenda extends Model
{
    protected $table = 'itens_venda';

    protected $fillable = ['venda_id', 'produto_id', 'quantidade', 'subtotal'];

    protected $casts = [
        'subtotal' => 'float',
        'quantidade' => 'integer',
    ];

    public function venda()
    {
        return $this->belongsTo(Venda::class, 'venda_id');
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }
}
