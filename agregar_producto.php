<?php
require 'conexion.php';

if (!isset($_GET['restaurante_id']) || !is_numeric($_GET['restaurante_id'])) {
    die("Error: ID del restaurante no especificado o invalido.");
}

$restaurante_id = intval($_GET['restaurante_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['restaurante_id']) || empty($_POST['restaurante_id'])) {
        die("Error: El ID del restaurante no se recibio correctamente en el formulario.");
    }

    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $precio = floatval($_POST['precio']);
    $imagen = null;

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['imagen']['tmp_name'];
        $fileName = uniqid() . '.' . pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
        $uploadDir = __DIR__ . '/uploads/';
        $destPath = $uploadDir . $fileName;

        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true)) {
            die("Error: No se pudo crear el directorio de subida.");
        }

        if (move_uploaded_file($fileTmpPath, $destPath)) {
            $imagen = 'uploads/' . $fileName;
        } else {
            die("Error al subir la imagen.");
        }
    }

    $stmt = $conn->prepare("INSERT INTO productos (restaurante_id, nombre, descripcion, precio, imagen) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issds", $restaurante_id, $nombre, $descripcion, $precio, $imagen);

    if ($stmt->execute()) {
        header("Location: gestionar_menu.php?restaurante_id=" . $restaurante_id);
        exit;
    } else {
        die("Error al insertar producto: " . $stmt->error);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Producto</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <a href="javascript:history.back()" class="btn btn-secondary">← Regresar</a>
    <h1 class="text-center mb-4">Agregar Producto</h1>
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="restaurante_id" value="<?php echo $restaurante_id; ?>">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Producto</label>
            <input type="text" name="nombre" id="nombre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripcion</label>
            <textarea name="descripcion" id="descripcion" class="form-control" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="precio" class="form-label">Precio (MX$)</label>
            <input type="number" step="0.01" name="precio" id="precio" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="imagen" class="form-label">Imagen del Producto</label>
            <input type="file" name="imagen" id="imagen" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary w-100">Agregar Producto</button>
    </form>
</div>
</body>
</html>
