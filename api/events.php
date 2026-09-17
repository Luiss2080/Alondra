<?php
/**
 * API Unificada para Eventos
 * Maneja todas las operaciones CRUD de eventos y obtención de datos para el calendario
 */

require_once __DIR__ . '/../app/Calendar/EventValidator.php';

use App\Calendar\EventValidator;

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
            $action = $_GET['action'] ?? 'list';
            
            switch ($action) {
                case 'calendar':
                    // Obtener eventos para el calendario por año y mes
                    $año = $_GET['año'] ?? date('Y');
                    $mes = $_GET['mes'] ?? date('n');
                    
                    $stmt = $pdo->prepare("
                        SELECT e.*, c.nombre as categoria_nombre, c.color as categoria_color
                        FROM eventos e 
                        LEFT JOIN categorias c ON e.categoria_id = c.id
                        WHERE e.usuario_id = ? AND YEAR(e.fecha_inicio) = ? AND MONTH(e.fecha_inicio) = ?
                        ORDER BY e.fecha_inicio, e.hora_inicio
                    ");
                    $stmt->execute([$usuario_id, $año, $mes]);
                    $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    echo json_encode([
                        'success' => true,
                        'events' => $eventos
                    ]);
                    break;
                    
                case 'recent':
                    // Obtener eventos recientes (últimos 10)
                    $limit = $_GET['limit'] ?? 10;
                    $stmt = $pdo->prepare("
                        SELECT e.*, c.nombre as categoria_nombre, c.color as categoria_color
                        FROM eventos e 
                        LEFT JOIN categorias c ON e.categoria_id = c.id
                        WHERE e.usuario_id = ?
                        ORDER BY e.creado_en DESC
                        LIMIT ?
                    ");
                    $stmt->execute([$usuario_id, (int)$limit]);
                    $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    echo json_encode([
                        'success' => true,
                        'events' => $eventos
                    ]);
                    break;
                    
                case 'stats':
                    // Obtener estadísticas para el dashboard
                    $hoy = date('Y-m-d');
                    
                    // Eventos de hoy
                    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM eventos WHERE usuario_id = ? AND fecha_inicio = ?");
                    $stmt->execute([$usuario_id, $hoy]);
                    $eventosHoy = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
                    
                    // Total de eventos
                    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM eventos WHERE usuario_id = ?");
                    $stmt->execute([$usuario_id]);
                    $totalEventos = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
                    
                    // Eventos completados (simulado)
                    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM eventos WHERE usuario_id = ? AND fecha_inicio < ?");
                    $stmt->execute([$usuario_id, $hoy]);
                    $completados = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
                    
                    // Eventos pendientes
                    $pendientes = $totalEventos - $completados;
                    
                    echo json_encode([
                        'success' => true,
                        'stats' => [
                            'hoy' => $eventosHoy,
                            'total' => $totalEventos,
                            'completados' => $completados,
                            'pendientes' => $pendientes
                        ]
                    ]);
                    break;
                    
                default:
                    // Listar todos los eventos del usuario
                    $stmt = $pdo->prepare("
                        SELECT e.*, c.nombre as categoria_nombre, c.color as categoria_color
                        FROM eventos e 
                        LEFT JOIN categorias c ON e.categoria_id = c.id
                        WHERE e.usuario_id = ?
                        ORDER BY e.fecha_inicio DESC, e.hora_inicio DESC
                    ");
                    $stmt->execute([$usuario_id]);
                    $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    echo json_encode([
                        'success' => true,
                        'eventos' => $eventos
                    ]);
                    break;
            }
            break;
            
        case 'POST':
            // Crear nuevo evento
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input) {
                $input = $_POST;
            }
            
            $titulo = $input['titulo'] ?? '';
            $descripcion = $input['descripcion'] ?? '';
            $fecha_inicio = $input['fecha_inicio'] ?? '';
            $hora_inicio = $input['hora_inicio'] ?? null;
            $hora_fin = $input['hora_fin'] ?? null;
            $tipo = $input['tipo'] ?? 'evento';
            $categoria_id = !empty($input['categoria_id']) ? $input['categoria_id'] : null;

            $errores = EventValidator::validate([
                'titulo' => $titulo,
                'fecha_inicio' => $fecha_inicio,
                'hora_inicio' => $hora_inicio,
                'hora_fin' => $hora_fin,
                'tipo' => $tipo,
            ]);
            if (!empty($errores)) {
                throw new Exception(implode(' ', $errores));
            }

            // Obtener color de la categoría si existe
            $color = '#6a3bd6'; // Color por defecto
            if ($categoria_id) {
                $stmt = $pdo->prepare("SELECT color FROM categorias WHERE id = ? AND usuario_id = ?");
                $stmt->execute([$categoria_id, $usuario_id]);
                $categoria = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($categoria) {
                    $color = $categoria['color'];
                }
            }

            $stmt = $pdo->prepare("
                INSERT INTO eventos (usuario_id, titulo, descripcion, fecha_inicio, hora_inicio, hora_fin, tipo, categoria_id, color)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $stmt->execute([
                $usuario_id,
                $titulo,
                $descripcion,
                $fecha_inicio,
                $hora_inicio,
                $hora_fin,
                $tipo,
                $categoria_id,
                $color
            ]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Evento creado exitosamente',
                'id' => $pdo->lastInsertId()
            ]);
            break;
            
        case 'PUT':
            // Actualizar evento existente
            $input = json_decode(file_get_contents('php://input'), true);
            $id = $_GET['id'] ?? null;
            
            if (!$id) {
                throw new Exception('ID del evento es requerido');
            }
            
            $titulo = $input['titulo'] ?? '';
            $descripcion = $input['descripcion'] ?? '';
            $fecha_inicio = $input['fecha_inicio'] ?? '';
            $hora_inicio = $input['hora_inicio'] ?? null;
            $hora_fin = $input['hora_fin'] ?? null;
            $tipo = $input['tipo'] ?? 'evento';
            $categoria_id = !empty($input['categoria_id']) ? $input['categoria_id'] : null;

            $errores = EventValidator::validate([
                'titulo' => $titulo,
                'fecha_inicio' => $fecha_inicio,
                'hora_inicio' => $hora_inicio,
                'hora_fin' => $hora_fin,
                'tipo' => $tipo,
            ]);
            if (!empty($errores)) {
                throw new Exception(implode(' ', $errores));
            }

            // Obtener color de la categoría si existe
            $color = '#6a3bd6'; // Color por defecto
            if ($categoria_id) {
                $stmt = $pdo->prepare("SELECT color FROM categorias WHERE id = ? AND usuario_id = ?");
                $stmt->execute([$categoria_id, $usuario_id]);
                $categoria = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($categoria) {
                    $color = $categoria['color'];
                }
            }

            $stmt = $pdo->prepare("
                UPDATE eventos
                SET titulo = ?, descripcion = ?, fecha_inicio = ?, hora_inicio = ?, hora_fin = ?,
                    tipo = ?, categoria_id = ?, color = ?, actualizado_en = NOW()
                WHERE id = ? AND usuario_id = ?
            ");
            
            $stmt->execute([
                $titulo,
                $descripcion,
                $fecha_inicio,
                $hora_inicio,
                $hora_fin,
                $tipo,
                $categoria_id,
                $color,
                $id,
                $usuario_id
            ]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Evento actualizado exitosamente'
            ]);
            break;
            
        case 'DELETE':
            // Eliminar evento
            $id = $_GET['id'] ?? null;
            
            if (!$id) {
                throw new Exception('ID del evento es requerido');
            }
            
            $stmt = $pdo->prepare("DELETE FROM eventos WHERE id = ? AND usuario_id = ?");
            $stmt->execute([$id, $usuario_id]);
            
            if ($stmt->rowCount() > 0) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Evento eliminado exitosamente'
                ]);
            } else {
                throw new Exception('Evento no encontrado');
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