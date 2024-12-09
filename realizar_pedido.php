<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'repartidor') {
    header("Location: login.html");
    exit;
}

if (!isset($_POST['pedido_id'])) {
    header("Location: dashboard_repartidor.php?error=No se especifico un ID de pedido.");
    exit;
}

$pedido_id = intval($_POST['pedido_id']);
$repartidor_id = intval($_SESSION['user_id']);

$query = "
    UPDATE pedidos 
    SET estado = 'entregado' 
    WHERE id = ? AND repartidor_id = ? AND estado = 'en camino'
";

$stmt = $conn->prepare($query);

if (!$stmt) {
    header("Location: dashboard_repartidor.php?error=Error al preparar la consulta: " . $conn->error);
    exit;
}

$stmt->bind_param("ii", $pedido_id, $repartidor_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    header("Location: dashboard_repartidor.php?message=Pedido marcado como entregado con exito.");
} else {
    header("Location: dashboard_repartidor.php?error=No se pudo marcar el pedido como entregado Verifica el estado o si está asignado a otro repartidor.");
}

$stmt->close();
$conn->close();
?>
