<?php
include 'conexion.php';

// Verificamos que la conexión exista antes de hacer nada
if (!$conn) {
    die("Error crítico: Conexión no establecida.");
}

$usuario = $_POST['usuario'] ?? '';
$password = $_POST['password'] ?? '';

$sql = "SELECT rol FROM usuarios WHERE usuario = ? AND password = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("ss", $usuario, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $fila = $result->fetch_assoc();
        $rol = $fila['rol'];
        echo "<script>
                localStorage.setItem('rolUsuario', '$rol');
                window.location.href = 'menu.php';
              </script>";
    } else {
        echo "<script>alert('Usuario o contraseña incorrectos'); window.location.href='index.html';</script>";
    }
} else {
    echo "Error en la consulta SQL: " . $conn->error;
}
?>