<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'cliente') {
    header("Location: login.html");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['enviar_soporte'])) {
    $mensaje = $_POST['mensaje'];
    $usuario_id = $_SESSION['user_id']; 

    $query = "INSERT INTO soporte_cliente (usuario_id, mensaje) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('is', $usuario_id, $mensaje);

    if ($stmt->execute()) {
        echo "<p>Tu mensaje ha sido enviado con exito, te responderemos pronto.</p>";
    } else {
        echo "<p>Error al enviar el mensaje, Intentalo de nuevo mas tarde.</p>";
    }

    $stmt->close();
    $conn->close();
}
?>
