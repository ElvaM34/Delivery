<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.html");
    exit;
}

$query = "SELECT id, nombre, categoria, horario, calificacion FROM restaurantes";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Restaurantes</title>
    <link rel="stylesheet" href="css3/gestion_restaurantes1.css">
</head>
<body>
    <div class="navbar">
        <h1>Gestion de Restaurantes</h1>
        <a href="dashboard_admin.php" class="back-button">Regresar</a>
    </div>
    <div class="container">
        <h2>Lista de Restaurantes</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Categoria</th>
                    <th>Horario</th>
                    <th>Calificacion</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($row['categoria']); ?></td>
                    <td><?php echo htmlspecialchars($row['horario']); ?></td>
                    <td><?php echo htmlspecialchars($row['calificacion']); ?></td>
                    <td>
                        <a href="editar_restaurante.php?id=<?php echo $row['id']; ?>">Editar</a> |
                        <a href="eliminar_restaurante.php?id=<?php echo $row['id']; ?>" onclick="return confirm('¿Estás seguro?')">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php $conn->close(); ?>
