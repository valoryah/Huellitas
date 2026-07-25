<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'publicador') {
    header("Location: dashboard.php");
    exit();
}

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $publicador_id = $_SESSION['usuario_id'];
    $nombre = $_POST['nombre'];
    $especie = $_POST['especie'];
    $edad = $_POST['edad'];
    $tamano = $_POST['tamano'];
    $ubicacion = $_POST['ubicacion'];
    $descripcion = $_POST['descripcion'];
    
    $ruta_foto = ""; 
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $carpeta_destino = "uploads/";
        $nombre_archivo = time() . "_" . basename($_FILES["foto"]["name"]);
        $ruta_foto = $carpeta_destino . $nombre_archivo;
        
        move_uploaded_file($_FILES["foto"]["tmp_name"], $ruta_foto);
    }

    $sql = "INSERT INTO mascotas (publicador_id, nombre, especie, edad, tamano, ubicacion, descripcion, foto) 
            VALUES ('$publicador_id', '$nombre', '$especie', '$edad', '$tamano', '$ubicacion', '$descripcion', '$ruta_foto')";
    
    if ($conn->query($sql) === TRUE) {
        $mensaje = "<p style='color: green; font-weight: bold;'>¡Mascota publicada con foto exitosamente! 🐾</p>";
    } else {
        $mensaje = "<p style='color: red;'>Error: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Huellitas - Publicar Mascota</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; padding: 20px; box-sizing: border-box;}
        .app-container { background-color: white; width: 100%; max-width: 400px; padding: 30px; border-radius: 15px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); text-align: center; }
        h1 { color: #ff6b6b; margin-bottom: 5px; }
        input, select, textarea, button { width: 100%; padding: 12px; margin: 10px 0; border-radius: 8px; border: 1px solid #ddd; box-sizing: border-box; font-family: Arial, sans-serif;}
        textarea { resize: vertical; min-height: 80px; }
        .file-label { text-align: left; display: block; margin-top: 10px; color: #555; font-weight: bold; font-size: 14px;}
        input[type="file"] { padding: 10px; background-color: #f8f9fa; cursor: pointer; }
        button { background-color: #ff6b6b; color: white; border: none; font-weight: bold; font-size: 16px; cursor: pointer; }
        .back-link { display: block; margin-top: 15px; color: #555; text-decoration: none; font-size: 14px; }
    </style>
</head>
<body>

<div class="app-container">
    <h1>Dar en Adopción</h1>
    <p>Sube los datos y foto del peludito</p>
    
    <?php echo $mensaje; ?>

    <form action="publicar_mascota.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="nombre" placeholder="Nombre de la mascota" required>
        
        <select name="especie" required>
            <option value="" disabled selected>Especie...</option>
            <option value="Perro">Perro</option>
            <option value="Gato">Gato</option>
            <option value="Otro">Otro</option>
        </select>

        <select name="edad" required>
            <option value="" disabled selected>Edad aproximada...</option>
            <option value="Cachorro">Cachorro (0-1 año)</option>
            <option value="Adulto">Adulto (1-7 años)</option>
            <option value="Senior">Senior (+7 años)</option>
        </select>

        <select name="tamano" required>
            <option value="" disabled selected>Tamaño...</option>
            <option value="Pequeño">Pequeño</option>
            <option value="Mediano">Mediano</option>
            <option value="Grande">Grande</option>
        </select>

        <input type="text" name="ubicacion" placeholder="Ubicación (Ej. Ciudad de México)" required>
        
        <textarea name="descripcion" placeholder="Cuéntanos un poco sobre su personalidad..." required></textarea>

        <label class="file-label">Foto del peludito (Obligatorio):</label>
        <input type="file" name="foto" accept="image/*" required>

        <button type="submit">Publicar Mascota</button>
    </form>
    
    <a href="dashboard.php" class="back-link">← Volver al Menú Principal</a>
</div>

</body>
</html>