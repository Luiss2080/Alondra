<?php
/**
 * Script de inicialización para usuarios existentes
 * Crea categorías por defecto para usuarios que no las tengan
 */

// Iniciar sesión solo si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Headers
header('Content-Type: application/json; charset=utf-8');

// Verificar autenticación
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Usuario no autenticado']);
    exit;
}

// Incluir conexión a base de datos
$pdo = include_once __DIR__ . '/../config/conexion.php';

$usuario_id = $_SESSION['user_id'];

try {
    // Verificar si el usuario ya tiene categorías
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM categorias WHERE usuario_id = ? AND activo = 1");
    $stmt->execute([$usuario_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result['total'] > 0) {
        echo json_encode([
            'success' => true,
            'message' => 'El usuario ya tiene categorías',
            'categories_count' => $result['total']
        ]);
        exit;
    }
    
    // Crear categorías por defecto
    $categorias_defecto = [
        ['Trabajo', '#6a3bd6', 'Reuniones, proyectos y actividades laborales'],
        ['Personal', '#10b981', 'Actividades personales y tiempo libre'],
        ['Salud', '#f59e0b', 'Citas médicas y actividades relacionadas con la salud'],
        ['Educación', '#8b5cf6', 'Cursos, clases y actividades de aprendizaje'],
        ['Emergencia', '#ef4444', 'Eventos urgentes que requieren atención inmediata']
    ];
    
    $stmt_crear = $pdo->prepare('INSERT INTO categorias (usuario_id, nombre, color, descripcion, activo) VALUES (?, ?, ?, ?, 1)');
    $categorias_creadas = 0;
    
    foreach ($categorias_defecto as $categoria) {
        $stmt_crear->execute([$usuario_id, $categoria[0], $categoria[1], $categoria[2]]);
        $categorias_creadas++;
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Categorías por defecto creadas exitosamente',
        'categories_created' => $categorias_creadas
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>