<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Usuario no autenticado.']);
    exit();
}

$user_id = $_SESSION['user_id'];

if (!isset($_POST['pedido_id'], $_POST['estado'])) {
    echo json_encode(['status' => 'error', 'message' => 'Datos incompletos.']);
    exit();
}

$pedido_id = (int)$_POST['pedido_id'];
$estado = $_POST['estado'];

$estados_validos = ['pendiente', 'en camino', 'entregado'];
if (!in_array($estado, $estados_validos)) {
    echo json_encode(['status' => 'error', 'message' => 'Estado no valido.']);
    exit();
}

$stmt = $conn->prepare("
    UPDATE pedidos 
    SET estado = ? 
    WHERE id = ? AND restaurante_id IN (SELECT id FROM restaurantes WHERE user_id = ?)
");
$stmt->bind_param("sii", $estado, $pedido_id, $user_id);
$stmt->execute();


if ($stmt->affected_rows > 0) {
    echo json_encode(['status' => 'success', 'message' => 'Estado del pedido actualizado correctamente.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No se pudo actualizar el estado del pedido.']);
}
?>
