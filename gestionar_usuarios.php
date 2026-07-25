<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'administrador') {
    header("Location: dashboard.php");
    exit();
}

$mensaje = "";

if (isset($_GET['bloquear_id'])) {
    $id = $_GET['bloquear_id'];
    if ($id != $_SESSION['usuario_id']) {
        $conn->query("UPDATE usuarios SET estado_cuenta = 'bloqueado' WHERE id = '$id'");
        $mensaje = "<p style='color: #856404; background-color: #fff3cd; padding: 10px; border-radius: 5px;'>Usuario suspendido. Ya no podrá iniciar sesión. 🚫</p>";
    }
}
if (isset($_GET['activar_id'])) {
    $id = $_GET['activar_id'];
    $conn->query("UPDATE usuarios SET estado_cuenta = 'activo' WHERE id = '$id'");
    $mensaje = "<p style='color: #155724; background-color: #d4edda; padding: 10px; border-radius: 5px;'>Usuario reactivado exitosamente. ✅</p>";
}

if (isset($_GET['eliminar_id'])) {
    $id = $_GET['eliminar_id'];
    if ($id != $_SESSION['usuario_id']) {
        $conn->query("DELETE FROM usuarios WHERE id = '$id'");
        $mensaje = "<p style='color: #721c24; background-color: #f8d7da; padding: 10px; border-radius: 5px;'>Usuario y todo su contenido eliminado para siempre. 🗑️</p>";
    }
}


$sql = "SELECT * FROM usuarios ORDER BY fecha_registro DESC";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Huellitas - Panel de Control</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; margin: 0; padding: 20px; text-align: center; }
        h1 { color: #333; margin-bottom: 5px;}
        .contenedor { display: flex; flex-direction: column; gap: 15px; align-items: center; margin-top: 20px; }
        
        .card-usuario { background-color: white; width: 100%; max-width: 400px; border-radius: 12px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); padding: 15px; text-align: left; box-sizing: border-box; border-left: 5px solid #ccc;}
        .card-usuario.activo { border-left-color: #28a745; }
        .card-usuario.bloqueado { border-left-color: #dc3545; background-color: #f8f9fa;}
        .card-usuario.administrador { border-left-color: #007bff; }
        
        h3 { margin: 0 0 5px 0; color: #333; }
        p { margin: 5px 0; font-size: 14px; color: #666; }
        .tag-rol { font-weight: bold; font-size: 12px; background: #eee; padding: 3px 8px; border-radius: 10px; color: #333;}
        
        .fila-botones { display: flex; gap: 10px; margin-top: 15px; }
        .btn { flex: 1; text-align: center; padding: 8px; text-decoration: none; font-weight: bold; border-radius: 5px; font-size: 13px; color: white;}
        .btn-bloquear { background-color: #ff9800; }
        .btn-activar { background-color: #28a745; }
        .btn-eliminar { background-color: #dc3545; }
        
        .back-link { display: inline-block; margin-top: 25px; color: #555; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

    <h1>Gestión de Usuarios 👥</h1>
    <p>Panel de Control del Administrador</p>
    <?php echo $mensaje; ?>

    <div class="contenedor">
        <?php
        if ($resultado->num_rows > 0) {
            while($row = $resultado->fetch_assoc()) {
                $clase_estado = ($row['rol'] == 'administrador') ? 'administrador' : $row['estado_cuenta'];
                
                echo "<div class='card-usuario " . $clase_estado . "'>";
                echo "<h3>" . $row['nombre'] . " <span class='tag-rol'>" . ucfirst($row['rol']) . "</span></h3>";
                echo "<p>📧 " . $row['correo'] . "</p>";
                echo "<p>📅 Registrado: " . date("d/m/Y", strtotime($row['fecha_registro'])) . "</p>";
                echo "<p>📌 Estado: <strong>" . ucfirst($row['estado_cuenta']) . "</strong></p>";
                
                if ($row['id'] != $_SESSION['usuario_id']) {
                    echo "<div class='fila-botones'>";
                    
                    if ($row['estado_cuenta'] == 'activo') {
                        echo "<a href='gestionar_usuarios.php?bloquear_id=" . $row['id'] . "' class='btn btn-bloquear' onclick='return confirm(\"¿Suspender a este usuario?\")'>Bloquear 🚫</a>";
                    } else {
                        echo "<a href='gestionar_usuarios.php?activar_id=" . $row['id'] . "' class='btn btn-activar'>Activar ✅</a>";
                    }
                    
                    echo "<a href='gestionar_usuarios.php?eliminar_id=" . $row['id'] . "' class='btn btn-eliminar' onclick='return confirm(\"⚠️ ALERTA: Esto borrará al usuario y todas sus mascotas publicadas/solicitudes. ¿Estás seguro?\")'>Eliminar 🗑️</a>";
                    echo "</div>";
                } else {
                    echo "<p style='color:#007bff; font-weight:bold; font-size:12px; margin-top:10px;'>🌟 Eres tú (Super Admin)</p>";
                }
                
                echo "</div>";
            }
        }
        ?>
    </div>

    <a href="dashboard.php" class="back-link">← Volver al Menú Principal</a>

</body>
</html>