<?php
session_start();
require 'conexion.php';
require 'Producto.php';
require 'tcpdf/tcpdf.php';

// Inicializar el carrito si no existe
if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    die('El carrito está vacío. No se puede proceder al pago.');
}

try {
    // Inicializa TCPDF
    $pdf = new TCPDF();
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('KLYL Boutique');
    $pdf->SetTitle('Detalles de su compra en KLYL Boutique');
    $pdf->SetSubject('Resumen de Compra');
    $pdf->SetKeywords('KLYL, Compra, PDF');

    // Configuración del documento
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->SetMargins(15, 15, 15);
    $pdf->SetAutoPageBreak(TRUE, 15);

    // Agrega una página
    $pdf->AddPage();

    // Añade logotipo
    $logoPath = './assets/logo.png'; // Ruta al archivo del logotipo
    if (file_exists($logoPath)) {
        $pdf->Image($logoPath, 25, 5, 50); // Posición X, Y y ancho del logotipo
    }

    // Añadir título debajo del logotipo
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->Cell(0, 15, 'KLYL Boutique', 0, 1, 'C');
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(0, 10, 'Detalles de la compra', 0, 1, 'C');
    $pdf->Ln(5); // Espacio adicional

    // Fecha de la compra
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 10, 'Fecha de compra: ' . date('d/m/Y H:i:s'), 0, 1, 'L');

    // Tabla con detalles del carrito
    $html = '
    <head>
    
        <table border="1" cellspacing="3" cellpadding="4" style="width: 100%;">
            <thead>
                <tr style="background-color: #28a745; color: #fff; text-align: center;">
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
    ';

    // Variables para el total
    $total = 0;

    // Itera sobre los productos en el carrito
    foreach ($_SESSION['carrito'] as $item) {
        if (!isset($item['cantidad'])) {
            $item['cantidad'] = 1;
        }

        $producto = Producto::getById($conn, $item['id']);
        $cantidad = $item['cantidad'];
        $precio_unitario = $producto['precio'];
        $subtotal = $cantidad * $precio_unitario;
        $total += $subtotal;

        $html .= '
            <tr style="text-align: center;">
                <td>' . htmlspecialchars($producto['nombre']) . '</td>
                <td>' . $cantidad . '</td>
                <td>$' . number_format($precio_unitario, 2) . '</td>
                <td>$' . number_format($subtotal, 2) . '</td>
            </tr>
        ';
    }

    // Cierra la tabla y agrega el total
    $html .= '
            </tbody>
        </table>
        <h3 style="text-align: right; margin-top: 20px;">Total: $' . number_format($total, 2) . '</h3>
    ';

    // Agrega el contenido HTML al PDF
    $pdf->writeHTML($html, true, false, true, false, '');

    // Salida del archivo PDF
    $pdf->Output('detalle_compra_' . time() . '.pdf', 'I'); // 'I' para mostrarlo en el navegador

} catch (Exception $e) {
    echo 'Error al generar el PDF: ' . $e->getMessage();
}
?>

