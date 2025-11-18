<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';

session_start();

// Verificar que el usuario esté autenticado y sea estudiante
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'estudiante') {
    http_response_code(403);
    echo json_encode([
        "success" => false,
        "message" => "Acceso denegado. Solo estudiantes pueden subir documentos."
    ]);
    exit();
}

$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Verificar que se haya subido un archivo
    if (isset($_FILES['documento']) && $_FILES['documento']['error'] === UPLOAD_ERR_OK) {
        
        $titulo = htmlspecialchars(strip_tags($_POST['titulo']));
        $descripcion = htmlspecialchars(strip_tags($_POST['descripcion']));
        
        $archivo = $_FILES['documento'];
        $nombre_archivo = basename($archivo['name']);
        $extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
        $tamano = $archivo['size'];
        $tipo = $archivo['type'];
        
        // Validar extensiones permitidas
        $extensiones_permitidas = ['pdf', 'doc', 'docx', 'txt', 'xls', 'xlsx', 'ppt', 'pptx', 'zip'];
        
        if (!in_array(strtolower($extension), $extensiones_permitidas)) {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "Tipo de archivo no permitido. Solo se permiten: " . implode(', ', $extensiones_permitidas)
            ]);
            exit();
        }
        
        // Validar tamaño (máximo 10MB)
        if ($tamano > 10485760) {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "El archivo es demasiado grande. Máximo 10MB."
            ]);
            exit();
        }
        
        // Crear carpeta de uploads si no existe
        $upload_dir = '../uploads/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        // Generar nombre único para el archivo
        $nuevo_nombre = uniqid() . '_' . $nombre_archivo;
        $ruta_destino = $upload_dir . $nuevo_nombre;
        
        // Mover archivo
        if (move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
            
            // Guardar información en la base de datos
            $query = "INSERT INTO documentos (titulo, descripcion, nombre_archivo, ruta_archivo, tipo_archivo, tamano, usuario_id) 
                      VALUES (:titulo, :descripcion, :nombre_archivo, :ruta_archivo, :tipo_archivo, :tamano, :usuario_id)";
            
            $stmt = $db->prepare($query);
            $stmt->bindParam(':titulo', $titulo);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':nombre_archivo', $nombre_archivo);
            $stmt->bindParam(':ruta_archivo', $ruta_destino);
            $stmt->bindParam(':tipo_archivo', $tipo);
            $stmt->bindParam(':tamano', $tamano);
            $stmt->bindParam(':usuario_id', $_SESSION['user_id']);
            
            if ($stmt->execute()) {
                http_response_code(201);
                echo json_encode([
                    "success" => true,
                    "message" => "Documento subido exitosamente",
                    "data" => [
                        "id" => $db->lastInsertId(),
                        "titulo" => $titulo,
                        "nombre_archivo" => $nombre_archivo
                    ]
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "success" => false,
                    "message" => "Error al guardar en la base de datos"
                ]);
            }
        } else {
            http_response_code(500);
            echo json_encode([
                "success" => false,
                "message" => "Error al mover el archivo"
            ]);
        }
    } else {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "No se ha enviado ningún archivo"
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        "success" => false,
        "message" => "Método no permitido"
    ]);
}
?>
