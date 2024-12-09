<?php
require 'paypal_config.php';

use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;

session_start();

if (!isset($_GET['success']) || $_GET['success'] !== 'true') {
    die("Pago cancelado.");
}

if (!isset($_GET['paymentId']) || !isset($_GET['PayerID'])) {
    die("Información incompleta del pago.");
}

$paymentId = $_GET['paymentId'];
$payerId = $_GET['PayerID'];

try {
    $payment = Payment::get($paymentId, $apiContext);

    $execution = new PaymentExecution();
    $execution->setPayerId($payerId);

    $result = $payment->execute($execution, $apiContext);

    $total = $result->transactions[0]->amount->total;

    $userId = $_SESSION['user_id'];
    $db = new PDO('mysql:host=localhost;dbname=tu_base_de_datos', 'usuario', 'contraseña');

    $stmt = $db->prepare("INSERT INTO orders (user_id, status, total) VALUES (:user_id, :status, :total)");
    $stmt->execute([
        ':user_id' => $userId,
        ':status' => 'paid',
        ':total' => $total
    ]);

    $orderId = $db->lastInsertId();

    foreach ($_SESSION['cart'] as $item) {
        $stmt = $db->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)");
        $stmt->execute([
            ':order_id' => $orderId,
            ':product_id' => $item['id'],
            ':quantity' => $item['quantity'],
            ':price' => $item['price']
        ]);
    }

    $_SESSION['cart'] = [];

    echo "Pago echo Pedido registrado con el ID: $orderId.";
    echo "<a href='success.html'>Ir a la pagina de confirmacion</a>";
} catch (Exception $ex) {
    die("Error al procesar el pago: " . $ex->getMessage());
}
