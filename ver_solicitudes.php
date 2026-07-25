<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'publicador') {
    header("Location: dashboard.php");
    exit();
}

$publicador_id = $_SESSION['usuario_id'];
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $solicitud_id = $_POST['solicitud_id'];
    $nuevo_estado = $_POST['estado'];
    $telefono = isset($_POST['telefono_contacto']) ? $_POST['telefono_contacto'] : '';

    $update_sql = "UPDATE solicitudes SET estado = '$nuevo_estado', telefono_contacto = '$telefono' WHERE id = '$solicitud_id'";
    
    if ($conn->query($update_sql) === TRUE) {
        $mensaje = "<p style='color: green; font-weight: bold;'>¡Solicitud actualizada correctamente!</p>";
    } else {
        $mensaje = "<p style='color: red;'>Error al actualizar: " . $conn->error . "</p>";
    }
}

$sql = "SELECT s.id AS solicitud_id, s.estado, s.fecha_solicitud, m.nombre AS mascota_nombre, m.foto, u.nombre AS adoptante_nombre, u.correo 
        FROM solicitudes s 
        INNER JOIN mascotas m ON s.mascota_id = m.id 
        INNER JOIN usuarios u ON s.adoptante_id = u.id 
        WHERE m.publicador_id = '$publicador_id' 
        ORDER BY s.fecha_solicitud DESC";
        
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Huellitas - Solicitudes Recibidas</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; margin: 0; padding: 20px; text-align: center; }
        h1 { color: #ff6b6b; margin-bottom: 5px;}
        .contenedor { display: flex; flex-direction: column; gap: 20px; align-items: center; margin-top: 20px; }
        .card { background-color: white; width: 100%; max-width: 400px; border-radius: 12px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); padding: 20px; text-align: left; box-sizing: border-box;}
        .card img { width: 100%; height: 200px; object-fit: cover; border-radius: 8px; margin-bottom: 10px;}
        h3 { color: #333; margin: 0 0 10px 0; }
        p { margin: 5px 0; font-size: 14px; color: #555; }
        
        .formulario-respuesta { margin-top: 15px; background-color: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px dashed #ccc;}
        input[type="text"] { width: 100%; padding: 10px; margin-bottom: 10px; border-radius: 5px; border: 1px solid #ddd; box-sizing: border-box;}
        .btn-aceptar { background-color: #28a745; color: white; border: none; padding: 10px; border-radius: 5px; cursor: pointer; font-weight: bold; width: 48%; }
        .btn-rechazar { background-color: #dc3545; color: white; border: none; padding: 10px; border-radius: 5px; cursor: pointer; font-weight: bold; width: 48%; }
        
        .estado-etiqueta { display: inline-block; padding: 5px 10px; border-radius: 5px; font-weight: bold; margin-top: 10px;}
        .estado-etiqueta.aceptada { background-color: #d4edda; color: #155724; }
        .estado-etiqueta.rechazada { background-color: #f8d7da; color: #721c24; }
        
        .back-link { display: inline-block; margin-top: 25px; color: #555; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

    <h1>Solicitudes Recibidas 🔔</h1>
    <p>Personas interesadas en tus peluditos</p>
    <?php echo $mensaje; ?>

    <div class="contenedor">
        <?php
        if ($resultado->num_rows > 0) {
            while($row = $resultado->fetch_assoc()) {
                $imagen = !empty($row['foto']) ? $row['foto'] : 'https://via.placeholder.com/400x200?text=Sin+Foto';
                
                echo "<div class='card'>";
                echo "<img src='" . $imagen . "' alt='Mascota'>";
                echo "<h3>" . $row['adoptante_nombre'] . " quiere adoptar a " . $row['mascota_nombre'] . "</h3>";
                echo "<p>📧 Correo de contacto: " . $row['correo'] . "</p>";
                echo "<p>📅 Fecha: " . date("d/m/Y", strtotime($row['fecha_solicitud'])) . "</p>";
                
                if ($row['estado'] == 'pendiente') {
                    echo "<div class='formulario-respuesta'>";
                    echo "<p><strong>¿Aceptar solicitud?</strong> Deja tu número para que te contacten:</p>";
                    echo "<form method='POST' action='ver_solicitudes.php'>";
                    echo "<input type='hidden' name='solicitud_id' value='" . $row['solicitud_id'] . "'>";
                    echo "<input type='text' name='telefono_contacto' placeholder='Ej. Mi WhatsApp es 5512345678' required>";
                    echo "<div style='display: flex; justify-content: space-between;'>";
                    echo "<button type='submit' name='estado' value='aceptada' class='btn-aceptar'>Aceptar ✅</button>";
                    echo "<button type='submit' name='estado' value='rechazada' class='btn-rechazar' formnovalidate>Rechazar ❌</button>";
                    echo "</div>";
                    echo "</form>";
                    echo "</div>";
                } else {
                    $clase = strtolower($row['estado']);
                    echo "<div class='estado-etiqueta " . $clase . "'>Ya " . $row['estado'] . " esta solicitud</div>";
                }
                
                echo "</div>";
            }
        } else {
            echo "<p>Aún no has recibido solicitudes de adopción.</p>";
        }
        ?>
    </div>

    <a href="dashboard.php" class="back-link">← Volver al Menú Principal</a>

</body>
</html>