<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'adoptante') {
    header("Location: dashboard.php");
    exit();
}

$adoptante_id = $_SESSION['usuario_id'];
$mascota_id = isset($_GET['id']) ? $_GET['id'] : 0;
$mensaje = "";

if ($mascota_id > 0) {
    
    $check_sql = "SELECT * FROM solicitudes WHERE adoptante_id = '$adoptante_id' AND mascota_id = '$mascota_id'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        $mensaje = "<p style='color: #ff9800; font-weight: bold;'>Ya has enviado una solicitud para este peludito anteriormente. 🐾 ¡El refugio la está revisando!</p>";
    } else {

        $sql = "INSERT INTO solicitudes (adoptante_id, mascota_id) VALUES ('$adoptante_id', '$mascota_id')";
        
        if ($conn->query($sql) === TRUE) {
            $mensaje = "<p style='color: green; font-weight: bold;'>¡Solicitud enviada con éxito! ❤️ El publicador será notificado pronto.</p>";
        } else {
            $mensaje = "<p style='color: red;'>Error al enviar solicitud: " . $conn->error . "</p>";
        }
    }
} else {
    $mensaje = "<p style='color: red;'>Mascota no válida o no encontrada.</p>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Huellitas - Estado de Solicitud</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .app-container { background-color: white; width: 100%; max-width: 400px; padding: 30px; border-radius: 15px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); text-align: center; }
        h1 { color: #ff6b6b; margin-bottom: 20px; }
        .btn-volver { display: inline-block; background-color: #ff6b6b; color: white; padding: 12px 20px; text-decoration: none; font-weight: bold; margin-top: 20px; border-radius: 8px; }
        .back-link { display: block; margin-top: 15px; color: #555; text-decoration: underline; font-size: 14px; }
    </style>
</head>
<body>

<div class="app-container">
    <h1>Estado de tu Solicitud</h1>
    
    <?php echo $mensaje; ?>

    <a href="buscar_mascotas.php" class="btn-volver">Seguir buscando mascotas</a>
    <a href="dashboard.php" class="back-link">Ir al Menú Principal</a>
</div>

</body>
</html>