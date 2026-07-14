<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\ArbolProducto;
use App\Helpers\HeapSorter;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    // 1. Registrar producto e insertarlo en el Árbol Binario de Búsqueda (Punto 9)
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        // Guardar el producto en la tabla estándar
        $producto = Producto::create([
            'nombre' => $request->nombre,
            'precio' => $request->precio,
            'stock' => $request->stock,
        ]);

        // Insertar recursivamente en el Árbol Binario en PostgreSQL
        $this->insertarEnArbol($producto);

        return response()->json([
            'mensaje' => 'Producto creado e insertado en el Árbol Binario correctamente',
            'producto' => $producto
        ], 201);
    }

    // 2. Obtener productos ordenados por precio usando HEAPSORT (Punto 10)
    public function index()
    {
        // Obtenemos los productos sin ordenar de la BD
        $productos = Producto::all()->toArray();
        
        // Convertir a objetos para trabajar cómodamente en PHP
        $productosObjetos = json_decode(json_encode($productos));

        // Aplicamos nuestro algoritmo Heapsort personalizado
        HeapSorter::sort($productosObjetos);

        return response()->json($productosObjetos);
    }

    // 3. Ver la estructura actual del Árbol Binario (Para tu documentación / sustentación)
    public function verArbol()
    {
        $nodos = ArbolProducto::with('producto')->get();
        return response()->json($nodos);
    }

    // --- LÓGICA INTERNA DEL ÁRBOL BINARIO DE BÚSQUEDA ---
    private function insertarEnArbol(\App\Models\Producto $producto)
    {
        // Crear el nuevo nodo para este producto
        $nuevoNodo = ArbolProducto::create([
            'producto_id' => $producto->id,
            'izq_node_id' => null,
            'der_node_id' => null
        ]);

        // Buscar la raíz del árbol
        $raiz = ArbolProducto::whereNotExists(function ($query) {
            $query->select(\DB::raw(1))
                  ->from('arbol_productos as sub')
                  ->whereRaw('sub.izq_node_id = arbol_productos.id')
                  ->orWhereRaw('sub.der_node_id = arbol_productos.id');
        })->where('id', '!=', $nuevoNodo->id)->first();

        // Si no hay raíz, este nuevo nodo es la raíz principal
        if (!$raiz) {
            return;
        }

        // Si ya hay raíz, lo recorremos e insertamos recursivamente según el precio
        $this->recorrerEInsertar($raiz, $nuevoNodo, $producto->precio);
    }

    private function recorrerEInsertar(\App\Models\ArbolProducto $nodoActual, \App\Models\ArbolProducto $nuevoNodo, float $precioNuevo)
    {
        $productoActual = Producto::find($nodoActual->producto_id);

        if ($precioNuevo < $productoActual->precio) {
            // Ir a la izquierda
            if (is_null($nodoActual->izq_node_id)) {
                $nodoActual->izq_node_id = $nuevoNodo->id;
                $nodoActual->save();
            } else {
                $hijoIzq = ArbolProducto::find($nodoActual->izq_node_id);
                $this->recorrerEInsertar($hijoIzq, $nuevoNodo, $precioNuevo);
            }
        } else {
            // Ir a la derecha (Precios mayores o iguales)
            if (is_null($nodoActual->der_node_id)) {
                $nodoActual->der_node_id = $nuevoNodo->id;
                $nodoActual->save();
            } else {
                $hijoDer = ArbolProducto::find($nodoActual->der_node_id);
                $this->recorrerEInsertar($hijoDer, $nuevoNodo, $precioNuevo);
            }
        }
    }
}