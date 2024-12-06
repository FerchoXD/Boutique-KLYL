<?php
require 'conexion.php';
require 'Producto.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $imagen_url = $_POST['imagen_url'];

    if (empty($nombre) || empty($descripcion) || empty($precio) || empty($imagen_url)) {
        echo "Todos los campos son obligatorios.";
        exit();
    }

    $producto = new Producto($nombre, $descripcion, $precio, $imagen_url);
    if ($producto->save($conn)) {
        echo "Producto guardado con éxito.";
    } else {
        echo "Error al guardar el producto.";
    }
}
?>
