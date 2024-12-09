// soporte.php
session_start();
require 'conexion.php';

if ($_SESSION['user_type'] !== 'cliente') {
    die("Acceso no autorizado");
}

// Obtiene los mensajes asociados al usuario actual
$usuario_id = $_SESSION['user_id'];
$sql = "SELECT s.*, u.nombre AS usuario_nombre 
        FROM soporte_cliente s 
        JOIN usuarios u ON s.usuario_id = u.id
        WHERE s.usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode($messages);
exit();
    