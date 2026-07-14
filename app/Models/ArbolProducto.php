<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArbolProducto extends Model
{
    protected $table = 'arbol_productos';

    protected $fillable = [
        'producto_id',
        'izq_node_id',
        'der_node_id',
    ];

    // Relación con el producto original
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    // Relaciones para navegar en el árbol binario
    public function hijoIzquierdo()
    {
        return $this->belongsTo(ArbolProducto::class, 'izq_node_id');
    }

    public function hijoDerecho()
    {
        return $this->belongsTo(ArbolProducto::class, 'der_node_id');
    }
}