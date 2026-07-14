<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda - Acceso</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h2 id="form-title" class="text-2xl font-bold mb-6 text-center text-gray-800">Iniciar Sesión</h2>
        
        <form id="auth-form" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Usuario</label>
                <input type="text" id="username" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Contraseña</label>
                <input type="password" id="password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            
            <button type="submit" id="submit-btn" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 transition">Ingresar</button>
        </form>

        <div class="mt-4 text-center">
            <button id="toggle-form" class="text-sm text-indigo-600 hover:underline">¿No tienes cuenta? Regístrate aquí</button>
        </div>
    </div>

    <script>
        const authForm = document.getElementById('auth-form');
        const formTitle = document.getElementById('form-title');
        const submitBtn = document.getElementById('submit-btn');
        const toggleForm = document.getElementById('toggle-form');
        
        let isLogin = true;

        // Alternar entre Login y Registro en la interfaz
        toggleForm.addEventListener('click', () => {
            isLogin = !isLogin;
            formTitle.textContent = isLogin ? 'Iniciar Sesión' : 'Registrarse';
            submitBtn.textContent = isLogin ? 'Ingresar' : 'Crear Cuenta';
            toggleForm.textContent = isLogin ? '¿No tienes cuenta? Regístrate aquí' : '¿Ya tienes cuenta? Inicia sesión';
        });

        // Enviar datos al Backend de manera Desacoplada (Fetch API)
        authForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;

            const endpoint = isLogin ? '/api/login' : '/api/registro';

            try {
                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ username, password })
                });

                const data = await response.json();

                if (response.ok) {
                    alert(isLogin ? '¡Bienvenido!' : 'Registro exitoso. Ahora puedes iniciar sesión.');
                    if (isLogin) {
                        // Guardamos el usuario en SessionStorage para simular sesión en JS
                        sessionStorage.setItem('usuario', JSON.stringify(data.usuario));
                        // Redireccionamos a la tienda
                        window.location.href = '/tienda';
                    } else {
                        toggleForm.click();
                    }
                } else {
                    alert(data.error || data.message || 'Ocurrió un error');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('No se pudo conectar con el servidor.');
            }
        });
    </script>
</body>
</html>
