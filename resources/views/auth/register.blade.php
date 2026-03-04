<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; padding-top: 60px; background: #f3f4f6; }
        .card { background: white; padding: 2rem; border-radius: 8px; width: 360px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        input { width: 100%; padding: 8px; margin: 6px 0 14px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #4f46e5; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .error { color: red; font-size: 0.85rem; }
        a { color: #4f46e5; }
    </style>
</head>
<body>
<div class="card">
    <h2>Crear cuenta</h2>

    {{-- Mostrar errores de validación --}}
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <p class="error">{{ $error }}</p>
        @endforeach
    @endif

    <form method="POST" action="/register">
        @csrf  {{-- Token de seguridad OBLIGATORIO en formularios POST --}}

        <label>Nombre</label>
        <input type="text" name="name" value="{{ old('name') }}" required>

        <label>Correo electrónico</label>
        <input type="email" name="email" value="{{ old('email') }}" required>

        <label>Contraseña</label>
        <input type="password" name="password" required>

        <label>Confirmar contraseña</label>
        <input type="password" name="password_confirmation" required>

        <button type="submit">Registrarse</button>
    </form>

    <p style="text-align:center; margin-top:1rem">
        ¿Ya tienes cuenta? <a href="/login">Inicia sesión</a>
    </p>
</div>
</body>
</html>
