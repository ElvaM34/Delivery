<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.html");
    exit;
}


if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $restaurante_id = $_GET['id'];


    $query = "DELETE FROM restaurantes WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $restaurante_id);

    if ($stmt->execute()) {
        header("Location: gestion_restaurantes.php?mensaje=Restaurante eliminado correctamente");
    } else {
        header("Location: gestion_restaurantes.php?error=Error al eliminar el restaurante");
    }
    $stmt->close();
} else {
    header("Location: gestion_restaurantes.php?error=ID de restaurante no valido");
}

$conn->close();
?>
