<?php
session_start();
$pedido_id = $_GET['pedido_id'] ?? ''; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $direccion = $_POST['direccion'] ?? ''; 

    if ($direccion) {
        include('conexion.php'); 

        $sql = "UPDATE pedidos SET direccion = ? WHERE pedido_id = ?";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$direccion, $pedido_id])) {
            $updateEstado = "UPDATE pedidos SET estado = 'confirmado' WHERE pedido_id = ?";
            $stmt2 = $pdo->prepare($updateEstado);
            $stmt2->execute([$pedido_id]);

            header("Location: success_direccion.php?pedido_id=$pedido_id");
        } else {
            echo "Error al procesar la direccion.";
        }
    } else {
        echo "Por favor ingresa una dirección valida.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingreso de Dirección</title>
    <link rel="stylesheet" href="cssM5/terminadoPago.css">
</head>
<body>
    <div class="container">
        <h1>Gracias por tu pago!</h1>
        <p>Por favor ingresa la direccion de entrega de tu pedido:</p>
        
        <form action="ingreso_direccion.php?pedido_id=<?php echo $pedido_id; ?>" method="POST">
            <input type="text" name="direccion" placeholder="Ingresa tu dirección" required>
            <button type="submit">Confirmar Direccion</button>
        </form>
    </div>
</body>
</html>
