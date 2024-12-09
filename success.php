<?php
session_start();
require 'conexion.php';

if (!isset($_GET['payment_id'])) {
    header("Location: cart.php?message=Hubo un problema con el pago.");
    exit;
}

$payment_id = $_GET['payment_id'];

$total = $_SESSION['total'] ?? 0;
$cliente_id = $_SESSION['user_id'] ?? null; 
$direccion = $_SESSION['direccion'] ?? 'Direccion no especificada'; 
$metodo_pago = 'paypal'; 
$estado = 'pendiente';

if (empty($payment_id) || !$cliente_id || $total <= 0) {
    die("Error: Datos invalidos para registrar el pedido.");
}

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $descripcion = implode(", ", array_map(function ($item) {
        return "{$item['name']} x{$item['quantity']}";
    }, $_SESSION['cart']));
} else {
    die("Error: El carrito esta vacio No se puede registrar el pedido.");
}

$restaurante_id = $_SESSION['cart'][0]['restaurante_id'] ?? 0;

if ($restaurante_id == 0) {
    die("Error: Restaurante no valido.");
}

$query = "INSERT INTO pedidos (cliente_id, restaurante_id, descripcion, total, payment_id, estado, metodo_pago, direccion) 
          VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);

if (!$stmt) {
    die("Error al preparar la consulta SQL: " . $conn->error);
}

$stmt->bind_param("iissssss", $cliente_id, $restaurante_id, $descripcion, $total, $payment_id, $estado, $metodo_pago, $direccion);

if ($stmt->execute()) {
    unset($_SESSION['cart']);
    unset($_SESSION['total']);
    unset($_SESSION['direccion']);

    $mensaje = "Gracias por tu pedido! Tu pago fue exitoso El restaurante se pondra en contacto contigo pronto.";
} else {
    die("Error al ejecutar la consulta SQL: " . $stmt->error);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago Exitoso!</title>
    <link rel="stylesheet" href="cssM5/terminadoPago.css">
</head>
<body>
    <div class="container">
        <h1>✔</h1>
        <h2><?php echo $mensaje; ?></h2>
        <a href="dashboard_cliente.php" class="btn-success">Volver al Menu</a>
    </div>
</body>
</html>
