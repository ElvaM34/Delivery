<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'repartidor') {
    header("Location: login.html");
    exit;
}

if (!isset($_POST['pedido_id'])) {
    die("Error: No se especifico un ID de pedido.");
}

$pedido_id = intval($_POST['pedido_id']);
$repartidor_id = $_SESSION['user_id'];

$query = "UPDATE pedidos SET repartidor_id = NULL, estado = 'pendiente' WHERE id = ? AND repartidor_id = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    die("Error al preparar la consulta: " . $conn->error);
}

$stmt->bind_param("ii", $pedido_id, $repartidor_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    header("Location: dashboard_repartidor.php");
    exit;
} else {
    echo "Error: No se pudo cancelar el pedido.";
}

$stmt->close();
$conn->close();
?>
