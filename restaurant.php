<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'cliente') {
    header("Location: login.html");
    exit;
}

require 'conexion.php';

$id_restaurante = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id_restaurante) {
    die("Error: ID de restaurante no valido.");
}

$sql_restaurante = "SELECT * FROM restaurantes WHERE id = ?";
$stmt_restaurante = $conn->prepare($sql_restaurante);
if (!$stmt_restaurante) {
    die("Error al preparar la consulta de restaurante: " . $conn->error);
}
$stmt_restaurante->bind_param("i", $id_restaurante);
$stmt_restaurante->execute();
$result_restaurante = $stmt_restaurante->get_result();
$restaurante = $result_restaurante->fetch_assoc();

if (!$restaurante) {
    die("Error: Restaurante no encontrado.");
}

$sql_menu = "SELECT * FROM productos WHERE restaurante_id = ?";
$stmt_menu = $conn->prepare($sql_menu);
if (!$stmt_menu) {
    die("Error al preparar la consulta del menu: " . $conn->error);
}
$stmt_menu->bind_param("i", $id_restaurante);
$stmt_menu->execute();
$result_menu = $stmt_menu->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($restaurante['nombre']); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="cssM2/restaurant.css">
    <style>
        .menu-card {
            margin-bottom: 20px;
        }
        .menu-image {
            height: 200px;
            object-fit: cover;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <header class="restaurant-header" style="background-image: url('<?php echo htmlspecialchars($restaurante['banner']); ?>'); background-size: cover; background-position: center;">
        <div class="container text-white p-4 text-center" style="background: rgba(0, 0, 0, 0.7);">
            <a href="javascript:history.back()" class="btn btn-light mb-3">← Regresar</a>
            <h1><?php echo htmlspecialchars($restaurante['nombre']); ?></h1>
            <p>⭐ <?php echo htmlspecialchars($restaurante['calificacion'] ?? '0'); ?> (<?php echo htmlspecialchars($restaurante['reseñas'] ?? '0'); ?> reseñas)</p>
            <p>Categoría: <?php echo htmlspecialchars($restaurante['categoria'] ?? 'No especificada'); ?></p>
            <p>Horario: <?php echo htmlspecialchars($restaurante['horario'] ?? 'No especificado'); ?></p>
        </div>
    </header>

    <main class="container my-5">
        <h2 class="text-center text-danger">Menú</h2>
        <?php if ($result_menu->num_rows > 0): ?>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php while ($menu = $result_menu->fetch_assoc()): ?>
                <div class="col">
                    <div class="card menu-card h-100">
                        <img src="<?php echo htmlspecialchars($menu['imagen']); ?>" alt="<?php echo htmlspecialchars($menu['nombre']); ?>" class="card-img-top menu-image">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($menu['nombre']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($menu['descripcion']); ?></p>
                            <p class="text-success"><strong>Precio: MX$<?php echo number_format($menu['precio'], 2); ?></strong></p>
                            <p>Disponible: <?php echo htmlspecialchars($menu['horario_disponible'] ?? 'Todo el día'); ?></p>
                            <form action="cart.php" method="POST">
                                <input type="hidden" name="menu_id" value="<?php echo $menu['id']; ?>">
                                <input type="hidden" name="menu_name" value="<?php echo htmlspecialchars($menu['nombre']); ?>">
                                <input type="hidden" name="menu_price" value="<?php echo $menu['precio']; ?>">
                                <input type="hidden" name="menu_image" value="<?php echo htmlspecialchars($menu['imagen']); ?>">
                                <button type="submit" name="add_to_cart" class="btn btn-primary w-100">Agregar al carrito</button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-warning text-center mt-4" role="alert">
                No hay productos disponibles en este momento.
            </div>
        <?php endif; ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
