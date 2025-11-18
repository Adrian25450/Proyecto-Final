<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';

session_start();

// Verificar que el usuario esté autenticado y sea profesor
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'profesor') {
    http_response_code(403);
    echo json_encode([
        "success" => false,
        "message" => "Acceso denegado. Solo profesores pueden ver documentos."
    ]);
    exit();
}

$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    
    $query = "SELECT * FROM vista_documentos ORDER BY fecha_subida DESC";
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    $documentos = [];
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $documentos[] = [
            "id" => $row['id'],
            "titulo" => $row['titulo'],
            "descripcion" => $row['descripcion'],
            "nombre_archivo" => $row['nombre_archivo'],
            "tipo_archivo" => $row['tipo_archivo'],
            "tamano" => $row['tamano'],
            "fecha_subida" => $row['fecha_subida'],
            "usuario" => [
                "username" => $row['username'],
                "nombre" => $row['nombre_usuario']
            ]
        ];
    }
    
    http_response_code(200);
    echo json_encode([
        "success" => true,
        "count" => count($documentos),
        "data" => $documentos
    ]);
    
} else {
    http_response_code(405);
    echo json_encode([
        "success" => false,
        "message" => "Método no permitido"
    ]);
}
?>
