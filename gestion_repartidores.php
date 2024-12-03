<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.html");
    exit;
}

$query = "SELECT id, nombre, estado FROM repartidores";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Repartidores</title>
    <link rel="stylesheet" href="css3/GestionRep.css">
</head>

<body>
    <div class="navbar">
        <h1>Gestion de Repartidores</h1>
        <a href="dashboard_admin.php" class="logout-button">Regresar</a>
        <a href="registrar_repartidor.php" class="add-button">Registrar Nuevo Repartidor</a>

    </div>
    <div class="container">
        <h2>Lista de Repartidores</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($row['estado']); ?></td>
                    <td>
                        <a href="asignar_pedido.php?id=<?php echo $row['id']; ?>">Asignar Pedido</a> |
                        <a href="cambiar_estado.php?id=<?php echo $row['id']; ?>">Cambiar Estado</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
