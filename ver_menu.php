<?php
require 'conexion.php';

if (!isset($_GET['restaurante_id']) || empty($_GET['restaurante_id'])) {
    die("Error: ID del restaurante no especificado.");
}

$restaurante_id = intval($_GET['restaurante_id']);

$stmt = $conn->prepare("SELECT * FROM productos WHERE restaurante_id = ?");
$stmt->bind_param("i", $restaurante_id);
$stmt->execute();
$result = $stmt->get_result();
$productos = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú del Restaurante</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .product-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Menu del Restaurante</h1>
    <?php if (empty($productos)): ?>
        <p class="text-center">No hay productos disponibles en el menú.</p>
    <?php else: ?>
        <div class="row">
            <?php foreach ($productos as $producto): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="<?php echo $producto['imagen'] ?: 'uploads/default.jpg'; ?>" class="card-img-top product-image" alt="Producto">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($producto['nombre']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                            <p class="card-text"><strong>MX$<?php echo number_format($producto['precio'], 2); ?></strong></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
