<?php
session_start();
include 'conexion.php';

// Seguridad: Solo adoptantes
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'adoptante') {
    header("Location: dashboard.php");
    exit();
}

$adoptante_id = $_SESSION['usuario_id'];

// Buscamos las solicitudes y AHORA incluimos el telefono_contacto de la base de datos
$sql = "SELECT s.id, s.estado, s.fecha_solicitud, s.telefono_contacto, m.nombre, m.foto 
        FROM solicitudes s 
        INNER JOIN mascotas m ON s.mascota_id = m.id 
        WHERE s.adoptante_id = '$adoptante_id' 
        ORDER BY s.fecha_solicitud DESC";
        
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Huellitas - Mis Solicitudes</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; margin: 0; padding: 20px; text-align: center; }
        h1 { color: #ff6b6b; margin-bottom: 5px;}
        
        .lista-solicitudes { display: flex; flex-direction: column; gap: 15px; align-items: center; margin-top: 20px; }
        .card-solicitud { background-color: white; width: 100%; max-width: 350px; border-radius: 12px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); display: flex; flex-direction: column; overflow: hidden; text-align: left; }
        .card-header { display: flex; align-items: center; }
        .card-header img { width: 100px; height: 100px; object-fit: cover; }
        .info { padding: 15px; flex-grow: 1; }
        .info h3 { margin: 0 0 5px 0; font-size: 18px; color: #333; }
        .fecha { font-size: 12px; color: #888; margin-bottom: 8px; display: block;}
        
        .estado { font-weight: bold; font-size: 14px; padding: 4px 8px; border-radius: 8px; display: inline-block; }
        .estado.pendiente { background-color: #fff3cd; color: #856404; }
        .estado.aceptada { background-color: #d4edda; color: #155724; }
        .estado.rechazada { background-color: #f8d7da; color: #721c24; }
        
        /* Estilos para la nueva cajita de contacto */
        .contacto-box { background-color: #e8f5e9; border-top: 1px solid #c3e6cb; padding: 12px 15px; font-size: 14px; color: #155724; }
        .contacto-box strong { color: #0f5132; }

        .back-link { display: inline-block; margin-top: 25px; color: #555; text-decoration: none; font-size: 14px; font-weight: bold;}
    </style>
</head>
<body>

    <h1>Mis Solicitudes 📄</h1>
    <p>Da seguimiento a tus procesos de adopción</p>

    <div class="lista-solicitudes">
        <?php
        if ($resultado->num_rows > 0) {
            while($row = $resultado->fetch_assoc()) {
                $imagen = !empty($row['foto']) ? $row['foto'] : 'https://via.placeholder.com/100?text=Sin+Foto';
                
                echo "<div class='card-solicitud'>";
                
                echo "<div class='card-header'>";
                echo "<img src='" . $imagen . "' alt='Foto de la mascota'>";
                echo "<div class='info'>";
                echo "<h3>" . $row['nombre'] . "</h3>";
                $fecha_bonita = date("d/m/Y", strtotime($row['fecha_solicitud']));
                echo "<span class='fecha'>Enviada el: " . $fecha_bonita . "</span>";
                
                $clase_estado = strtolower($row['estado']);
                echo "<span class='estado " . $clase_estado . "'>Estado: " . ucfirst($row['estado']) . "</span>";
                echo "</div>"; // Fin de info
                echo "</div>"; // Fin de card-header
                
                // MAGIA: Si la solicitud fue aceptada, mostramos la cajita con el teléfono de contacto
                if ($row['estado'] == 'aceptada' && !empty($row['telefono_contacto'])) {
                    echo "<div class='contacto-box'>";
                    echo "<strong>¡Felicidades! 🎉</strong><br>";
                    echo "Comunícate al: <strong>" . $row['telefono_contacto'] . "</strong> para continuar la adopción.";
                    echo "</div>";
                }
                
                echo "</div>"; // Fin de card-solicitud
            }
        } else {
            echo "<p>Aún no has enviado ninguna solicitud de adopción.</p>";
        }
        ?>
    </div>

    <a href="dashboard.php" class="back-link">← Volver al Menú Principal</a>

</body>
</html>