<?php
session_start();
include 'conexion.php';

// Seguridad: Solo publicadores
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'publicador') {
    header("Location: dashboard.php");
    exit();
}

$publicador_id = $_SESSION['usuario_id'];
$mensaje = "";

// 1. Lógica para ELIMINAR la publicación
if (isset($_GET['eliminar_id'])) {
    $eliminar_id = $_GET['eliminar_id'];
    $sql_delete = "DELETE FROM mascotas WHERE id = '$eliminar_id' AND publicador_id = '$publicador_id'";
    if ($conn->query($sql_delete) === TRUE) {
        $mensaje = "<p style='color: green; font-weight: bold;'>¡Publicación eliminada para siempre! 🗑️</p>";
    } else {
        $mensaje = "<p style='color: red;'>Error al eliminar: " . $conn->error . "</p>";
    }
}

// 2. Lógica para cambiar el ESTADO (Disponible / Adoptado)
if (isset($_GET['cambiar_estado']) && isset($_GET['id'])) {
    $mascota_id = $_GET['id'];
    $nuevo_estado = $_GET['cambiar_estado'];
    $sql_update = "UPDATE mascotas SET estado = '$nuevo_estado' WHERE id = '$mascota_id' AND publicador_id = '$publicador_id'";
    if ($conn->query($sql_update) === TRUE) {
        $mensaje = "<p style='color: green; font-weight: bold;'>¡Estado actualizado con éxito! 🐾</p>";
    }
}

// Buscar todas las mascotas de este usuario
$sql = "SELECT * FROM mascotas WHERE publicador_id = '$publicador_id' ORDER BY fecha_publicacion DESC";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Huellitas - Mis Publicaciones</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; margin: 0; padding: 20px; text-align: center; }
        h1 { color: #ff6b6b; margin-bottom: 5px;}
        .contenedor { display: flex; flex-direction: column; gap: 20px; align-items: center; margin-top: 20px; }
        .card-mascota { background-color: white; width: 100%; max-width: 350px; border-radius: 12px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); overflow: hidden; text-align: left; }
        .card-mascota img { width: 100%; height: 200px; object-fit: cover; }
        .info { padding: 15px; }
        .info h3 { margin: 0 0 10px 0; font-size: 20px; color: #333; }
        
        .estado-actual { font-weight: bold; padding: 5px 10px; border-radius: 8px; font-size: 14px; display: inline-block; margin-bottom: 15px;}
        .estado-actual.disponible { background-color: #d4edda; color: #155724; }
        .estado-actual.adoptado { background-color: #e2e3e5; color: #383d41; }
        
        .btn-accion { display: block; width: 100%; text-align: center; padding: 10px; text-decoration: none; font-weight: bold; border-radius: 5px; box-sizing: border-box; margin-bottom: 8px; font-size: 14px;}
        .btn-marcar-adoptado { background-color: #6c757d; color: white; }
        .btn-marcar-disponible { background-color: #28a745; color: white; }
        
        /* Nuevos botones de Editar y Eliminar */
        .fila-botones { display: flex; gap: 10px; }
        .btn-editar { background-color: #ffc107; color: #333; width: 50%; }
        .btn-eliminar { background-color: #dc3545; color: white; width: 50%; }
        
        .back-link { display: inline-block; margin-top: 25px; color: #555; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

    <h1>Mis Publicaciones 📋</h1>
    <p>Gestiona a los peluditos que has subido</p>
    <?php echo $mensaje; ?>

    <div class="contenedor">
        <?php
        if ($resultado->num_rows > 0) {
            while($row = $resultado->fetch_assoc()) {
                $imagen = !empty($row['foto']) ? $row['foto'] : 'https://via.placeholder.com/350x200?text=Sin+Foto';
                
                echo "<div class='card-mascota'>";
                echo "<img src='" . $imagen . "' alt='Foto'>";
                echo "<div class='info'>";
                echo "<h3>" . $row['nombre'] . "</h3>";
                
                $clase_estado = $row['estado'];
                echo "<div class='estado-actual " . $clase_estado . "'>Estado: " . ucfirst($row['estado']) . "</div>";
                
                if ($row['estado'] == 'disponible') {
                    echo "<a href='mis_publicaciones.php?cambiar_estado=adoptado&id=" . $row['id'] . "' class='btn-accion btn-marcar-adoptado'>Marcar como Adoptado 🏠</a>";
                } else {
                    echo "<a href='mis_publicaciones.php?cambiar_estado=disponible&id=" . $row['id'] . "' class='btn-accion btn-marcar-disponible'>Volver a poner Disponible 🐾</a>";
                }
                
                // Agregamos los nuevos botones de Editar y Eliminar en una fila
                echo "<div class='fila-botones'>";
                echo "<a href='editar_mascota.php?id=" . $row['id'] . "' class='btn-accion btn-editar'>Editar ✏️</a>";
                // El onclick lanza una alerta de confirmación en JavaScript antes de borrar
                echo "<a href='mis_publicaciones.php?eliminar_id=" . $row['id'] . "' class='btn-accion btn-eliminar' onclick='return confirm(\"¿Estás seguro de que deseas eliminar esta publicación?\")'>Eliminar 🗑️</a>";
                echo "</div>";
                
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p>No has publicado ninguna mascota todavía.</p>";
        }
        ?>
    </div>

    <a href="dashboard.php" class="back-link">← Volver al Menú Principal</a>

</body>
</html>