<?php
session_start();

$total = $_SESSION['total'] ?? 0.00; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $metodo_pago = $_POST['metodo_pago'] ?? ''; 

    if ($metodo_pago === 'efectivo') {
        header("Location: pago_efectivo.php");  
        exit; 
    } elseif ($metodo_pago === 'paypal') {
        header("Location: create-order.php?total=" . $total); 
        exit; 
    } else {
        $error = "Selecciona un método de pago válido."; 
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Metodo de Pago</title>
    <link rel="stylesheet" href="cssM5/configPago.css">
</head>
<body>
    <div class="container">
        <h1>Selecciona un Metodo de Pago</h1>
        <p>Total a pagar: <strong>MX$<?php echo number_format($total, 2); ?></strong></p>
        
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>
        
        <form action="metodo_pago.php" method="POST">
            <label>
                <input type="radio" name="metodo_pago" value="efectivo" required> Pago en Efectivo
            </label>
            <br>
            <label>
                <input type="radio" name="metodo_pago" value="paypal" required> PayPal
            </label>
            <br><br>
            <button type="submit" class="btn-accept">Confirmar Metodo de Pago</button>
        </form>

        <a href="cart.php" class="btn-cancel">Volver al Carrito</a>
    </div>
</body>
</html>
