<?php
session_start();
include('conexion.php'); 

if (isset($_POST['direccion']) && isset($_GET['pedido_id'])) {
    $direccion = $_POST['direccion'];
    $pedido_id = $_GET['pedido_id'];

    $sql = "UPDATE pedidos SET direccion = ? WHERE pedido_id = ?";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$direccion, $pedido_id])) {
        $updateEstado = "UPDATE pedidos SET estado = 'confirmado' WHERE pedido_id = ?";
        $stmt2 = $pdo->prepare($updateEstado);
        $stmt2->execute([$pedido_id]);

        header("Location: success.php?pedido_id=$pedido_id");
    } else {
        echo "Error al procesar la direccion";
    }
} else {
    echo "Faltan parametros.";
}
?>
