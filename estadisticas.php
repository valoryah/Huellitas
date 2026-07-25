<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'administrador') {
    header("Location: dashboard.php");
    exit();
}


$total_usuarios = $conn->query("SELECT COUNT(*) as total FROM usuarios")->fetch_assoc()['total'];
$total_adoptantes = $conn->query("SELECT COUNT(*) as total FROM usuarios WHERE rol='adoptante'")->fetch_assoc()['total'];
$total_publicadores = $conn->query("SELECT COUNT(*) as total FROM usuarios WHERE rol='publicador'")->fetch_assoc()['total'];


$total_mascotas = $conn->query("SELECT COUNT(*) as total FROM mascotas")->fetch_assoc()['total'];
$mascotas_disponibles = $conn->query("SELECT COUNT(*) as total FROM mascotas WHERE estado='disponible'")->fetch_assoc()['total'];
$mascotas_adoptadas = $conn->query("SELECT COUNT(*) as total FROM mascotas WHERE estado='adoptado'")->fetch_assoc()['total'];


$total_solicitudes = $conn->query("SELECT COUNT(*) as total FROM solicitudes")->fetch_assoc()['total'];
$solicitudes_aceptadas = $conn->query("SELECT COUNT(*) as total FROM solicitudes WHERE estado='aceptada'")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Huellitas - Estadísticas</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; margin: 0; padding: 20px; text-align: center; }
        h1 { color: #333; margin-bottom: 5px;}
        p { color: #666; margin-bottom: 25px;}
        
        .grid-estadisticas { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; max-width: 400px; margin: 0 auto; }
        
        .card-stat { background-color: white; padding: 20px 10px; border-radius: 12px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .card-stat h2 { margin: 0; font-size: 32px; color: #ff6b6b; }
        .card-stat span { font-size: 14px; font-weight: bold; color: #555; display: block; margin-top: 5px;}
        
        .card-full { grid-column: span 2; background-color: #333; color: white; }
        .card-full h2 { color: #ffc107; }
        .card-full span { color: #ddd; }

        .seccion-titulo { grid-column: span 2; text-align: left; font-size: 18px; color: #333; border-bottom: 2px solid #ddd; padding-bottom: 5px; margin-top: 15px;}

        .back-link { display: inline-block; margin-top: 30px; color: #555; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

    <h1>Métricas del Sistema 📊</h1>
    <p>Resumen en tiempo real de Huellitas</p>

    <div class="grid-estadisticas">
        <div class="seccion-titulo">Comunidad</div>
        <div class="card-stat card-full">
            <h2><?php echo $total_usuarios; ?></h2>
            <span>Usuarios Registrados 👥</span>
        </div>
        <div class="card-stat">
            <h2><?php echo $total_adoptantes; ?></h2>
            <span>Adoptantes 💖</span>
        </div>
        <div class="card-stat">
            <h2><?php echo $total_publicadores; ?></h2>
            <span>Refugios 🏠</span>
        </div>

        <div class="seccion-titulo">Peluditos</div>
        <div class="card-stat">
            <h2 style="color: #28a745;"><?php echo $mascotas_disponibles; ?></h2>
            <span>Buscando Hogar 🐕</span>
        </div>
        <div class="card-stat">
            <h2 style="color: #6c757d;"><?php echo $mascotas_adoptadas; ?></h2>
            <span>Adoptados ❤️</span>
        </div>

        <div class="seccion-titulo">Impacto</div>
        <div class="card-stat card-full" style="background-color: #ffeb3b; color: #333;">
            <h2 style="color: #d32f2f;"><?php echo $solicitudes_aceptadas; ?></h2>
            <span style="color: #333;">Familias Unidades (Adopciones Exitosas) 🎉</span>
        </div>
    </div>

    <a href="dashboard.php" class="back-link">← Volver al Menú Principal</a>

</body>
</html>