<?php
session_start();
require_once 'conexion.php';


if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Usuario no autenticado"]);
    exit();
}

$user_id = $_SESSION['user_id'];


if (!isset($_POST['producto_id']) || !isset($_POST['cantidad'])) {
    echo json_encode(["status" => "error", "message" => "Faltan datos para la actualizacion"]);
    exit();
}

$producto_id = (int)$_POST['producto_id'];
$cantidad = (int)$_POST['cantidad'];


if ($cantidad < 0) {
    echo json_encode(["status" => "error", "message" => "La cantidad debe ser un numero positivo"]);
    exit();
}


$stmt = $conn->prepare("SELECT * FROM inventarios WHERE id = ? AND restaurante_id IN (SELECT id FROM restaurantes WHERE user_id = ?)");
$stmt->bind_param("ii", $producto_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "Producto no encontrado en tu restaurante"]);
    exit();
}


$stmt = $conn->prepare("UPDATE inventarios SET cantidad = ? WHERE id = ?");
$stmt->bind_param("ii", $cantidad, $producto_id);

if ($stmt->execute()) {
    
    echo json_encode(["status" => "success", "message" => "Inventario actualizado correctamente"]);
} else {

    echo json_encode(["status" => "error", "message" => "Error al actualizar el inventario"]);
}
?>
