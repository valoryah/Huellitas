<?php include 'conexion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Huellitas - Iniciar Sesión</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .app-container {
            background-color: white;
            width: 100%;
            max-width: 400px;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            text-align: center;
        }
        h1 { color: #ff6b6b; }
        input, select, button {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #ddd;
            box-sizing: border-box;
        }
        button {
            background-color: #ff6b6b;
            color: white;
            border: none;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="app-container">
    <h1>🐾 Huellitas</h1>
    <p>Inicia sesión en tu cuenta</p>
    
    <form action="login.php" method="POST">
        <input type="email" name="correo" placeholder="Tu Correo Electrónico" required>
        <input type="password" name="password" placeholder="Tu Contraseña" required>
        
        <select name="rol" required>
            <option value="" disabled selected>Selecciona tu rol...</option>
            <option value="adoptante">Adoptante</option>
            <option value="publicador">Publicador</option>
            <option value="administrador">Administrador</option>
        </select>

        <button type="submit">Entrar</button>
    </form>
    <p>¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
</div>

</body>
</html>