<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda Virtual</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">

    <nav class="bg-indigo-600 text-white p-4 shadow-md flex justify-between items-center">
        <h1 class="text-xl font-bold">🛒 Mi Tienda Autónoma</h1>
        <div class="space-x-4">
            <a href="/dashboard" class="hover:underline font-medium">Ir al Dashboard</a>
            <button id="logout-btn" class="bg-red-500 hover:bg-red-600 px-3 py-1 rounded transition text-sm">Cerrar Sesión</button>
        </div>
    </nav>

    <main class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Catálogo de Productos</h2>
                <p class="text-sm text-gray-600">
                    ✨ Ordenados dinámicamente en el Backend usando el algoritmo <span class="font-bold text-indigo-600">Heapsort</span>.
                </p>
            </div>
            <button onclick="cargarProductos()" class="bg-indigo-100 text-indigo-700 font-semibold px-4 py-2 rounded-md hover:bg-indigo-200 transition">
                🔄 Actualizar Catálogo
            </button>
        </div>

        <div id="products-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            </div>
    </main>

    <script>
        // Proteger la vista: Si no hay usuario, redirigir al login
        if (!sessionStorage.getItem('usuario')) {
            window.location.href = '/';
        }

        const productsGrid = document.getElementById('products-grid');

        // Consumir la API REST de manera desacoplada
        async function cargarProductos() {
            try {
                productsGrid.innerHTML = `
                    <div class="col-span-full text-center py-12 text-gray-500">
                        Cargando productos ordenados por Heapsort...
                    </div>
                `;

                const response = await fetch('/api/productos');
                const productos = await response.json();

                productsGrid.innerHTML = '';

                if (productos.length === 0) {
                    productsGrid.innerHTML = `
                        <div class="col-span-full text-center py-12 text-gray-500 bg-white rounded-lg shadow-sm border">
                            No hay productos disponibles en este momento. Ve al Dashboard para agregar algunos.
                        </div>
                    `;
                    return;
                }

                // Renderizar las tarjetas de productos
                productos.forEach(producto => {
                    const card = document.createElement('div');
                    card.className = "bg-white rounded-lg shadow-md overflow-hidden border border-gray-100 flex flex-col justify-between p-4 hover:shadow-lg transition";
                    card.innerHTML = `
                        <div>
                            <div class="bg-indigo-50 text-indigo-700 text-xs font-bold px-2 py-1 rounded w-max mb-2">
                                ID: ${producto.id}
                            </div>
                            <h3 class="text-lg font-bold text-gray-800 mb-1">${producto.nombre}</h3>
                            <p class="text-2xl font-black text-gray-900 mb-4">$${parseFloat(producto.precio).toFixed(2)}</p>
                        </div>
                        <div>
                            <div class="flex justify-between items-center text-sm text-gray-600 mb-4">
                                <span>Disponibles:</span>
                                <span class="font-bold ${producto.stock > 0 ? 'text-green-600' : 'text-red-600'}">${producto.stock} u.</span>
                            </div>
                            <button class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700 transition font-medium ${producto.stock === 0 ? 'opacity-50 cursor-not-allowed' : ''}" ${producto.stock === 0 ? 'disabled' : ''}>
                                ${producto.stock > 0 ? 'Comprar ahora' : 'Agotado'}
                            </button>
                        </div>
                    `;
                    productsGrid.appendChild(card);
                });

            } catch (error) {
                console.error('Error al cargar productos:', error);
                productsGrid.innerHTML = `
                    <div class="col-span-full text-center py-12 text-red-500 font-medium">
                        Ocurrió un error al comunicarse con la API de la Tienda.
                    </div>
                `;
            }
        }

        // Evento de Logout
        document.getElementById('logout-btn').addEventListener('click', () => {
            sessionStorage.clear();
            window.location.href = '/';
        });

        // Cargar el catálogo al abrir la página
        cargarProductos();
    </script>
</body>
</html>