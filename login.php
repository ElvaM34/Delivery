<?php
session_start();
require_once 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        header("/login.html?error=empty_fields");
        exit;
    }

    $stmt = $conn->prepare("SELECT id, contraseña, tipo_usuario, nombre FROM usuarios WHERE email = ?");
    if (!$stmt) {
        header("login.html?error=system_error");
        exit;
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        header("login.html?error=invalid_credentials");
        exit;
    }

    $stmt->bind_result($id, $hashed_password, $tipo_usuario, $nombre);
    $stmt->fetch();

    if (!password_verify($password, $hashed_password)) {
        header("login.html?error=invalid_credentials");
        exit;
    }

    $_SESSION['user_id'] = $id;
    $_SESSION['user_type'] = $tipo_usuario;
    $_SESSION['nombre'] = $nombre;
    if ($tipo_usuario === 'admin') {
        header("Location: dashboard_admin.php");
    } else {
        header("Location: dashboard_{$tipo_usuario}.php");
    }

    exit;
}
?>
