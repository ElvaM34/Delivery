<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.html");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';

    if (!empty($nombre)) {
        $query = "INSERT INTO repartidores (nombre, estado) VALUES (?, 'disponible')";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $nombre);
        $stmt->execute();

        header("Location: gestion_repartidores.php?success=registro_completado");
        exit;
    } else {
        $error = "El nombre del repartidor no puede estar vacio.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Repartidor</title>
</head>
<body>
    <h1>Registrar Repartidor</h1>
    <form method="POST">
        <label for="nombre">Nombre del Repartidor:</label>
        <input type="text" id="nombre" name="nombre" required>
        <button type="submit">Registrar</button>
    </form>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <a href="gestion_repartidores.php">Regresar</a>
</body>
</html>
