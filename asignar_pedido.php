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

$query = "SELECT id, descripcion FROM pedidos WHERE estado = 'pendiente'";
$result = $conn->query($query);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pedido_id = $_POST['pedido_id'] ?? null;

    if ($pedido_id) {
        $update_query = "UPDATE pedidos SET repartidor_id = ?, estado = 'en camino' WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ii", $repartidor_id, $pedido_id);
        $stmt->execute();

        header("Location: gestion_repartidores.php?success=pedido_asignado");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Pedido al Repartidor</title>
    <link rel="stylesheet" href="css3/AsignarP.css">
</head>
<body>
    <h1>Asignar Pedido al Repartidor</h1>
    <form method="POST">
        <label for="pedido_id">Seleccionar Pedido:</label>
        <select name="pedido_id" id="pedido_id" required>
            <option value="">-- Seleccionar --</option>
            <?php while ($row = $result->fetch_assoc()): ?>
            <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['descripcion']); ?></option>
            <?php endwhile; ?>
        </select>
        <button type="submit">Asignar Pedido</button>
    </form>
    <a href="gestion_repartidores.php">Regresar</a>
</body>
</html>
