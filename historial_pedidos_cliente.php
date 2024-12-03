<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.html");
    exit;
}

$cliente_id = $_GET['id'];
$query = "SELECT p.id AS pedido_id, p.total, p.estado, r.nombre AS restaurante, p.fecha
          FROM pedidos p
          INNER JOIN restaurantes r ON p.restaurante_id = r.id
          WHERE p.cliente_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $cliente_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Pedidos</title>
    <link rel="stylesheet" href="css3/historial_pedidos.css">
</head>
<body>
    <div class="navbar">
        <h1>Historial de Pedidos</h1>
        <a href="gestion_clientes.php" class="back-button">Regresar</a>
    </div>
    <div class="container">
        <h2>Pedidos del Cliente</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Pedido</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Restaurante</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['pedido_id']; ?></td>
                    <td>$<?php echo number_format($row['total'], 2); ?></td>
                    <td><?php echo ucfirst($row['estado']); ?></td>
                    <td><?php echo htmlspecialchars($row['restaurante']); ?></td>
                    <td><?php echo htmlspecialchars($row['fecha']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
