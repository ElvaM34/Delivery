<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.html");
    exit;
}

if (isset($_POST['pedido_id'])) {
    $pedido_id = intval($_POST['pedido_id']);
    $nuevo_estado = 'en camino'; 

    $query = "UPDATE pedidos SET estado = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si', $nuevo_estado, $pedido_id);

    if ($stmt->execute()) {
        header("Location: gestion_pedidos.php?message=Pedido actualizado correctamente");
    } else {
        
        echo "Error al actualizar el pedido.";
    }

    $stmt->close();
    $conn->close();
}
?>
