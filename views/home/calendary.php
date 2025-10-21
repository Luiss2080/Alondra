<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit();
}

// Incluir conexión a base de datos
$pdo = include_once '../../config/conexion.php';

// Obtener información del usuario
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    session_destroy();
    header("Location: ../../auth/login.php");
    exit();
}
?>

<?php include '../layouts/header.php'; ?>

<!-- Header Section -->
<div class="dashboard-header">
    <div class="header-left">
        <h2 class="dashboard-title">Alondra</h2>
        <p class="dashboard-subtitle">Organiza tu agenda de manera inteligente. Gestiona eventos y tareas con facilidad.</p>
    </div>
    <div class="header-right">
        <div class="user-info">
            <input type="text" placeholder="Buscar eventos..." class="search-bar" id="search-events">
            <div class="user-avatar">
                <?php echo strtoupper(substr($usuario['nombre'], 0, 1)); ?>
            </div>
        </div>
    </div>
</div>




<!-- Dashboard Content -->
<div class="dashboard-content">
    <!-- Calendar Layout with Sidebar -->
    <div class="calendar-layout-full">
        <!-- Calendario Principal -->
        <div class="calendar-main-full">
            <div class="card">
                <div class="card-header">
                    <div class="header-left">
                        <h3 class="card-title"><i class="fas fa-calendar"></i> Calendario de Eventos</h3>
                        <div class="calendar-legend">
                            <span class="legend-item work"><i class="fas fa-circle"></i> Trabajo</span>
                            <span class="legend-item personal"><i class="fas fa-circle"></i> Personal</span>
                            <span class="legend-item health"><i class="fas fa-circle"></i> Salud</span>
                            <span class="legend-item education"><i class="fas fa-circle"></i> Educación</span>
                        </div>
                    </div>
                    <div class="header-actions">
                        <button class="btn" onclick="window.location.href='frmEvento.php'">
                            <i class="fas fa-plus"></i> Agregar Evento
                        </button>
                    </div>
                </div>
                <div class="card-content">
                    <div class="calendar-container">
                        <div class="calendar-navigation">
                            <button class="nav-btn" onclick="cambiarMes(-1)">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <h3 id="calendar-month-year"></h3>
                            <button class="nav-btn" onclick="cambiarMes(1)">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                        
                        <div class="calendar-grid">
                            <div class="calendar-header-days">
                                <div class="day-header">Dom</div>
                                <div class="day-header">Lun</div>
                                <div class="day-header">Mar</div>
                                <div class="day-header">Mié</div>
                                <div class="day-header">Jue</div>
                                <div class="day-header">Vie</div>
                                <div class="day-header">Sáb</div>
                            </div>
                            <div id="calendar-days" class="calendar-days">
                                <!-- Los días se generan dinámicamente con JavaScript -->
                            </div>
                        </div>
                        
                        <div class="calendar-loading" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i>
                            <span>Cargando eventos...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>

<script>
// Calendario dinámico conectado con la API
let currentDate = new Date();
let eventos = [];

// Inicializar el calendario
document.addEventListener('DOMContentLoaded', function() {
    inicializarCalendario();
    cargarEventos(currentDate.getFullYear(), currentDate.getMonth() + 1);
});

