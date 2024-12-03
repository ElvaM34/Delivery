<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'cliente') {
    header("Location: login.html");
    exit;
}

require 'conexion.php';

$sql = "SELECT * FROM restaurantes";
$result = $conn->query($sql);

if (!$result) {
    die("<p>Error al obtener los restaurantes: " . $conn->error . "</p>");
}

$sql_descuentos = "SELECT d.*, r.nombre AS restaurante_nombre 
                   FROM descuentos d 
                   JOIN restaurantes r ON d.restaurante_id = r.id";
$result_descuentos = $conn->query($sql_descuentos);

if (!$result_descuentos) {
    die("<p>Error al obtener los descuentos: " . $conn->error . "</p>");
}

$usuario_id = $_SESSION['user_id']; 
$sql_soporte = "SELECT mensaje, fecha, estado FROM soporte_cliente WHERE usuario_id = ? ORDER BY fecha DESC";
$stmt = $conn->prepare($sql_soporte);
$stmt->bind_param('i', $usuario_id);
$stmt->execute();
$result_soporte = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Cliente</title>
    <link rel="stylesheet" href="cssM2/dashboard_cliente.css">
    <link rel="stylesheet" href="cssM2/descuentos.css">
    <link rel="stylesheet" href="cssM2/SoporteCliente.css"> 
</head>
<body>
    <header class="top-bar">
        <a href="logout.php" class="logout-button">Cerrar Sesion</a>
        <div class="location">
            <img src="img/location-icon.png" alt="Ubicacion">
            <span>Calle X</span>
        </div>
        <div class="search-bar">
            <input type="text" placeholder="Buscar en el menu...">
        </div>
    </header>

    <section class="discounts">
        <h2>Promociones y Descuentos</h2>
        <div class="discount-grid">
            <?php if ($result_descuentos && $result_descuentos->num_rows > 0): ?>
                <?php while ($row = $result_descuentos->fetch_assoc()): ?>
                    <div class="discount-card">
                        <img src="<?php echo $row['imagen']; ?>" alt="<?php echo $row['producto']; ?>">
                        <div class="discount-info">
                            <h3><?php echo $row['producto']; ?></h3>
                            <p>De: <span class="original-price">$<?php echo number_format($row['precio_original'], 2); ?></span></p>
                            <p>A: <span class="discount-price">$<?php echo number_format($row['precio_descuento'], 2); ?></span></p>
                            <p class="restaurant-name">En: <?php echo $row['restaurante_nombre']; ?></p>
                            <form action="cart.php" method="POST">
                                <input type="hidden" name="menu_id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="menu_name" value="<?php echo $row['producto']; ?>">
                                <input type="hidden" name="menu_price" value="<?php echo $row['precio_descuento']; ?>">
                                <input type="hidden" name="menu_image" value="<?php echo $row['imagen']; ?>">
                                <button type="submit" name="add_to_cart" class="add-to-cart-button">Agregar al carrito</button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No hay descuentos disponibles en este momento.</p>
            <?php endif; ?>
        </div>
    </section>

    <section class="restaurants">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <a href="restaurant.php?id=<?php echo $row['id']; ?>" class="restaurant-card">
                    <img src="<?php echo $row['banner']; ?>" alt="<?php echo $row['nombre']; ?>">
                    <div class="info">
                        <h3><?php echo $row['nombre']; ?></h3>
                        <p>Abre: <?php echo $row['horario']; ?></p>
                        <p class="price">⭐ <?php echo $row['calificacion']; ?> (<?php echo $row['reseñas']; ?> reseñas)</p>
                    </div>
                </a>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No hay restaurantes disponibles en este momento.</p>
        <?php endif; ?>
    </section>

    <section class="support">
        <h2>Soporte al Cliente</h2>
        <form action="enviar_soporte.php" method="POST">
            <textarea name="mensaje" placeholder="Escribe tu mensaje..." required></textarea>
            <button type="submit" name="enviar_soporte">Enviar Mensaje</button>
        </form>
    </section>

    <section class="support-status">
        <h2>Estado de tus mensajes de soporte</h2>
        <table>
            <thead>
                <tr>
                    <th>Mensaje</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_soporte && $result_soporte->num_rows > 0): ?>
                    <?php while ($row = $result_soporte->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['mensaje']); ?></td>
                            <td><?php echo $row['fecha']; ?></td>
                            <td><?php echo htmlspecialchars($row['estado']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="3">No tienes mensajes de soporte enviados.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>

    <script>
        function moveSlider(direction) {
            const slider = document.querySelector('.discount-slider');
            const scrollAmount = 300; 
            slider.scrollBy({
                left: direction * scrollAmount,
                behavior: 'smooth',
            });
        }
    </script>
</body>
</html>
