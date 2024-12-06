<?php
class Producto {
    private $id;
    private $nombre;
    private $descripcion;
    private $precio;
    private $imagen_url;

    public function __construct($nombre, $descripcion, $precio, $imagen_url) {
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->precio = $precio;
        $this->imagen_url = $imagen_url;
    }

    public function save($conn) {
        $sql = "INSERT INTO productos (nombre, descripcion, precio, imagen_url) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssds', $this->nombre, $this->descripcion, $this->precio, $this->imagen_url);
        return $stmt->execute();
    }

    public static function getAll($conn) {
        $sql = "SELECT * FROM productos";
        $result = $conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function getPaginated($conn, $offset, $limit) {
        $stmt = $conn->prepare("SELECT * FROM productos LIMIT ? OFFSET ?");
        $stmt->bind_param('ii', $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function getTotalCount($conn) {
        $result = $conn->query("SELECT COUNT(*) AS total FROM productos");
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    public static function search($conn, $query) {
        $sql = "SELECT * FROM productos WHERE nombre LIKE ? OR descripcion LIKE ?";
        $stmt = $conn->prepare($sql);
        $searchQuery = '%' . $query . '%';
        $stmt->bind_param('ss', $searchQuery, $searchQuery);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function getById($conn, $id) {
        $sql = "SELECT * FROM productos WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
?>