// Cargar eventos desde la API
async function cargarEventos(año, mes) {
    try {
        // Mostrar indicador de carga
        const loading = document.querySelector('.calendar-loading');
        if (loading) loading.style.display = 'block';
        
        const response = await fetch(`../../api/events.php?action=calendar&año=${año}&mes=${mes}`);
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        const responseText = await response.text();
        console.log('Respuesta cruda de la API:', responseText);
        
        // Intentar extraer JSON limpio si hay warnings de PHP
        let jsonStartIndex = responseText.indexOf('{');
        let jsonEndIndex = responseText.lastIndexOf('}') + 1;
        
        if (jsonStartIndex >= 0 && jsonEndIndex > jsonStartIndex) {
            const cleanJson = responseText.substring(jsonStartIndex, jsonEndIndex);
            const data = JSON.parse(cleanJson);
            
            if (data.success && (data.events || data.eventos)) {
                eventos = data.events || data.eventos || [];
                mostrarCalendario();
                console.log(`✓ Cargados ${eventos.length} eventos para ${mes}/${año}`);
                
                // Solo mostrar error si realmente no hay eventos y debería haberlos
                if (eventos.length === 0) {
                    console.warn('No se encontraron eventos para este mes');
                }
                return; // Salir exitosamente
            }
        }
        
        // Si llegamos aquí, hubo un problema con el JSON
        console.error('Respuesta no válida:', responseText);
        eventos = [];
        mostrarCalendario();
        
    } catch (error) {
        console.error('Error cargando eventos:', error);
        // Solo mostrar notificación si realmente no se pudieron cargar eventos
        eventos = [];
        mostrarCalendario();
    } finally {
        // Ocultar indicador de carga
        const loading = document.querySelector('.calendar-loading');
        if (loading) loading.style.display = 'none';
    }
}

// Inicializar el calendario
function inicializarCalendario() {
    actualizarTituloMes();
    mostrarCalendario();
}

// Actualizar título del mes
function actualizarTituloMes() {
    const meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                  'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    const monthYearElement = document.getElementById('calendar-month-year');
    if (monthYearElement) {
        monthYearElement.textContent = `${meses[currentDate.getMonth()]} ${currentDate.getFullYear()}`;
    }
}

// Mostrar el calendario
function mostrarCalendario() {
    actualizarTituloMes();
    
    const calendarDays = document.getElementById('calendar-days');
    if (!calendarDays) {
        console.error('No se encontró el elemento calendar-days');
        return;
    }
    
    // Limpiar calendario
    calendarDays.innerHTML = '';
    
    // Obtener primer y último día del mes
    const año = currentDate.getFullYear();
    const mes = currentDate.getMonth();
    const primerDia = new Date(año, mes, 1);
    const ultimoDia = new Date(año, mes + 1, 0);
    const diaSemanaInicio = primerDia.getDay();
    
    // Días del mes anterior (grises)
    const diasMesAnterior = new Date(año, mes, 0).getDate();
    for (let i = diaSemanaInicio - 1; i >= 0; i--) {
        const cell = crearCeldaCalendario(diasMesAnterior - i, true, año, mes - 1);
        calendarDays.appendChild(cell);
    }
    
    // Días del mes actual
    for (let dia = 1; dia <= ultimoDia.getDate(); dia++) {
        const cell = crearCeldaCalendario(dia, false, año, mes);
        calendarDays.appendChild(cell);
    }
    
    // Días del mes siguiente (grises)
    const celdasUsadas = diaSemanaInicio + ultimoDia.getDate();
    const celdasRestantes = celdasUsadas % 7 === 0 ? 0 : 7 - (celdasUsadas % 7);
    for (let dia = 1; dia <= celdasRestantes; dia++) {
        const cell = crearCeldaCalendario(dia, true, año, mes + 1);
        calendarDays.appendChild(cell);
    }
}

