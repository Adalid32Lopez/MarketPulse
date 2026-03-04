<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        body { font-family: sans-serif; padding: 40px; background: #f3f4f6; }
        .card { background: white; padding: 2rem; border-radius: 8px; max-width: 500px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        button { padding: 8px 16px; background: #ef4444; color: white; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
<div class="card">
    <h2>¡Bienvenido, {{ Auth::user()->name }}! 👋</h2>
    <p>Has iniciado sesión correctamente con: <strong>{{ Auth::user()->email }}</strong></p>

    <form method="POST" action="/logout">
        @csrf
        <button type="submit">Cerrar sesión</button>
    </form>
</div>
</body>
</html>
