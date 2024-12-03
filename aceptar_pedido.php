<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'repartidor') {
    header("Location: login.html");
    exit;
}

if (!isset($_POST['pedido_id'])) {
    header("Location: dashboard_repartidor.php?error=No se especifico un ID de pedido");
    exit;
}

$pedido_id = intval($_POST['pedido_id']);
$repartidor_id = $_SESSION['user_id'];

$query = "UPDATE pedidos SET repartidor_id = ?, estado = 'en camino' WHERE id = ? AND repartidor_id IS NULL";
$stmt = $conn->prepare($query);

if (!$stmt) {
    header("Location: dashboard_repartidor.php?error=Error al preparar la consulta: " . $conn->error);
    exit;
}

$stmt->bind_param("ii", $repartidor_id, $pedido_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    header("Location: dashboard_repartidor.php?message=Pedido aceptado con exito");
    exit;
} else {
    header("Location: dashboard_repartidor.php?error=No se pudo aceptar el pedido Es posible que ya este asignado.");
    exit;
}

$stmt->close();
$conn->close();
?>
