<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['user_id'])) {
    die("Error: Usuario no autenticado.");
}

$user_id = $_SESSION['user_id'];


$stmt = $conn->prepare("SELECT id, nombre, direccion, telefono, banner FROM restaurantes WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$restaurantes_result = $stmt->get_result();
$restaurantes = $restaurantes_result->fetch_all(MYSQLI_ASSOC);


$restaurante_id = isset($_GET['restaurante_id']) ? (int)$_GET['restaurante_id'] : null;


$pedidos = [];
if ($restaurante_id) {
    $stmt = $conn->prepare("
        SELECT p.id, p.descripcion, u.nombre AS cliente, p.estado, p.total, p.fecha, p.metodo_pago 
        FROM pedidos p
        LEFT JOIN usuarios u ON p.cliente_id = u.id
        WHERE p.restaurante_id = ?
        ORDER BY p.fecha DESC
    ");
    $stmt->bind_param("i", $restaurante_id);
    $stmt->execute();
    $pedidos_result = $stmt->get_result();
    $pedidos = $pedidos_result->fetch_all(MYSQLI_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Restaurante</title>
    <link rel="stylesheet" href="cssM2/dashRest.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Dashboard Restaurante</h1>
    <a href="logout.php" class="btn btn-danger">Cerrar Sesion</a>

    <?php if (empty($restaurantes)): ?>
        <h2 class="mt-4">Registrar un Restaurante</h2>
        <form method="post" action="dashboard_restaurante.php" enctype="multipart/form-data" class="mt-3">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Restaurante</label>
                <input type="text" id="nombre" name="nombre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="direccion" class="form-label">Direccion</label>
                <textarea id="direccion" name="direccion" class="form-control" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label">Telefono</label>
                <input type="text" id="telefono" name="telefono" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="banner" class="form-label">Imagen del Restaurante (Opcional)</label>
                <input type="file" id="banner" name="banner" class="form-control">
            </div>
            <button type="submit" name="add_restaurant" class="btn btn-primary">Registrar Restaurante</button>
        </form>
    <?php else: ?>

        <h2 class="mt-4">Tus Restaurantes</h2>
        <ul class="list-group mt-3">
            <?php foreach ($restaurantes as $restaurante): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong><?php echo htmlspecialchars($restaurante['nombre']); ?></strong><br>
                        <span><?php echo htmlspecialchars($restaurante['direccion']); ?></span><br>
                        <span><?php echo htmlspecialchars($restaurante['telefono']); ?></span>
                    </div>
                    <div class="ms-3">
                        <img src="<?php echo $restaurante['banner'] ?: 'uploads/default-banner.jpg'; ?>" alt="Banner" width="50">
                    </div>
                    <div class="ms-auto">
                        <a href="?restaurante_id=<?php echo $restaurante['id']; ?>" class="btn btn-primary btn-sm">Ver Pedidos</a>
                        <a href="gestionar_inventarios.php?restaurante_id=<?php echo $restaurante['id']; ?>" class="btn btn-secondary btn-sm">Gestionar Inventarios</a>
                        <a href="gestionar_menu.php?restaurante_id=<?php echo $restaurante['id']; ?>" class="btn btn-warning btn-sm">Gestionar Menú</a>
                        <a href="editar_restaurante.php?id=<?php echo $restaurante['id']; ?>" class="btn btn-success btn-sm">Editar</a>
                        <a href="eliminar_restaurante.php?id=<?php echo $restaurante['id']; ?>" class="btn btn-danger btn-sm">Eliminar</a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php if ($restaurante_id): ?>
            <h2 class="mt-4">Pedidos Recibidos</h2>
            <?php if (!empty($pedidos)): ?>
                <table class="table mt-3">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Descripcion</th>
                            <th>Cliente</th>
                            <th>Estado</th>
                            <th>Total</th>
                            <th>Método de Pago</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pedidos as $pedido): ?>
                            <tr>
                                <th scope="row"><?php echo $pedido['id']; ?></th>
                                <td><?php echo htmlspecialchars($pedido['descripcion']); ?></td>
                                <td><?php echo htmlspecialchars($pedido['cliente']); ?></td>
                                <td>
                                    <select class="form-select estado" data-id="<?php echo $pedido['id']; ?>">
                                        <option value="pendiente" <?php echo ($pedido['estado'] === 'pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                                        <option value="en camino" <?php echo ($pedido['estado'] === 'en camino') ? 'selected' : ''; ?>>En Camino</option>
                                        <option value="entregado" <?php echo ($pedido['estado'] === 'entregado') ? 'selected' : ''; ?>>Entregado</option>
                                    </select>
                                </td>
                                <td><?php echo '$' . number_format($pedido['total'], 2); ?></td>
                                <td><?php echo htmlspecialchars($pedido['metodo_pago']); ?></td>
                                <td><?php echo date("d-m-Y H:i", strtotime($pedido['fecha'])); ?></td>
                                <td>
                                    <button class="btn btn-primary actualizar_estado" data-id="<?php echo $pedido['id']; ?>">Actualizar</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No hay pedidos para este restaurante.</p>
            <?php endif; ?>
        <?php endif; ?>
    <?php endif; ?>
</div>

<script>
    $(document).ready(function() {
        $('.actualizar_estado').click(function() {
            var pedido_id = $(this).data('id');
            var estado = $('.estado[data-id="' + pedido_id + '"]').val();

            $.ajax({
                url: 'actualizar_estado_pedido.php',
                method: 'POST',
                data: {
                    pedido_id: pedido_id,
                    estado: estado
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.status === 'success') {
                        alert(data.message);
                    } else {
                        alert(data.message);
                    }
                },
                error: function() {
                    alert('Error al actualizar el estado del pedido.');
                }
            });
        });
    });
</script>
</body>
</html>
