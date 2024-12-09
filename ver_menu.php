<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'cliente') {
    header("Location: login.html");
    exit();
}

$cliente_id = $_SESSION['user_id'];

if (!isset($_GET['id'])) {
    die("Error: Restaurante no encontrado.");
}

$restaurante_id = (int)$_GET['id'];

$stmt = $conn->prepare("SELECT id, nombre, descripcion, precio FROM productos WHERE restaurante_id = ?");
$stmt->bind_param("i", $restaurante_id);
$stmt->execute();
$result = $stmt->get_result();
$productos = $result->fetch_all(MYSQLI_ASSOC);

$stmt_restaurante = $conn->prepare("SELECT nombre, banner FROM restaurantes WHERE id = ?");
$stmt_restaurante->bind_param("i", $restaurante_id);
$stmt_restaurante->execute();
$restaurante = $stmt_restaurante->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - <?php echo htmlspecialchars($restaurante['nombre']); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Menu de <?php echo htmlspecialchars($restaurante['nombre']); ?></h1>
    <img src="<?php echo htmlspecialchars($restaurante['banner']); ?>" alt="Banner del Restaurante" class="img-fluid mb-4">
    <a href="dashboard_cliente.php" class="btn btn-secondary mb-4">Volver</a>

    <form action="cart.php" method="POST">
        <table class="table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Descripcion</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($producto['descripcion']); ?></td>
                        <td><?php echo '$' . number_format($producto['precio'], 2); ?></td>
                        <td>
                            <input type="number" name="cantidad[<?php echo $producto['id']; ?>]" class="form-control" min="0" value="0">
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <input type="hidden" name="restaurante_id" value="<?php echo $restaurante_id; ?>">
        <button type="submit" name="ir_al_carrito" class="btn btn-primary">Ir al Carrito</button>
    </form>
</div>
</body>
</html>
