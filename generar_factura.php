<?php
session_start();
require('FPDF/fpdf.php');
require_once 'conexion.php';

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    die("No hay productos en el carrito.");
}

if (!isset($_SESSION['user_id'])) {
    die("Informacion del usuario no encontrada.");
}

$user_id = $_SESSION['user_id'];

$query = $conn->prepare("SELECT nombre, email FROM usuarios WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows === 0) {
    die("Información del usuario no encontrada.");
}

$user = $result->fetch_assoc();
$user_name = $user['nombre'];
$user_email = $user['email'];

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

$pdf->Cell(0, 10, 'Factura de Compra', 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Fecha: ' . date('d/m/Y'), 0, 1, 'C');
$pdf->Ln(10);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Detalles del Cliente:', 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Nombre: ' . $user_name, 0, 1);
$pdf->Cell(0, 10, 'Correo: ' . $user_email, 0, 1);
$pdf->Ln(10);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(80, 10, 'Producto', 1);
$pdf->Cell(30, 10, 'Cantidad', 1);
$pdf->Cell(30, 10, 'Precio', 1);
$pdf->Cell(40, 10, 'Subtotal', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
$total = 0;

foreach ($_SESSION['cart'] as $producto) {
    $subtotal = $producto['price'] * $producto['quantity'];
    $total += $subtotal;

    $pdf->Cell(80, 10, $producto['name'], 1);
    $pdf->Cell(30, 10, $producto['quantity'], 1, 0, 'C');
    $pdf->Cell(30, 10, '$' . number_format($producto['price'], 2), 1, 0, 'R');
    $pdf->Cell(40, 10, '$' . number_format($subtotal, 2), 1, 0, 'R');
    $pdf->Ln();
}

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(110, 10, 'Total', 1);
$pdf->Cell(40, 10, '$' . number_format($total, 2), 1, 0, 'R');

$pdf->Output('D', 'Factura_' . date('Ymd') . '.pdf');
?>
