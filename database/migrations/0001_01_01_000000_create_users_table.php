<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tabla de Usuarios (Soporte para Punto 4: Login y Sesiones)
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('password'); // Contraseña encriptada
            $table->string('rol')->default('cliente'); // 'admin' o 'cliente'
            $table->timestamps();
        });

        // 2. Tabla de Productos (Catálogo)
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->decimal('precio', 10, 2);
            $table->integer('stock');
            $table->timestamps();
        });

        // 3. Tabla del Árbol Binario de Productos (Punto 9)
        // Almacenará la estructura de un Árbol Binario de Búsqueda directamente en PostgreSQL
        Schema::create('arbol_productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->unsignedBigInteger('izq_node_id')->nullable(); // Hijo izquierdo (Precios menores)
            $table->unsignedBigInteger('der_node_id')->nullable(); // Hijo derecho (Precios mayores)
            $table->timestamps();

            // Claves foráneas autoreferenciadas
            $table->foreign('izq_node_id')->references('id')->on('arbol_productos')->onDelete('set null');
            $table->foreign('der_node_id')->references('id')->on('arbol_productos')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arbol_productos');
        Schema::dropIfExists('productos');
        Schema::dropIfExists('usuarios');
    }
};