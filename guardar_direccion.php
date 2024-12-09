<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $direccion = $_POST['direccion'] ?? '';

    if (!empty($direccion)) {
        $_SESSION['direccion_cliente'] = $direccion;

       
        $pedido_id = $_GET['pedido_id']; 

        $sql = "UPDATE pedidos SET direccion_cliente = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $direccion, $pedido_id);

        if ($stmt->execute()) {
            echo "Direccion guardada correctamente. El restaurante sera notificado.";
        } else {
            echo "Error al guardar la dirección.";
        }
    } else {
        echo "La dirección no puede estar vacia.";
    }
}
?>
