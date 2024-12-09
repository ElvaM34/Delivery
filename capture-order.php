<?php
require 'paypal-actions.php'; 

if (!isset($_GET['token'])) {
    die("Error: El token no fue encontrado.");
}

$token = $_GET['token']; 
$accessToken = getAccessToken(); 

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, PAYPAL_BASE_URL . "/v2/checkout/orders/$token/capture");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $accessToken"
]);

$result = curl_exec($ch);
curl_close($ch);

if (!$result) {
    die("Error al capturar el pago.");
}

$result = json_decode($result);
if ($result->status === "COMPLETED") {
    echo "Pago completado Gracias por tu compra.";
} else {
    echo "Error al procesar el pago.";
}
?>
