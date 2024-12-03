<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.html");
    exit;
}

$query = "SELECT p.id AS pedido_id, p.cliente_id, p.total, p.estado, r.nombre AS restaurante, r.direccion AS direccion_restaurante
          FROM pedidos p
          INNER JOIN restaurantes r ON p.restaurante_id = r.id";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Pedidos</title>
    <link rel="stylesheet" href="css3/gestion_pedidos.css">
</head>
<body>
    <div class="container">
        <h1>Gestion de Pedidos</h1>
        <table>
            <thead>
                <tr>
                    <th>ID Pedido</th>
                    <th>ID Cliente</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Restaurante</th>
                    <th>Direccion del Restaurante</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['pedido_id']; ?></td>
                    <td><?php echo $row['cliente_id']; ?></td>
                    <td>$<?php echo number_format($row['total'], 2); ?></td>
                    <td><?php echo ucfirst($row['estado']); ?></td>
                    <td><?php echo $row['restaurante']; ?></td>
                    <td><?php echo $row['direccion_restaurante']; ?></td>
                    <td>
                        <form action="actualizar_pedido.php" method="POST" style="display:inline;">
                            <input type="hidden" name="pedido_id" value="<?php echo $row['pedido_id']; ?>">
                            <button type="submit">Actualizar</button>
                        </form>
                        <form action="cancelar_pedido.php" method="POST" style="display:inline;">
                            <input type="hidden" name="pedido_id" value="<?php echo $row['pedido_id']; ?>">
                            <button type="submit">Cancelar</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php $conn->close(); ?>
