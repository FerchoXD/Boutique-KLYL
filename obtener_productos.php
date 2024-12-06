<?php
require 'conexion.php';
require 'Producto.php';

$productos = Producto::getAll($conn);
header('Content-Type: application/json');
echo json_encode($productos);
?>
