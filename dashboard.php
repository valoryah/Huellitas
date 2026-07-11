<?php
session_start();
// Si no han iniciado sesión, los regresamos
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

$rol = $_SESSION['rol']; // Guardamos el rol en una variable más corta
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Huellitas - Menú Principal</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; text-align: center; margin: 0; padding-top: 30px; }
        .app-container { background-color: white; width: 90%; max-width: 400px; margin: auto; padding: 30px; border-radius: 15px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        h1 { color: #ff6b6b; font-size: 24px; margin-bottom: 5px;}
        .rol-tag { background-color: #eee; padding: 5px 10px; border-radius: 15px; font-size: 14px; color: #555; display: inline-block; margin-bottom: 20px;}
        
        /* Estilos para los botones del menú */
        .menu-opciones { display: flex; flex-direction: column; gap: 15px; margin-top: 20px; }
        .btn-menu { background-color: #f8f9fa; border: 2px solid #ff6b6b; color: #ff6b6b; padding: 15px; text-decoration: none; border-radius: 10px; font-weight: bold; font-size: 16px; transition: 0.3s; }
        .btn-menu:hover { background-color: #ff6b6b; color: white; }
        
        .btn-salir { display: inline-block; margin-top: 30px; color: #888; text-decoration: underline; font-size: 14px; }
    </style>
</head>
<body>

<div class="app-container">
    <h1>¡Hola, <?php echo $_SESSION['nombre']; ?>! 🐾</h1>
    <div class="rol-tag">Perfil: <?php echo ucfirst($rol); ?></div>
    
    <div class="menu-opciones">
        <?php if ($rol == 'adoptante'): ?>
            <!-- Menú exclusivo para Adoptantes -->
            <a href="buscar_mascotas.php" class="btn-menu">🔍 Buscar Mascotas</a>
            <a href="mis_solicitudes.php" class="btn-menu">📄 Mis Solicitudes de Adopción</a>
            
        <?php elseif ($rol == 'publicador'): ?>
            <!-- Menú exclusivo para Publicadores -->
            <a href="publicar_mascota.php" class="btn-menu">➕ Publicar Mascota</a>
            <a href="mis_publicaciones.php" class="btn-menu">📋 Mis Publicaciones</a>
            <a href="ver_solicitudes.php" class="btn-menu">🔔 Ver Solicitudes Recibidas</a>
            
        <?php elseif ($rol == 'administrador'): ?>
            <!-- Menú exclusivo para Administradores -->
            <a href="gestionar_usuarios.php" class="btn-menu">👥 Gestionar Usuarios</a>
            <a href="estadisticas.php" class="btn-menu">📊 Ver Estadísticas</a>
        <?php endif; ?>
    </div>

    <a href="logout.php" class="btn-salir">Cerrar Sesión</a>
</div>

</body>
</html>