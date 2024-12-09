<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'repartidor') {
    header("Location: login.html");
    exit;
}

$user_id = $_SESSION['user_id'];

$query_asignadas = "
    SELECT 
        p.id AS pedido_id, 
        p.descripcion, 
        p.total, 
        p.estado, 
        r.nombre AS nombre_restaurante, 
        r.direccion AS direccion_restaurante
    FROM pedidos p
    INNER JOIN restaurantes r ON p.restaurante_id = r.id
    WHERE p.repartidor_id = ? 
    AND p.estado IN ('pendiente', 'en camino')
";
$stmt_asignadas = $conn->prepare($query_asignadas);
$stmt_asignadas->bind_param("i", $user_id);
$stmt_asignadas->execute();
$result_asignadas = $stmt_asignadas->get_result();

$query_disponibles = "
    SELECT 
        p.id AS pedido_id, 
        p.descripcion, 
        p.total, 
        r.nombre AS nombre_restaurante, 
        r.direccion AS direccion_restaurante
    FROM pedidos p
    INNER JOIN restaurantes r ON p.restaurante_id = r.id
    WHERE p.estado = 'pendiente' 
    AND p.repartidor_id IS NULL
";
$stmt_disponibles = $conn->prepare($query_disponibles);
$stmt_disponibles->execute();
$result_disponibles = $stmt_disponibles->get_result();


$query_finalizados = "
    SELECT 
        p.id AS pedido_id, 
        p.descripcion, 
        p.total, 
        p.estado, 
        r.nombre AS nombre_restaurante, 
        r.direccion AS direccion_restaurante
    FROM pedidos p
    INNER JOIN restaurantes r ON p.restaurante_id = r.id
    WHERE p.repartidor_id = ? 
    AND p.estado NOT IN ('pendiente', 'en camino')
";
$stmt_finalizados = $conn->prepare($query_finalizados);
$stmt_finalizados->bind_param("i", $user_id);
$stmt_finalizados->execute();
$result_finalizados = $stmt_finalizados->get_result();
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
        <a href="logout.php" class="logout-button">Cerrar Sesión</a>
    </div>
    <div class="container">
        <h1>Dashboard de Repartidor</h1>

        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($_GET['message']); ?></div>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>

        <h2>Entregas Asignadas</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Descripcion</th>
                    <th>Total</th>
                    <th>Restaurante</th>
                    <th>Direccion</th>
                    <th>Estado</th>
                    <th>Accion</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_asignadas->num_rows > 0): ?>
                    <?php while ($row = $result_asignadas->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['pedido_id']; ?></td>
                            <td><?php echo htmlspecialchars($row['descripcion']); ?></td>
                            <td>$<?php echo number_format($row['total'], 2); ?></td>
                            <td><?php echo htmlspecialchars($row['nombre_restaurante']); ?></td>
                            <td><?php echo htmlspecialchars($row['direccion_restaurante']); ?></td>
                            <td><?php echo ucfirst($row['estado']); ?></td>
                            <td>
                                <form action="cancelar_pedido.php" method="POST">
                                    <input type="hidden" name="pedido_id" value="<?php echo $row['pedido_id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Cancelar</button>
                                </form>
                                <form action="realizar_pedido.php" method="POST" style="display: inline;">
                                <input type="hidden" name="pedido_id" value="<?php echo $row['pedido_id']; ?>">
                                <button type="submit" class="btn btn-success btn-sm">Entregado</button>
                            </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No tienes entregas asignadas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h2>Pedidos Disponibles</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Descripcion</th>
                    <th>Total</th>
                    <th>Restaurante</th>
                    <th>Direccion</th>
                    <th>Accion</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_disponibles->num_rows > 0): ?>
                    <?php while ($row = $result_disponibles->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['pedido_id']; ?></td>
                            <td><?php echo htmlspecialchars($row['descripcion']); ?></td>
                            <td>$<?php echo number_format($row['total'], 2); ?></td>
                            <td><?php echo htmlspecialchars($row['nombre_restaurante']); ?></td>
                            <td><?php echo htmlspecialchars($row['direccion_restaurante']); ?></td>
                            <td>
                                <form action="aceptar_pedido.php" method="POST">
                                    <input type="hidden" name="pedido_id" value="<?php echo $row['pedido_id']; ?>">
                                    <button type="submit" class="btn btn-primary btn-sm">Aceptar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No hay pedidos disponibles.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h2>Pedidos Completados o Cancelados</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Descripcion</th>
                    <th>Total</th>
                    <th>Restaurante</th>
                    <th>Direccion</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_finalizados->num_rows > 0): ?>
                    <?php while ($row = $result_finalizados->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['pedido_id']; ?></td>
                            <td><?php echo htmlspecialchars($row['descripcion']); ?></td>
                            <td>$<?php echo number_format($row['total'], 2); ?></td>
                            <td><?php echo htmlspecialchars($row['nombre_restaurante']); ?></td>
                            <td><?php echo htmlspecialchars($row['direccion_restaurante']); ?></td>
                            <td><?php echo ucfirst($row['estado']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No hay pedidos completados o cancelados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php 
$stmt_asignadas->close();
$stmt_disponibles->close();
$stmt_finalizados->close();
$conn->close();
?>
