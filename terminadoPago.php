<?php
session_start();
require 'paypal-actions.php'; 

$total = 100.00; 

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido Confirmado</title>
    <link rel="stylesheet" href="cssM5/terminadoPago.css">
</head>
<body>
    <div class="container">
        <div class="icon-check">✔</div>
        <h1>Gracias por tu pedido!</h1>
        <p>Tu pedido ha sido confirmado Te contactaremos pronto para coordinar la entrega</p>

        <h2>Total a pagar: MX$<?php echo number_format($total, 2); ?></h2>

        <a href="create-order.php?total=<?php echo $total; ?>" class="btn btn-primary">Pagar con PayPal</a>

        <a href="dashboard_cliente.php" class="btn btn-secondary">Volver al Menu</a>
    </div>
</body>
</html>
    