// Crear celda del calendario
function crearCeldaCalendario(dia, otroMes, año, mes) {
    const cell = document.createElement('div');
    cell.className = 'calendar-day-full';
    if (otroMes) cell.classList.add('other-month');
    
    const fechaCompleta = new Date(año, mes, dia);
    const hoy = new Date();
    
    // Marcar día actual
    if (!otroMes && 
        fechaCompleta.toDateString() === hoy.toDateString()) {
        cell.classList.add('today');
    }
    
    const fechaISO = fechaCompleta.toISOString().split('T')[0];
    
    cell.innerHTML = `
        <div class="calendar-day-number">${dia}</div>
        <div class="calendar-day-events"></div>
    `;
    
    // Agregar eventos si no es de otro mes
    if (!otroMes) {
        const eventosDelDia = eventos.filter(evento => {
            return evento.fecha_inicio === fechaISO;
        });
        
        const eventsContainer = cell.querySelector('.calendar-day-events');
        eventosDelDia.forEach(evento => {
            const eventElement = document.createElement('div');
            eventElement.className = 'calendar-day-event';
            
            // Truncar título si es muy largo
            const titulo = evento.titulo.length > 15 ? evento.titulo.substring(0, 12) + '...' : evento.titulo;
            eventElement.textContent = titulo;
            eventElement.title = evento.titulo; // Tooltip con título completo
            
            // Aplicar color de la categoría o color del evento
            const color = evento.categoria_color || evento.color || '#6a3bd6';
            eventElement.style.backgroundColor = color;
            eventElement.style.color = '#ffffff';
            eventElement.style.fontSize = '11px';
            eventElement.style.padding = '2px 6px';
            eventElement.style.margin = '2px 0';
            eventElement.style.borderRadius = '3px';
            eventElement.style.cursor = 'pointer';
            
            eventElement.onclick = (e) => {
                e.stopPropagation();
                verEvento(evento);
            };
            
            eventsContainer.appendChild(eventElement);
        });
    }
    
    // Agregar click para crear evento
    cell.onclick = () => {
        if (!otroMes) {
            agregarEvento(fechaISO);
        }
    };
    
    return cell;
}

// Navegación del calendario
function cambiarMes(direccion) {
    currentDate.setMonth(currentDate.getMonth() + direccion);
    cargarEventos(currentDate.getFullYear(), currentDate.getMonth() + 1);
}

// Funciones de compatibilidad
function mesAnterior() {
    cambiarMes(-1);
}

function mesSiguiente() {
    cambiarMes(1);
}

// Agregar evento
function agregarEvento(fecha) {
    // Redirigir al formulario de eventos con la fecha preseleccionada
    window.location.href = `frmEvento.php?fecha=${fecha}`;
}

// Crear evento via API
async function crearEvento(eventoData) {
    try {
        const response = await fetch('../../api/events.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(eventoData)
        });
        
        const result = await response.json();
        
        if (result.success) {
            mostrarNotificacion('Evento creado exitosamente!', 'success');
            cargarEventos(currentDate.getFullYear(), currentDate.getMonth() + 1);
        } else {
            mostrarNotificacion('Error: ' + (result.error || result.message), 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        mostrarNotificacion('Error al crear evento', 'error');
    }
}

// Ver detalles del evento
function verEvento(evento) {
    const fechaFormateada = new Date(evento.fecha_inicio + 'T00:00:00').toLocaleDateString('es-ES', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    const horaInicio = evento.hora_inicio || 'Sin hora';
    const horaFin = evento.hora_fin ? ` - ${evento.hora_fin}` : '';
    const categoria = evento.categoria_nombre || 'Sin categoría';
    
    const mensaje = `📅 ${evento.titulo}

📆 Fecha: ${fechaFormateada}
⏰ Hora: ${horaInicio}${horaFin}
🏷️ Tipo: ${evento.tipo || 'evento'}
📂 Categoría: ${categoria}
📝 Descripción: ${evento.descripcion || 'Sin descripción'}`;

    alert(mensaje);
}

// Mostrar notificación
function mostrarNotificacion(mensaje, tipo) {
    const notificacion = document.createElement('div');
    notificacion.style.position = 'fixed';
    notificacion.style.top = '20px';
    notificacion.style.right = '20px';
    notificacion.style.padding = '12px 20px';
    notificacion.style.borderRadius = '8px';
    notificacion.style.color = 'white';
    notificacion.style.fontWeight = '500';
    notificacion.style.zIndex = '3000';
    notificacion.textContent = mensaje;
    
    if (tipo === 'success') {
        notificacion.style.background = '#10b981';
    } else {
        notificacion.style.background = '#ef4444';
    }
    
    document.body.appendChild(notificacion);
    
    setTimeout(() => {
        notificacion.remove();
    }, 3000);
}
</script>

<!-- Include Main JavaScript -->
<script src="../../public/js/main.js"></script>

</body>
</html>
