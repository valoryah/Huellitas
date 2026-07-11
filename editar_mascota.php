<?php
session_start();
include 'conexion.php';

// Seguridad
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'publicador') {
    header("Location: dashboard.php");
    exit();
}

$publicador_id = $_SESSION['usuario_id'];
$mensaje = "";

// 1. Si enviaron el formulario para actualizar los datos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mascota_id = $_POST['mascota_id'];
    $nombre = $_POST['nombre'];
    $especie = $_POST['especie'];
    $edad = $_POST['edad'];
    $tamano = $_POST['tamano'];
    $ubicacion = $_POST['ubicacion'];
    $descripcion = $_POST['descripcion'];

    $sql_update = "UPDATE mascotas SET nombre='$nombre', especie='$especie', edad='$edad', tamano='$tamano', ubicacion='$ubicacion', descripcion='$descripcion' WHERE id='$mascota_id' AND publicador_id='$publicador_id'";
    
    if ($conn->query($sql_update) === TRUE) {
        $mensaje = "<p style='color: green; font-weight: bold;'>¡Datos actualizados correctamente! ✅</p>";
    } else {
        $mensaje = "<p style='color: red;'>Error al actualizar: " . $conn->error . "</p>";
    }
}

// 2. Cargar los datos actuales de la mascota para rellenar el formulario
$mascota = null;
if (isset($_GET['id'])) {
    $mascota_id = $_GET['id'];
    $sql_select = "SELECT * FROM mascotas WHERE id='$mascota_id' AND publicador_id='$publicador_id'";
    $resultado = $conn->query($sql_select);
    
    if ($resultado->num_rows > 0) {
        $mascota = $resultado->fetch_assoc();
    } else {
        echo "<script>alert('Mascota no encontrada o no tienes permiso.'); window.location.href='mis_publicaciones.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Huellitas - Editar Mascota</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; padding: 20px; box-sizing: border-box;}
        .app-container { background-color: white; width: 100%; max-width: 400px; padding: 30px; border-radius: 15px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); text-align: center; }
        h1 { color: #ffc107; margin-bottom: 5px; }
        input, select, textarea, button { width: 100%; padding: 12px; margin: 10px 0; border-radius: 8px; border: 1px solid #ddd; box-sizing: border-box; font-family: Arial, sans-serif;}
        textarea { resize: vertical; min-height: 80px; }
        button { background-color: #ffc107; color: #333; border: none; font-weight: bold; font-size: 16px; cursor: pointer; }
        .back-link { display: block; margin-top: 15px; color: #555; text-decoration: none; font-size: 14px; }
    </style>
</head>
<body>

<div class="app-container">
    <h1>Editar Publicación ✏️</h1>
    <p>Actualiza los datos de <?php echo $mascota['nombre']; ?></p>
    
    <?php echo $mensaje; ?>

    <form action="editar_mascota.php?id=<?php echo $mascota['id']; ?>" method="POST">
        <!-- Input oculto para enviar el ID de la mascota sin que el usuario lo vea -->
        <input type="hidden" name="mascota_id" value="<?php echo $mascota['id']; ?>">
        
        <input type="text" name="nombre" value="<?php echo $mascota['nombre']; ?>" required>
        
        <select name="especie" required>
            <option value="Perro" <?php if($mascota['especie'] == 'Perro') echo 'selected'; ?>>Perro</option>
            <option value="Gato" <?php if($mascota['especie'] == 'Gato') echo 'selected'; ?>>Gato</option>
            <option value="Otro" <?php if($mascota['especie'] == 'Otro') echo 'selected'; ?>>Otro</option>
        </select>

        <select name="edad" required>
            <option value="Cachorro" <?php if($mascota['edad'] == 'Cachorro') echo 'selected'; ?>>Cachorro (0-1 año)</option>
            <option value="Adulto" <?php if($mascota['edad'] == 'Adulto') echo 'selected'; ?>>Adulto (1-7 años)</option>
            <option value="Senior" <?php if($mascota['edad'] == 'Senior') echo 'selected'; ?>>Senior (+7 años)</option>
        </select>

        <select name="tamano" required>
            <option value="Pequeño" <?php if($mascota['tamano'] == 'Pequeño') echo 'selected'; ?>>Pequeño</option>
            <option value="Mediano" <?php if($mascota['tamano'] == 'Mediano') echo 'selected'; ?>>Mediano</option>
            <option value="Grande" <?php if($mascota['tamano'] == 'Grande') echo 'selected'; ?>>Grande</option>
        </select>

        <input type="text" name="ubicacion" value="<?php echo $mascota['ubicacion']; ?>" required>
        
        <textarea name="descripcion" required><?php echo $mascota['descripcion']; ?></textarea>

        <button type="submit">Guardar Cambios</button>
    </form>
    
    <a href="mis_publicaciones.php" class="back-link">← Volver a Mis Publicaciones</a>
</div>

</body>
</html>