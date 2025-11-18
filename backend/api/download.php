<?php
include_once '../config/database.php';

session_start();

// Verificar que el usuario esté autenticado y sea profesor
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'profesor') {
    http_response_code(403);
    echo "Acceso denegado";
    exit();
}

if (isset($_GET['id'])) {
    $database = new Database();
    $db = $database->getConnection();
    
    $id = htmlspecialchars(strip_tags($_GET['id']));
    
    $query = "SELECT nombre_archivo, ruta_archivo, tipo_archivo FROM documentos WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $file_path = $row['ruta_archivo'];
        
        if (file_exists($file_path)) {
            header('Content-Description: File Transfer');
            header('Content-Type: ' . $row['tipo_archivo']);
            header('Content-Disposition: attachment; filename="' . basename($row['nombre_archivo']) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file_path));
            readfile($file_path);
            exit();
        } else {
            http_response_code(404);
            echo "Archivo no encontrado";
        }
    } else {
        http_response_code(404);
        echo "Documento no encontrado";
    }
} else {
    http_response_code(400);
    echo "ID de documento no proporcionado";
}
?>
