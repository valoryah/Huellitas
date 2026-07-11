<?php 
include 'conexion.php'; 
$mensaje = "";

// Esta parte de PHP se ejecuta solo cuando el usuario presiona el botón "Registrarme"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encriptamos la contraseña por seguridad
    $rol = $_POST['rol'];

    // Preparamos la orden para guardar en MySQL
    $sql = "INSERT INTO usuarios (nombre, correo, password, rol) VALUES ('$nombre', '$correo', '$password', '$rol')";
    
    if ($conn->query($sql) === TRUE) {
        $mensaje = "<p style='color: green; font-weight: bold;'>¡Registro exitoso! Ya puedes iniciar sesión.</p>";
    } else {
        $mensaje = "<p style='color: red;'>Error al registrar: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Huellitas - Registro</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .app-container { background-color: white; width: 100%; max-width: 400px; padding: 30px; border-radius: 15px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); text-align: center; }
        h1 { color: #ff6b6b; margin-bottom: 5px; }
        input, select, button { width: 100%; padding: 12px; margin: 10px 0; border-radius: 8px; border: 1px solid #ddd; box-sizing: border-box; }
        button { background-color: #ff6b6b; color: white; border: none; font-weight: bold; font-size: 16px; cursor: pointer; }
        .back-link { display: block; margin-top: 15px; color: #555; text-decoration: none; font-size: 14px; }
    </style>
</head>
<body>

<div class="app-container">
    <h1>Crear Cuenta</h1>
    <p>Únete a Huellitas 🐾</p>
    
    <!-- Aquí mostramos el mensaje de éxito o error -->
    <?php echo $mensaje; ?>

    <form action="registro.php" method="POST">
        <input type="text" name="nombre" placeholder="Tu Nombre Completo" required>
        <input type="email" name="correo" placeholder="Tu Correo Electrónico" required>
        <input type="password" name="password" placeholder="Crea una Contraseña" required>
        
        <select name="rol" required>
            <option value="" disabled selected>¿Qué deseas hacer en la app?</option>
            <option value="adoptante">Quiero Adoptar</option>
            <option value="publicador">Quiero Dar en Adopción</option>
        </select>

        <button type="submit">Registrarme</button>
    </form>
    
    <a href="index.php" class="back-link">← Volver al Inicio de Sesión</a>
</div>

</body>
</html>