<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit();
}

// Incluir conexión a base de datos
include_once '../../config/conexion.php';

// Obtener información del usuario
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    // Si no se encuentra el usuario, cerrar sesión
    session_destroy();
    header("Location: ../../auth/login.php");
    exit();
}

// Obtener estadísticas rápidas
$hoy = date('Y-m-d');
$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM eventos WHERE usuario_id = ? AND fecha_inicio = ?");
$stmt->execute([$_SESSION['user_id'], $hoy]);
$eventosHoy = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM eventos WHERE usuario_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$totalEventos = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
?>

<?php include '../layouts/header.php'; ?>



<!-- Header Section -->
<div class="dashboard-header">
    <div class="header-left">
        <h2 class="dashboard-title">Tablero Principal</h2>
        <p class="dashboard-subtitle">Bienvenido, <?php echo htmlspecialchars($usuario['nombre']); ?>.</p>
    </div>
    <div class="header-right">
        <div class="user-info">
            <input type="text" placeholder="Buscar eventos..." class="search-bar">
            <div class="user-avatar">
                <?php echo strtoupper(substr($usuario['nombre'], 0, 1)); ?>
            </div>
        </div>
    </div>
</div>

<!-- Navigation Tabs -->
<div class="tab-navigation">
    <a href="dashboard.php" class="tab-btn active">
        <i class="fas fa-th-large"></i> Tablero
    </a>
    <a href="calendary.php" class="tab-btn">
        <i class="fas fa-calendar-alt"></i> Calendario
    </a>
    <a href="#" class="tab-btn">
        <i class="fas fa-chalkboard-teacher"></i> Aula Virtual
    </a>
    <a href="#" class="tab-btn">
        <i class="fas fa-cog"></i> Configuración
    </a>
</div>

<!-- Dashboard Content -->
<div class="dashboard-content">
    <!-- Stats Overview -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-calendar-day"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $eventosHoy; ?></h3>
                <p>Eventos Hoy</p>
                <div class="stat-trend">
                    <i class="fas fa-arrow-up"></i>
                    <span>+12%</span>
                </div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon pink">
                <i class="fas fa-tasks"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $totalEventos; ?></h3>
                <p>Total Eventos</p>
                <div class="stat-trend">
                    <i class="fas fa-arrow-up"></i>
                    <span>+8%</span>
                </div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
                <h3 id="eventos-completados">0</h3>
                <p>Completados</p>
                <div class="stat-trend">
                    <i class="fas fa-arrow-up"></i>
                    <span>+15%</span>
                </div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info">
                <h3 id="eventos-pendientes">3</h3>
                <p>Pendientes</p>
                <div class="stat-trend">
                    <i class="fas fa-arrow-down"></i>
                    <span>-5%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="content-grid">
        <!-- Left Column - Próximas Tareas (más grande) -->
        <div class="main-content">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-clock"></i> Próximas Tareas</h3>
                    <button class="btn" onclick="crearEventoRapido()">
                        <i class="fas fa-plus"></i> Agregar
                    </button>
                </div>
                <div class="card-content">
                    <div class="events-list">
                        <div class="event-item">
                            <div class="event-time">
                                <span class="time">09:00</span>
                                <span class="date">Hoy</span>
                            </div>
                            <div class="event-details">
                                <h4 class="event-title">Reunión de Equipo</h4>
                                <p class="event-description">Revisión de proyectos pendientes</p>
                            </div>
                            <div class="event-status orange"></div>
                        </div>
                        <div class="event-item">
                            <div class="event-time">
                                <span class="time">14:30</span>
                                <span class="date">Hoy</span>
                            </div>
                            <div class="event-details">
                                <h4 class="event-title">Presentación Cliente</h4>
                                <p class="event-description">Propuesta de proyecto nuevo</p>
                            </div>
                            <div class="event-status red"></div>
                        </div>
                        <div class="event-item">
                            <div class="event-time">
                                <span class="time">10:00</span>
                                <span class="date">Mañana</span>
                            </div>
                            <div class="event-details">
                                <h4 class="event-title">Revisión de Código</h4>
                                <p class="event-description">Control de calidad del desarrollo</p>
                            </div>
                            <div class="event-status green"></div>
                        </div>
                        <div class="event-item">
                            <div class="event-time">
                                <span class="time">16:00</span>
                                <span class="date">Mañana</span>
                            </div>
                            <div class="event-details">
                                <h4 class="event-title">Capacitación del Equipo</h4>
                                <p class="event-description">Sesión de formación en nuevas tecnologías</p>
                            </div>
                            <div class="event-status blue"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actividad Reciente -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-history"></i> Actividad Reciente</h3>
            </div>
            <div class="card-content">
                <div class="activity-list">
                    <div class="activity-item">
                        <div class="activity-icon created">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="activity-details">
                            <p><strong>Evento creado:</strong> "Reunión de Equipo"</p>
                            <span class="activity-time">hace 2 horas</span>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon completed">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="activity-details">
                            <p><strong>Tarea completada:</strong> "Revisión de documentos"</p>
                            <span class="activity-time">hace 4 horas</span>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon modified">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div class="activity-details">
                            <p><strong>Evento modificado:</strong> "Presentación Cliente"</p>
                            <span class="activity-time">ayer</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Row - Progreso del Mes -->
    <div class="bottom-content">
        <!-- Progreso del Mes -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-line"></i> Progreso del Mes</h3>
            </div>
            <div class="card-content">
                <div class="progress-content">
                    <div class="progress-item">
                        <div class="progress-label">
                            <span>Tareas Completadas</span>
                            <span class="progress-value">75%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 75%; background: var(--primary);"></div>
                        </div>
                    </div>
                    <div class="progress-item">
                        <div class="progress-label">
                            <span>Reuniones Asistidas</span>
                            <span class="progress-value">90%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 90%; background: #10b981;"></div>
                        </div>
                    </div>
                    <div class="progress-item">
                        <div class="progress-label">
                            <span>Proyectos Activos</span>
                            <span class="progress-value">60%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 60%; background: #f59e0b;"></div>
                        </div>
                    </div>
                    <div class="progress-item">
                        <div class="progress-label">
                            <span>Objetivos Mensuales</span>
                            <span class="progress-value">85%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 85%; background: #8b5cf6;"></div>
                        </div>
                    </div>
                    <div class="progress-item">
                        <div class="progress-label">
                            <span>Eficiencia del Equipo</span>
                            <span class="progress-value">78%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 78%; background: #06b6d4;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>

