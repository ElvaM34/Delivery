<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php?message=El carrito esta vacio");
    exit;
}

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header("Location: login.html");
    exit;
}

$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $subtotal = $item['price'] * $item['quantity'];
    $total += $subtotal + ($subtotal * 0.016); 
}

$stmt = $conn->prepare("INSERT INTO pedidos (cliente_id, total, estado) VALUES (?, ?, 'pendiente')");
$stmt->bind_param("id", $user_id, $total);
$stmt->execute();
$pedido_id = $stmt->insert_id;

$stmt_producto = $conn->prepare("SELECT id FROM productos WHERE id = ?");
$stmt_detalle = $conn->prepare("INSERT INTO detalle_pedidos (pedido_id, producto_id, cantidad, precio) VALUES (?, ?, ?, ?)");

foreach ($_SESSION['cart'] as $item) {
    $stmt_producto->bind_param("i", $item['id']);
    $stmt_producto->execute();
    $stmt_producto->store_result();

    if ($stmt_producto->num_rows > 0) { 
        $stmt_detalle->bind_param("iiid", $pedido_id, $item['id'], $item['quantity'], $item['price']);
        $stmt_detalle->execute();
    } else {
        echo "El producto con ID {$item['id']} no existe No sera añadido al pedido.";
    }
}

unset($_SESSION['cart']);
header("Location: success.html");
exit;
?>
