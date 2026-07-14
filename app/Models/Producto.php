<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';

    protected $fillable = [
        'nombre',
        'precio',
        'stock',
    ];

    // Relación con el nodo del árbol binario
    public function nodoArbol()
    {
        return $this->hasOne(ArbolProducto::class, 'producto_id');
    }
}