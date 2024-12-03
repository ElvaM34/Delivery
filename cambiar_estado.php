<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.html");
    exit;
}

$repartidor_id = $_GET['id'] ?? null;
if (!$repartidor_id) {
    header("Location: gestion_repartidores.php?error=no_id");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevo_estado = $_POST['estado'] ?? null;

    if ($nuevo_estado) {
        $update_query = "UPDATE repartidores SET estado = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("si", $nuevo_estado, $repartidor_id);
        $stmt->execute();

        header("Location: gestion_repartidores.php?success=estado_actualizado");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Estado del Repartidor</title>
    <link rel="stylesheet" href="css3/CambiarEstado.css">
</head>
<body>
    <h1>Cambiar Estado del Repartidor</h1>
    <form method="POST">
        <label for="estado">Seleccionar Nuevo Estado:</label>
        <select name="estado" id="estado" required>
            <option value="disponible">Disponible</option>
            <option value="ocupado">Ocupado</option>
            <option value="inactivo">Inactivo</option>
        </select>
        <button type="submit">Actualizar Estado</button>
    </form>
    <a href="gestion_repartidores.php">Regresar</a>
</body>
</html>
