<?php 
session_start(); 
require_once 'conexion.php'; 

$pedido_id = $_GET['pedido_id'] ?? null; 

if (!$pedido_id) { 
    echo "No se ha encontrado el ID del pedido."; 
    exit; 
}

$stmt = $conn->prepare("SELECT p.id, p.total, p.estado, p.fecha, c.nombre 
                         FROM pedidos p 
                         JOIN clientes c ON p.cliente_id = c.id 
                         WHERE p.id = ? "); 
$stmt->bind_param("i", $pedido_id); 
$stmt->execute(); 
$stmt->store_result(); 
$stmt->bind_result($pedido_id, $total, $estado, $fecha, $cliente_nombre); 

if ($stmt->fetch()) { 
    echo "<h1>Gracias por tu compra, $cliente_nombre</h1>"; 
    echo "<p>Tu pedido con ID $pedido_id ha sido procesado.</p>"; 
    echo "<p>Total a pagar: MX$" . number_format($total, 2) . "</p>"; 
    echo "<p>Estado del pedido: $estado</p>"; 
    echo "<p>Fecha del pedido: $fecha</p>"; 

    echo "<h2>Detalles del pedido:</h2>"; 
    $stmt_detalle = $conn->prepare("SELECT dp.producto_id, p.nombre, dp.cantidad, dp.precio 
                                     FROM detalle_pedidos dp 
                                     JOIN productos p ON dp.producto_id = p.id 
                                     WHERE dp.pedido_id = ? "); 
    $stmt_detalle->bind_param("i", $pedido_id); 
    $stmt_detalle-> execute(); 
    $stmt_detalle->store_result(); 
    $stmt_detalle->bind_result($producto_id, $producto_nombre, $cantidad, $precio); 

    echo "<ul>"; 
    while ($stmt_detalle->fetch()) { 
        echo "<li>$producto_nombre - Cantidad: $cantidad - Precio: MX$" . number_format($precio, 2) . "</li>"; 
    } 
    echo "</ul>"; 

} else { 
    echo "No se ha encontrado el pedido con ID $pedido_id."; 
} 

$stmt->close(); 
$stmt_detalle->close(); 
$conn->close(); 
?> 