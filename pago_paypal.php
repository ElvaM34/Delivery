<?php
session_start();
include('conexion.php');

if (!isset($_SESSION['pedido_id']) || $_SESSION['pedido_id'] == 0) {
    die('No se pudo procesar el pedido No se encontro un ID de pedido valido.');
}

$pedido_id = $_SESSION['pedido_id'];  

$query = "SELECT total FROM pedidos WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $pedido_id);
$stmt->execute();
$stmt->bind_result($total);
$stmt->fetch();
$stmt->close();


if ($total === null) {
    die('No se pudo obtener el total del pedido.');
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagar con PayPal</title>
    <script src="https://www.paypal.com/sdk/js?client-id=ARqyHWPfSSHVkS3VpCOWj6ZgKvr1U7Sx4QUU0xCnbPALtEFIe0lDVrw3iAMMeaWquFMAMYckWEml65nd&currency=MXN"></script>
</head>
<body>
    <div>
        <p>Total a pagar: <strong>MX$<?php echo number_format($total, 2); ?></strong></p>
    </div>
    <div id="paypal-button-container"></div>

    <script>
        paypal.Buttons({
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: '<?php echo number_format($total, 2, '.', ''); ?>' 
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    alert('Pago realizado por ' + details.payer.name.given_name);

                    var pedido_id = '<?php echo $pedido_id; ?>';

                    window.location.href = "success.php?payment_id=" + details.id + "&pedido_id=" + pedido_id;
                });
            },
            onCancel: function(data) {
                alert('El pago fue cancelado.');
            }
        }).render('#paypal-button-container');
    </script>
</body>
</html>
