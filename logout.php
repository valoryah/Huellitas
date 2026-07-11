<?php
session_start();
session_destroy(); // Borramos los datos de la sesión
header("Location: index.php"); // Lo regresamos al inicio
?>