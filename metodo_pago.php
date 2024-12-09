<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION['total']) || $_SESSION['total'] <= 0) {
    header("Location: cart.php?message=El carrito esta vacio Agrega productos antes de proceder al pago.");
    exit;
}

$total = $_SESSION['total']; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $metodo_pago = $_POST['metodo_pago'] ?? '';
    $direccion = trim($_POST['direccion'] ?? ''); 

    if (empty($direccion)) {
        $error = "Por favor proporciona una direccion valida.";
    } elseif (!in_array($metodo_pago, ['efectivo', 'tarjeta', 'otros', 'paypal'])) {
        $error = "Selecciona un metodo de pago valido.";
    } else {
        $_SESSION['direccion'] = $direccion;
        $_SESSION['metodo_pago'] = $metodo_pago;

        if ($metodo_pago === 'efectivo') {
            header("Location: pago_efectivo.php");
            exit;
        } elseif ($metodo_pago === 'paypal') {
            $error = null;
        }
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
    <script src="https://www.paypal.com/sdk/js?client-id=ARqyHWPfSSHVkS3VpCOWj6ZgKvr1U7Sx4QUU0xCnbPALtEFIe0lDVrw3iAMMeaWquFMAMYckWEml65nd&currency=MXN"></script>
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
                <input type="radio" name="metodo_pago" value="paypal" id="paypal_radio" required> Pagar con PayPal
            </label>
            <br><br>
            <button type="submit" class="btn-accept">Confirmar Metodo de Pago</button>
        </form>

        <a href="cart.php" class="btn-cancel">Volver al Carrito</a>
    </div>

    <div id="paypal-button-container" style="display:none;"></div>

    <script>
        const paypalRadioButton = document.getElementById('paypal_radio');
        const paypalButtonContainer = document.getElementById('paypal-button-container');

        paypalRadioButton.addEventListener('change', function () {
            if (this.checked) {
                paypalButtonContainer.style.display = 'block';
                renderPayPalButton();
            } else {
                paypalButtonContainer.style.display = 'none';
            }
        });

        function renderPayPalButton() {
            paypal.Buttons({
                createOrder: function (data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: '<?php echo $total; ?>'
                            }
                        }]
                    });
                },
                onApprove: function (data, actions) {
                    return actions.order.capture().then(function (details) {
                        window.location.href = "success.php?payment_id=" + details.id;
                    });
                },
                onError: function (err) {
                    alert('Hubo un error al procesar el pago: ' + err);
                }
            }).render('#paypal-button-container');
        }
    </script>
</body>
</html>