<script>
    // Función para crear evento rápido
    function crearEventoRapido() {
        const titulo = prompt('Título del evento:');
        if (titulo) {
            const fecha = prompt('Fecha (YYYY-MM-DD):', new Date().toISOString().split('T')[0]);
            const descripcion = prompt('Descripción (opcional):') || '';
            
            if (fecha) {
                // Aquí se enviaría a la API
                alert(`Evento "${titulo}" creado para el ${fecha}`);
            }
        }
    }

    function exportarEventos() {
        alert('Exportando eventos... (Funcionalidad en desarrollo)');
    }

    function mostrarEstadisticas() {
        alert('Mostrando estadísticas detalladas... (Funcionalidad en desarrollo)');
    }

    // Función para cargar estadísticas del dashboard
    async function cargarEstadisticas() {
        try {
            const response = await fetch('../../api/eventos.php?action=stats');
            const data = await response.json();
            
            if (data.success) {
                document.getElementById('eventos-completados').textContent = data.stats.completados || 0;
                document.getElementById('eventos-pendientes').textContent = data.stats.pendientes || 3;
            }
        } catch (error) {
            console.error('Error al cargar estadísticas:', error);
        }
    }

    // Inicializar cuando cargue la página
    document.addEventListener('DOMContentLoaded', function() {
        cargarEstadisticas();
        
        // Animar barras de progreso
        setTimeout(() => {
            const progressBars = document.querySelectorAll('.progress-fill');
            progressBars.forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0%';
                setTimeout(() => {
                    bar.style.width = width;
                }, 100);
            });
        }, 500);
        
        // Actualizar sidebar activo
        document.querySelectorAll('.nav-link').forEach(link => {
            link.classList.remove('active');
            if (link.href.includes('dashboard.php')) {
                link.classList.add('active');
            }
        });
    });
</script>
