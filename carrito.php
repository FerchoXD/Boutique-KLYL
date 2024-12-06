<?php
session_start();
require 'conexion.php';
require 'Producto.php';

// Verifica si el carrito está inicializado
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Obtén los parámetros de la URL
$accion = $_GET['accion'] ?? null;
$id = $_GET['id'] ?? null;

if ($accion === 'agregar' && $id) {
    // Busca el producto en la base de datos
    $producto = Producto::getById($conn, $id);
    
    if ($producto) {
        // Agrega el producto al carrito
        $_SESSION['carrito'][] = $producto;
    }
    
    // Redirige de vuelta a la tienda
    header("Location: shop.php");
    exit();
}

// Si necesitas manejar otras acciones (como eliminar productos), agrégalas aquí
?>
