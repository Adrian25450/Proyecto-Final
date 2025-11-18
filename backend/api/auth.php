<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';

// Iniciar sesión
session_start();

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (!empty($data->username) && !empty($data->password)) {
        
        $username = htmlspecialchars(strip_tags($data->username));
        $password = $data->password;
        
        $query = "SELECT id, username, password, nombre, email, rol FROM usuarios WHERE username = :username";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Verificar contraseña
            if (password_verify($password, $row['password'])) {
                
                // Crear sesión
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['nombre'] = $row['nombre'];
                $_SESSION['rol'] = $row['rol'];
                
                http_response_code(200);
                echo json_encode([
                    "success" => true,
                    "message" => "Login exitoso",
                    "data" => [
                        "id" => $row['id'],
                        "username" => $row['username'],
                        "nombre" => $row['nombre'],
                        "email" => $row['email'],
                        "rol" => $row['rol']
                    ]
                ]);
            } else {
                http_response_code(401);
                echo json_encode([
                    "success" => false,
                    "message" => "Contraseña incorrecta"
                ]);
            }
        } else {
            http_response_code(404);
            echo json_encode([
                "success" => false,
                "message" => "Usuario no encontrado"
            ]);
        }
    } else {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Datos incompletos"
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
