<?php
require 'paypal-actions.php';

$total = isset($_GET['total']) ? floatval($_GET['total']) : 0;

if ($total <= 0) {
    die("Error: El monto total del pedido no es valido.");
}

$accessToken = getAccessToken();

$orderData = [
    "intent" => "CAPTURE",  
    "purchase_units" => [[
        "amount" => [
            "currency_code" => "MXN", 
            "value" => $total 
        ]
    ]],
    "application_context" => [
        "return_url" => "https://88.214.59.32/success.php", 
        "cancel_url" => "https://88.214.59.32/cancel.php" 
    ]
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, PAYPAL_API_URL . "/v2/checkout/orders");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($orderData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $accessToken"
]);

$result = curl_exec($ch);
curl_close($ch);

if (!$result) {
    die("Error al crear la orden: " . curl_error($ch)); 
}

$result = json_decode($result);
echo '<pre>';
print_r($result);
echo '</pre>';

$approvalUrl = null;
foreach ($result->links as $link) {
    if ($link->rel === 'approve') {
        $approvalUrl = $link->href;
    }
}

if ($approvalUrl) {
    header("Location: $approvalUrl");
    exit;
} else {
    die("No se pudo generar la URL de aprobacion de PayPal.");
}
?>
