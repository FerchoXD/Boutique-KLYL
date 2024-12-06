<?php
session_start();
require 'conexion.php';
require 'Producto.php';

// Inicializamos el carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

if (isset($_POST['eliminar'])) {
    $id = intval($_POST['id']);
    foreach ($_SESSION['carrito'] as $indice => $producto) {
        if (isset($producto['id']) && $producto['id'] == $id) {
            unset($_SESSION['carrito'][$indice]);
            $_SESSION['carrito'] = array_values($_SESSION['carrito']);
            session_write_close(); // Esto sirve para evitar errores al hacer múltiples peticiones

            break;
        }
    }
}


// Obtener detalles de los productos en el carrito
$productosEnCarrito = [];
$total = 0;

foreach ($_SESSION['carrito'] as $item) {
    // Obtener los detalles del producto desde la base de datos
    $producto = Producto::getById($conn, $item['id']);

    if ($producto) {
        // Asegurarse de que 'cantidad' exista y sea válida
        $cantidad = isset($item['cantidad']) && $item['cantidad'] > 0 ? $item['cantidad'] : 1;

        $producto['cantidad'] = $cantidad;
        $producto['subtotal'] = $producto['precio'] * $cantidad;
        $total += $producto['subtotal'];
        $productosEnCarrito[] = $producto;
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="apple-touch-icon" href="assets/3.svg">
    <link rel="shortcut icon" type="image/x-icon" href="assets/3.ico">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Carrito de Compras</h2>
    <?php if (empty($productosEnCarrito)): ?>
        <p>No hay productos en el carrito.</p>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Subtotal</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productosEnCarrito as $producto): ?>
                    <tr>
                        <td><?= htmlspecialchars($producto['nombre']) ?></td>
                        <td>$<?= number_format($producto['precio'], 2) ?></td>
                        <td>$<?= number_format($producto['subtotal'], 2) ?></td>
                        <td>
                        <form method="POST">
                            <input type="hidden" name="id" value="<?= $producto['id'] ?>">
                            <button type="submit" name="eliminar" class="btn btn-danger">Eliminar</button>
                        </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="text-right">
            <h4>Total: $<?= number_format($total, 2) ?></h4>
            <a href="checkout.php" class="btn btn-success">Proceder al Pago</a>
        </div>
    <?php endif; ?>
    <a href="shop.php" class="btn btn-secondary mt-3">Seguir Comprando</a>
</div>

<script src="assets/js/jquery-1.11.0.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
