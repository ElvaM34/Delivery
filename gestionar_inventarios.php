<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['user_id'])) {
    echo "Usuario no autenticado";
    exit();
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['restaurante_id'])) {
    echo "Restaurante no encontrado";
    exit();
}

$restaurante_id = (int)$_GET['restaurante_id'];


$stmt = $conn->prepare("
    SELECT i.id, p.nombre AS producto_nombre, i.cantidad
    FROM inventarios i
    JOIN productos p ON i.producto_id = p.id
    WHERE i.restaurante_id = ? AND i.restaurante_id IN (SELECT id FROM restaurantes WHERE user_id = ?)
");
$stmt->bind_param("ii", $restaurante_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();


if ($result->num_rows === 0) {
    echo "No se encontraron productos en el inventario.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Inventarios - Restaurante</title>
    <link rel="stylesheet" href="cssM2/dashRest.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1>Gestionar Inventarios - <?php echo htmlspecialchars($restaurante['nombre']); ?></h1>

    <h2 class="mt-4">Productos en Inventario</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['producto_nombre']); ?></td>
                    <td>
                        <input type="number" class="form-control" id="cantidad_<?php echo $row['id']; ?>" value="<?php echo $row['cantidad']; ?>">
                    </td>
                    <td>
                        <button class="btn btn-primary actualizar" data-id="<?php echo $row['id']; ?>">Actualizar</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('.actualizar').click(function() {
        var producto_id = $(this).data('id');
        var cantidad = $('#cantidad_' + producto_id).val();


        $.ajax({
            url: 'actualizar_inventario.php',
            method: 'POST',
            data: {
                producto_id: producto_id,
                cantidad: cantidad
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
                alert('Hubo un error al actualizar el inventario.');
            }
        });
    });
});
</script>
</body>
</html>
