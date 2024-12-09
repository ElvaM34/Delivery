<?php
require 'paypal_config.php';

use PayPal\Api\Payer;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Payment;

session_start();

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    die("El carrito esta vacio.");
}

$cart = $_SESSION['cart'];
$total = 0;

$payer = new Payer();
$payer->setPaymentMethod('paypal');

$items = [];
foreach ($cart as $product) {
    $item = new Item();
    $item->setName($product['name'])
        ->setCurrency('USD')
        ->setQuantity($product['quantity'])
        ->setPrice($product['price']);
    $items[] = $item;
    $total += $product['price'] * $product['quantity'];
}

$itemList = new ItemList();
$itemList->setItems($items);

$amount = new Amount();
$amount->setCurrency('USD')
    ->setTotal($total);

$transaction = new Transaction();
$transaction->setAmount($amount)
    ->setItemList($itemList)
    ->setDescription('Compra en Osonny-Remake');

$redirectUrls = new RedirectUrls();
$redirectUrls->setReturnUrl("http://http://88.214.59.32/execute_payment.php?success=true")
    ->setCancelUrl("http:http://88.214.59.32/execute_payment.php?success=false");

$payment = new Payment();
$payment->setIntent('sale')
    ->setPayer($payer)
    ->setRedirectUrls($redirectUrls);

try {
    $payment->create($apiContext);
    header("Location: " . $payment->getApprovalLink());
    exit;
} catch (Exception $ex) {
    die("Error al crear el pago: " . $ex->getMessage());
}
