 <?php
session_start();
include 'conexion.php';

// Seguridad: Solo adoptantes pueden entrar aquí
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'adoptante') {
    header("Location: dashboard.php");
    exit();
}

// Lógica para el filtro de especie
$filtro_especie = isset($_GET['especie']) ? $_GET['especie'] : '';
$sql = "SELECT * FROM mascotas WHERE estado = 'disponible'";

if ($filtro_especie != '') {
    $sql .= " AND especie = '$filtro_especie'";
}

$sql .= " ORDER BY fecha_publicacion DESC"; // Mostrar los más recientes primero
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Huellitas - Buscar Mascotas</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; margin: 0; padding: 20px; text-align: center; }
        h1 { color: #ff6b6b; margin-bottom: 5px;}
        .filtros { margin-bottom: 20px; }
        select, button { padding: 10px; border-radius: 8px; border: 1px solid #ddd; font-size: 14px; }
        button { background-color: #ff6b6b; color: white; border: none; font-weight: bold; cursor: pointer; }
        
        /* Estilos de las tarjetas simulando una app */
        .catalogo { display: flex; flex-direction: column; gap: 20px; align-items: center; }
        .card-mascota { background-color: white; width: 100%; max-width: 350px; border-radius: 15px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); overflow: hidden; text-align: left; }
        .card-mascota img { width: 100%; height: 250px; object-fit: cover; }
        .card-info { padding: 15px; }
        .card-info h2 { margin: 0 0 10px 0; color: #333; font-size: 22px; }
        .card-info p { margin: 5px 0; color: #666; font-size: 14px; }
        .tag { display: inline-block; background-color: #eee; padding: 3px 8px; border-radius: 10px; font-size: 12px; margin-right: 5px; color: #555; }
        
        .btn-adoptar { display: block; width: 100%; text-align: center; background-color: #ff6b6b; color: white; padding: 12px; text-decoration: none; font-weight: bold; margin-top: 15px; border-radius: 8px; box-sizing: border-box;}
        .back-link { display: inline-block; margin-top: 20px; color: #555; text-decoration: none; font-size: 14px; }
    </style>
</head>
<body>

    <h1>Adopta un Peludito 🐾</h1>
    <p>Encuentra a tu nuevo mejor amigo</p>

    <!-- Barra de Filtros -->
    <div class="filtros">
        <form action="buscar_mascotas.php" method="GET">
            <select name="especie">
                <option value="">Todas las especies</option>
                <option value="Perro" <?php if($filtro_especie == 'Perro') echo 'selected'; ?>>Perros</option>
                <option value="Gato" <?php if($filtro_especie == 'Gato') echo 'selected'; ?>>Gatos</option>
                <option value="Otro" <?php if($filtro_especie == 'Otro') echo 'selected'; ?>>Otros</option>
            </select>
            <button type="submit">Filtrar</button>
        </form>
    </div>

    <!-- Catálogo de Mascotas -->
    <div class="catalogo">
        <?php
        if ($resultado->num_rows > 0) {
            while($mascota = $resultado->fetch_assoc()) {
                // Verificar si tiene foto, si no, poner una imagen por defecto
                $imagen = !empty($mascota['foto']) ? $mascota['foto'] : 'https://via.placeholder.com/350x250?text=Sin+Foto';
                
                echo "<div class='card-mascota'>";
                echo "<img src='" . $imagen . "' alt='Foto de " . $mascota['nombre'] . "'>";
                echo "<div class='card-info'>";
                echo "<h2>" . $mascota['nombre'] . "</h2>";
                echo "<p><span class='tag'>" . $mascota['especie'] . "</span> <span class='tag'>" . $mascota['edad'] . "</span> <span class='tag'>" . $mascota['tamano'] . "</span></p>";
                echo "<p>📍 " . $mascota['ubicacion'] . "</p>";
                echo "<p><em>" . $mascota['descripcion'] . "</em></p>";
                
                // Este botón llevará más adelante a la solicitud
                echo "<a href='enviar_solicitud.php?id=" . $mascota['id'] . "' class='btn-adoptar'>¡Quiero Adoptarlo! ❤️</a>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p>No hay peluditos disponibles con este filtro en este momento.</p>";
        }
        ?>
    </div>

    <a href="dashboard.php" class="back-link">← Volver al Menú Principal</a>

</body>
</html>