<?php
session_start();
require 'FPDF/fpdf.php'; 


if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}


if (isset($_POST['add_to_cart'])) {
    $menu_id = $_POST['menu_id'];
    $menu_name = $_POST['menu_name'];
    $menu_price = $_POST['menu_price'];
    $menu_image = $_POST['menu_image'];
    $restaurante_id = $_POST['restaurante_id']; 
   
    if (!$restaurante_id) {
        die("Error: Restaurante no valido.");
    }


    if (!isset($_SESSION['restaurante_id'])) {
        $_SESSION['restaurante_id'] = $restaurante_id; 
    } elseif ($_SESSION['restaurante_id'] != $restaurante_id) {
        die("Error: No puedes agregar productos de diferentes restaurantes al carrito.");
    }
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $menu_id) {
            $item['quantity']++;
            $found = true;
            break;
        }
    }
    if (!$found) {
        $_SESSION['cart'][] = [
            'id' => $menu_id,
            'name' => $menu_name,
            'price' => $menu_price,
            'image' => $menu_image,
            'quantity' => 1,
            'restaurante_id' => $restaurante_id 
        ];
    }
    header("Location: cart.php?message=Producto agregado al carrito");
    exit;
}


if (isset($_POST['remove_from_cart'])) {
    $menu_id = $_POST['menu_id'];
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $menu_id) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
    $_SESSION['cart'] = array_values($_SESSION['cart']);
    header("Location: cart.php?message=Producto eliminado del carrito");
    exit;
}

if (isset($_POST['update_quantity'])) {
    $menu_id = $_POST['menu_id'];
    $new_quantity = (int)$_POST['quantity'];
    if ($new_quantity > 0) {
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $menu_id) {
                $item['quantity'] = $new_quantity;
                break;
            }
        }
        header("Location: cart.php?message=Cantidad actualizada correctamente");
    } else {
        header("Location: cart.php?message=La cantidad debe ser mayor a 0");
    }
    exit;
}


$total = 0;
$restaurante_id = null;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
    if (isset($item['restaurante_id'])) {
        $restaurante_id = $item['restaurante_id'];
    }
}
$_SESSION['total'] = $total; 
$_SESSION['restaurante_id'] = $restaurante_id; 


if (isset($_POST['generate_invoice'])) {
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);

    $pdf->Cell(40, 10, 'Factura de Compra');
    $pdf->Ln(10);

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(40, 10, 'Productos en el carrito:');
    $pdf->Ln(10);

    foreach ($_SESSION['cart'] as $item) {
        $pdf->Cell(0, 10, "{$item['name']} x{$item['quantity']} - MX$" . number_format($item['price'] * $item['quantity'], 2), 0, 1);
    }

    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, "Total: MX$" . number_format($total, 2), 0, 1);


    $pdf->Output('D', 'Factura.pdf');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="cssM2/cart.css">
</head>
<body>
    <div class="container">
        <h1>Carrito de Compras</h1>
        <a href="dashboard_cliente.php" class="continue-shopping">← Seguir comprando</a>
        <?php if (isset($_GET['message'])): ?>
            <p class='message'><?php echo htmlspecialchars($_GET['message']); ?></p>
        <?php endif; ?>

        <?php if (!empty($_SESSION['cart'])): ?>
            <div class="cart-items">
                <?php foreach ($_SESSION['cart'] as $item): ?>
                    <div class="cart-item">
                        <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="cart-image">
                        <div class="cart-info">
                            <h3><?php echo $item['name']; ?></h3>
                            <p>Precio: MX$<?php echo number_format($item['price'], 2); ?></p>
                            <form method="post" action="cart.php" class="quantity-form">
                                <input type="hidden" name="menu_id" value="<?php echo $item['id']; ?>">
                                <label for="quantity-<?php echo $item['id']; ?>">Cantidad:</label>
                                <input type="number" id="quantity-<?php echo $item['id']; ?>" name="quantity" value="<?php echo $item['quantity']; ?>" min="1">
                                <button type="submit" name="update_quantity">Actualizar</button>
                            </form>
                            <p>Subtotal: MX$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></p>
                            <form method="post" action="cart.php">
                                <input type="hidden" name="menu_id" value="<?php echo $item['id']; ?>">
                                <button type="submit" name="remove_from_cart">Eliminar</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <h2>Total: MX$<?php echo number_format($total, 2); ?></h2>
            <form method="get" action="metodo_pago.php">
                <button type="submit" class="checkout-button">Proceder al Pago</button>
            </form>
            <form method="post" action="cart.php">
                <button type="submit" name="generate_invoice" class="generate-receipt-button">Generar Factura</button>
            </form>
        <?php else: ?>

            <p>El carrito esta vacio</p>
            
        <?php endif; ?>
    </div>
</body>
</html>
