<?php
/**
 * API Unificada para Categorías
 * Maneja todas las operaciones CRUD de categorías de eventos
 */

// Iniciar sesión solo si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Headers solo si no se han enviado ya
if (!headers_sent()) {
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Content-Type');
}

// Verificar autenticación
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Usuario no autenticado']);
    exit;
}

// Incluir conexión a base de datos
$pdo = include_once __DIR__ . '/../config/conexion.php';

$usuario_id = $_SESSION['user_id'];
$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            // Obtener todas las categorías del usuario
            $stmt = $pdo->prepare("
                SELECT * FROM categorias 
                WHERE usuario_id = ? AND activo = 1 
                ORDER BY nombre
            ");
            $stmt->execute([$usuario_id]);
            $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Si el usuario no tiene categorías, crear las categorías por defecto
            if (empty($categorias)) {
                $categorias_defecto = [
                    ['Trabajo', '#6a3bd6', 'Reuniones, proyectos y actividades laborales'],
                    ['Personal', '#10b981', 'Actividades personales y tiempo libre'],
                    ['Salud', '#f59e0b', 'Citas médicas y actividades relacionadas con la salud'],
                    ['Educación', '#8b5cf6', 'Cursos, clases y actividades de aprendizaje'],
                    ['Emergencia', '#ef4444', 'Eventos urgentes que requieren atención inmediata']
                ];
                
                $stmt_crear = $pdo->prepare('INSERT INTO categorias (usuario_id, nombre, color, descripcion, activo) VALUES (?, ?, ?, ?, 1)');
                
                foreach ($categorias_defecto as $categoria) {
                    $stmt_crear->execute([$usuario_id, $categoria[0], $categoria[1], $categoria[2]]);
                }
                
                // Volver a obtener las categorías recién creadas
                $stmt->execute([$usuario_id]);
                $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            
            echo json_encode([
                'success' => true,
                'categories' => $categorias
            ]);
            break;
            
        case 'POST':
            // Crear nueva categoría
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input) {
                $input = $_POST;
            }
            
            $nombre = trim($input['nombre'] ?? '');
            $color = $input['color'] ?? '#6a3bd6';
            $descripcion = trim($input['descripcion'] ?? '');
            
            if (empty($nombre)) {
                throw new Exception('El nombre de la categoría es obligatorio');
            }
            
            // Verificar si ya existe una categoría con el mismo nombre para este usuario
            $stmt = $pdo->prepare("SELECT id FROM categorias WHERE usuario_id = ? AND nombre = ? AND activo = 1");
            $stmt->execute([$usuario_id, $nombre]);
            if ($stmt->fetch()) {
                throw new Exception('Ya existe una categoría con ese nombre');
            }
            
            // Crear la categoría
            $stmt = $pdo->prepare("
                INSERT INTO categorias (usuario_id, nombre, color, descripcion)
                VALUES (?, ?, ?, ?)
            ");
            
            $stmt->execute([$usuario_id, $nombre, $color, $descripcion]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Categoría creada exitosamente',
                'id' => $pdo->lastInsertId()
            ]);
            break;
            
        case 'PUT':
            // Actualizar categoría existente
            $input = json_decode(file_get_contents('php://input'), true);
            $id = $_GET['id'] ?? null;
            
            if (!$id) {
                throw new Exception('ID de la categoría es requerido');
            }
            
            $nombre = trim($input['nombre'] ?? '');
            $color = $input['color'] ?? '#6a3bd6';
            $descripcion = trim($input['descripcion'] ?? '');
            
            if (empty($nombre)) {
                throw new Exception('El nombre de la categoría es obligatorio');
            }
            
            // Verificar que la categoría pertenece al usuario
            $stmt = $pdo->prepare("SELECT id FROM categorias WHERE id = ? AND usuario_id = ?");
            $stmt->execute([$id, $usuario_id]);
            if (!$stmt->fetch()) {
                throw new Exception('Categoría no encontrada');
            }
            
            // Verificar si ya existe otra categoría con el mismo nombre
            $stmt = $pdo->prepare("SELECT id FROM categorias WHERE usuario_id = ? AND nombre = ? AND id != ? AND activo = 1");
            $stmt->execute([$usuario_id, $nombre, $id]);
            if ($stmt->fetch()) {
                throw new Exception('Ya existe una categoría con ese nombre');
            }
            
            $stmt = $pdo->prepare("
                UPDATE categorias 
                SET nombre = ?, color = ?, descripcion = ?, actualizado_en = NOW()
                WHERE id = ? AND usuario_id = ?
            ");
            
            $stmt->execute([$nombre, $color, $descripcion, $id, $usuario_id]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Categoría actualizada exitosamente'
            ]);
            break;
            
        case 'DELETE':
            // Eliminar (desactivar) categoría
            $id = $_GET['id'] ?? null;
            
            if (!$id) {
                throw new Exception('ID de la categoría es requerido');
            }
            
            // En lugar de eliminar, desactivamos la categoría
            $stmt = $pdo->prepare("
                UPDATE categorias 
                SET activo = 0, actualizado_en = NOW()
                WHERE id = ? AND usuario_id = ?
            ");
            $stmt->execute([$id, $usuario_id]);
            
            if ($stmt->rowCount() > 0) {
                // También actualizamos los eventos que usan esta categoría
                $stmt = $pdo->prepare("
                    UPDATE eventos 
                    SET categoria_id = NULL 
                    WHERE categoria_id = ? AND usuario_id = ?
                ");
                $stmt->execute([$id, $usuario_id]);
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Categoría eliminada exitosamente'
                ]);
            } else {
                throw new Exception('Categoría no encontrada');
            }
            break;
            
        default:
            throw new Exception('Método no permitido');
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>