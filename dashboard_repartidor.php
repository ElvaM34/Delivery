<?php
ob_start();
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'repartidor') {
    header("Location: login.html");
    exit;
}

$user_id = $_SESSION['user_id'];

$query = "
    SELECT 
        p.id AS pedido_id, 
        p.cliente_id, 
        p.total, 
        p.estado, 
        p.metodo_pago,  
        r.nombre AS nombre_restaurante, 
        r.direccion AS direccion_restaurante
    FROM pedidos p
    INNER JOIN restaurantes r ON p.restaurante_id = r.id
    WHERE p.repartidor_id = ? 
    AND p.estado IN ('pendiente', 'en camino') 
    AND p.metodo_pago = 'efectivo' 
";
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Error al preparar la consulta: " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$query_pendientes = "
    SELECT 
        p.id AS pedido_id, 
        p.cliente_id, 
        p.total, 
        p.estado, 
        p.metodo_pago,  
        r.nombre AS nombre_restaurante, 
        r.direccion AS direccion_restaurante
    FROM pedidos p
    INNER JOIN restaurantes r ON p.restaurante_id = r.id
    WHERE p.estado = 'pendiente' 
    AND p.repartidor_id IS NULL
    AND p.metodo_pago = 'efectivo' 
";
$stmt_pendientes = $conn->prepare($query_pendientes);
if (!$stmt_pendientes) {
    die("Error al preparar la consulta de pedidos disponibles: " . $conn->error);
}
$stmt_pendientes->execute();
$result_pendientes = $stmt_pendientes->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Repartidor</title>
    <link rel="stylesheet" href="cssM2/dasReparti.css">
</head>
<body>
    <div class="navbar">
        <a href="logout.php" class="logout-button">Cerrar Sesion</a>
    </div>
    <div class="container">
        <h1>Bienvenido al Dashboard de Repartidor</h1>
        <p>Aqui puedes gestionar tus entregas asignadas y aceptar nuevos pedidos.</p>
        
        <h2>Entregas Asignadas</h2>
        <table>
            <thead>
                <tr>
                    <th>Pedido ID</th>
                    <th>Cliente</th>
                    <th>Total</th>
                    <th>Nombre del Restaurante</th>
                    <th>Dirección del Restaurante</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['pedido_id']; ?></td>
                            <td><?php echo htmlspecialchars($row['cliente_id']); ?></td>
                            <td>$<?php echo number_format($row['total'], 2); ?></td>
                            <td><?php echo htmlspecialchars($row['nombre_restaurante']); ?></td>
                            <td><?php echo htmlspecialchars($row['direccion_restaurante']); ?></td>
                            <td><?php echo ucfirst($row['estado']); ?></td>
                            <td>
                                <form action="cancelar_pedido.php" method="POST">
                                    <input type="hidden" name="pedido_id" value="<?php echo $row['pedido_id']; ?>">
                                    <button type="submit" class="btn-cancel">Cancelar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No tienes entregas asignadas actualmente.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h2>Pedidos Disponibles</h2>
        <table>
            <thead>
                <tr>
                    <th>Pedido ID</th>
                    <th>Cliente</th>
                    <th>Total</th>
                    <th>Nombre del Restaurante</th>
                    <th>Dirección del Restaurante</th>
                    <th>Accion</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_pendientes->num_rows > 0): ?>
                    <?php while ($row = $result_pendientes->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['pedido_id']; ?></td>
                            <td><?php echo htmlspecialchars($row['cliente_id']); ?></td>
                            <td>$<?php echo number_format($row['total'], 2); ?></td>
                            <td><?php echo htmlspecialchars($row['nombre_restaurante']); ?></td>
                            <td><?php echo htmlspecialchars($row['direccion_restaurante']); ?></td>
                            <td>
                                <form action="aceptar_pedido.php" method="POST">
                                    <input type="hidden" name="pedido_id" value="<?php echo $row['pedido_id']; ?>">
                                    <button type="submit" class="btn-accept">Aceptar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No hay pedidos disponibles en este momento.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </div>
</body>
</html>

<?php 
$stmt->close();
$stmt_pendientes->close();
$conn->close();
ob_end_flush(); 
?>
