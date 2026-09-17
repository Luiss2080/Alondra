<?php
// Verificar sesión
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../auth/login.php');
    exit;
}

// Obtener información del usuario
$user_name = $_SESSION['user_name'] ?? 'Usuario';
$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Eventos - Alondra</title>
    <link rel="stylesheet" href="../../public/css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include '../layouts/header.php'; ?>

    <div class="main-content">
        <div class="content-wrapper">
            <!-- Page Header -->
            <div class="page-header">
                <div class="page-title">
                    <h1><i class="fas fa-calendar-plus"></i> Gestión de Eventos</h1>
                    <p>Crea y administra tus eventos y tareas</p>
                </div>
                <div class="page-actions">
                    <button class="btn" onclick="mostrarFormularioCategoria()">
                        <i class="fas fa-tags"></i> Nueva Categoría
                    </button>
                    <button class="btn" onclick="window.location.href='calendary.php'">
                        <i class="fas fa-calendar"></i> Ver Calendario
                    </button>
                </div>
            </div>

            <!-- Main Content -->
            <div class="events-management">
                <!-- Event Form Section -->
                <div class="form-section">
                    <div class="card form-card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-plus-circle"></i> Nuevo Evento</h3>
                            <div class="card-actions">
                                <button class="btn-icon" onclick="limpiarFormulario()" title="Limpiar formulario">
                                    <i class="fas fa-eraser"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-content">
                            <form id="event-form" method="POST">
                                <!-- Título del evento -->
                                <div class="form-group">
                                    <label for="event-title">Título del evento *</label>
                                    <input type="text" id="event-title" name="titulo" required placeholder="Ej: Reunión de trabajo, Cita médica...">
                                </div>

                                <!-- Fecha y horas -->
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="event-date">Fecha *</label>
                                        <input type="date" id="event-date" name="fecha_inicio" required min="<?= date('Y-m-d') ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="event-time-start">Hora inicio</label>
                                        <input type="time" id="event-time-start" name="hora_inicio" value="09:00">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="event-time-end">Hora fin</label>
                                        <input type="time" id="event-time-end" name="hora_fin" value="10:00">
                                    </div>
                                    <div class="form-group">
                                        <label for="event-type">Tipo *</label>
                                        <select id="event-type" name="tipo" required>
                                            <option value="evento">📅 Evento</option>
                                            <option value="tarea">✅ Tarea</option>
                                            <option value="recordatorio">⏰ Recordatorio</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Categoría -->
                                <div class="form-group">
                                    <label for="event-category">Categoría *</label>
                                    <div class="category-selector-full">
                                        <!-- Custom Category Dropdown -->
                                        <div class="custom-select-wrapper">
                                            <div class="custom-select" id="custom-category-select" onclick="toggleCategoryDropdown()">
                                                <div class="selected-option" id="selected-category">
                                                    <span class="category-color-dot" style="background: #ccc;"></span>
                                                    <span class="category-name">Seleccionar categoría...</span>
                                                    <i class="fas fa-chevron-down dropdown-arrow"></i>
                                                </div>
                                                <div class="custom-options" id="category-options">
                                                    <div class="custom-option loading-option">
                                                        <i class="fas fa-spinner fa-spin"></i>
                                                        <span>Cargando categorías...</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Hidden input para el formulario -->
                                            <input type="hidden" id="event-category" name="categoria_id" required>
                                        </div>
                                        <button type="button" class="btn-add-inline" onclick="mostrarFormularioCategoria()">
                                            <i class="fas fa-plus"></i> Nueva
                                        </button>
                                        <button type="button" class="btn-add-inline" onclick="crearCategoriasDefecto()" title="Crear categorías por defecto">
                                            <i class="fas fa-magic"></i> Por defecto
                                        </button>
                                    </div>
                                    <div class="category-loading" style="display: none;">
                                        <i class="fas fa-spinner fa-spin"></i> Cargando categorías...
                                    </div>
                                </div>

                                <!-- Descripción -->
                                <div class="form-group">
                                    <label for="event-description">Descripción</label>
                                    <textarea id="event-description" name="descripcion" rows="3" placeholder="Descripción detallada del evento (opcional)..."></textarea>
                                </div>

                                <div class="form-actions-main">
                                    <button type="submit" class="btn">
                                        <i class="fas fa-save"></i> Crear Evento
                                    </button>
                                    <button type="button" class="btn" onclick="limpiarFormulario()">
                                        <i class="fas fa-eraser"></i> Limpiar Formulario
                                    </button>
                                    <button type="button" class="btn" onclick="window.location.href='calendary.php'">
                                        <i class="fas fa-calendar"></i> Ver en Calendario
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar -->
                <div class="right-sidebar">
                    <!-- Quick Actions Section -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-bolt"></i> Acciones Rápidas</h3>
                        </div>
                        <div class="card-content">
                            <div class="quick-actions-grid">
                                <button class="quick-action-card" onclick="crearEventoRapido('reunion')">
                                    <div class="quick-action-icon blue">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div class="quick-action-info">
                                        <h4>Reunión</h4>
                                        <p>Crear reunión de trabajo</p>
                                    </div>
                                </button>

                                <button class="quick-action-card" onclick="crearEventoRapido('cita')">
                                    <div class="quick-action-icon red">
                                        <i class="fas fa-user-md"></i>
                                    </div>
                                    <div class="quick-action-info">
                                        <h4>Cita Médica</h4>
                                        <p>Agendar cita de salud</p>
                                    </div>
                                </button>

                                <button class="quick-action-card" onclick="crearEventoRapido('tarea')">
                                    <div class="quick-action-icon purple">
                                        <i class="fas fa-tasks"></i>
                                    </div>
                                    <div class="quick-action-info">
                                        <h4>Nueva Tarea</h4>
                                        <p>Crear tarea pendiente</p>
                                    </div>
                                </button>

                                <button class="quick-action-card" onclick="crearEventoRapido('recordatorio')">
                                    <div class="quick-action-icon orange">
                                        <i class="fas fa-bell"></i>
                                    </div>
                                    <div class="quick-action-info">
                                        <h4>Recordatorio</h4>
                                        <p>Recordatorio importante</p>
                                    </div>
                                </button>

                                <button class="quick-action-card" onclick="crearEventoRapido('personal')">
                                    <div class="quick-action-icon green">
                                        <i class="fas fa-home"></i>
                                    </div>
                                    <div class="quick-action-info">
                                        <h4>Personal</h4>
                                        <p>Actividad personal</p>
                                    </div>
                                </button>

                                <button class="quick-action-card" onclick="crearEventoRapido('cumpleanos')">
                                    <div class="quick-action-icon pink">
                                        <i class="fas fa-birthday-cake"></i>
                                    </div>
                                    <div class="quick-action-info">
                                        <h4>Cumpleaños</h4>
                                        <p>Celebración especial</p>
                                    </div>
                                </button>

                                <button class="quick-action-card" onclick="crearEventoRapido('ejercicio')">
                                    <div class="quick-action-icon red">
                                        <i class="fas fa-dumbbell"></i>
                                    </div>
                                    <div class="quick-action-info">
                                        <h4>Ejercicio</h4>
                                        <p>Sesión de entrenamiento</p>
                                    </div>
                                </button>

                                <button class="quick-action-card" onclick="crearEventoRapido('estudio')">
                                    <div class="quick-action-icon purple">
                                        <i class="fas fa-graduation-cap"></i>
                                    </div>
                                    <div class="quick-action-info">
                                        <h4>Estudio</h4>
                                        <p>Sesión de aprendizaje</p>
                                    </div>
                                </button>

                                <button class="quick-action-card" onclick="crearEventoRapido('compras')">
                                    <div class="quick-action-icon orange">
                                        <i class="fas fa-shopping-cart"></i>
                                    </div>
                                    <div class="quick-action-info">
                                        <h4>Compras</h4>
                                        <p>Lista de compras</p>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Events Section -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-history"></i> Eventos Recientes</h3>
                            <button class="btn-icon" onclick="cargarEventosRecientes()" title="Actualizar">
                                <i class="fas fa-refresh"></i>
                            </button>
                        </div>
                        <div class="card-content">
                            <div id="recent-events-list" class="events-list">
                                <div class="loading-state">
                                    <i class="fas fa-spinner fa-spin"></i>
                                    <p>Cargando eventos...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Nueva Categoría -->
    <div id="category-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-tags"></i> Nueva Categoría</h3>
                <button class="modal-close" onclick="cerrarModalCategoria()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="category-form">
                    <div class="form-group">
                        <label for="category-name">Nombre *</label>
                        <input type="text" id="category-name" name="nombre" required placeholder="Ej: Trabajo, Personal...">
                    </div>
                    <div class="form-group">
                        <label for="category-color">Color *</label>
                        <input type="color" id="category-color" name="color" value="#667eea" required>
                    </div>
                    <div class="form-group">
                        <label for="category-description">Descripción</label>
                        <textarea id="category-description" name="descripcion" rows="2" placeholder="Descripción de la categoría..."></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn">
                            <i class="fas fa-save"></i> Crear Categoría
                        </button>
                        <button type="button" class="btn-secondary" onclick="cerrarModalCategoria()">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Escapa texto antes de insertarlo como HTML. Los eventos y
        // categorías vienen de datos guardados por el propio usuario, así
        // que nunca deben inyectarse tal cual en innerHTML (evita XSS
        // almacenado si un título, descripción o nombre de categoría
        // contiene HTML/JS).
        function escapeHtml(value) {
            const div = document.createElement('div');
            div.textContent = value ?? '';
            return div.innerHTML;
        }

        // Solo acepta colores hexadecimales (#rgb o #rrggbb); cualquier
        // otro valor se reemplaza por un color por defecto en vez de
        // insertarse directamente en un atributo style.
        function safeColor(value, fallback = '#6a3bd6') {
            return /^#[0-9a-fA-F]{3}([0-9a-fA-F]{3})?$/.test(value ?? '') ? value : fallback;
        }

        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            initializeEventForm();
        });

        // Initialize event form
        function initializeEventForm() {
            loadCategorias();
            setDefaultDate();
            cargarEventosRecientes();
            
            // Setup main event form handler
            const eventForm = document.getElementById('event-form');
            if (eventForm) {
                eventForm.addEventListener('submit', handleEventSubmit);
            }
            
            // Setup category form handler
            const categoryForm = document.getElementById('category-form');
            if (categoryForm) {
                categoryForm.addEventListener('submit', handleCategorySubmit);
            }
            
            // Check if date was passed from calendar
            const urlParams = new URLSearchParams(window.location.search);
            const fechaParam = urlParams.get('fecha');
            if (fechaParam) {
                const dateInput = document.getElementById('event-date');
                if (dateInput) {
                    dateInput.value = fechaParam;
                }
                const titleInput = document.getElementById('event-title');
                if (titleInput) {
                    titleInput.focus();
                }
            }
        }

        // Función para inicializar categorías automáticamente para el usuario
        async function initializeCategoriesForUser() {
            try {
                const response = await fetch('../../api/initialize_categories.php', {
                    method: 'POST',
                    credentials: 'same-origin'
                });
                
                const result = await response.json();
                console.log('Inicialización de categorías:', result);
                
                if (result.success) {
                    // Recargar categorías después de crearlas
                    setTimeout(() => {
                        loadCategorias();
                    }, 500);
                }
            } catch (error) {
                console.error('Error inicializando categorías:', error);
            }
        }

        // Función para crear categorías por defecto
        async function crearCategoriasDefecto() {
            if (!confirm('¿Deseas crear las categorías por defecto? (Trabajo, Personal, Salud, Educación, Emergencia)')) {
                return;
            }

            const categorias_defecto = [
                {nombre: 'Trabajo', color: '#6a3bd6', descripcion: 'Reuniones, proyectos y actividades laborales'},
                {nombre: 'Personal', color: '#10b981', descripcion: 'Actividades personales y tiempo libre'},
                {nombre: 'Salud', color: '#f59e0b', descripcion: 'Citas médicas y actividades relacionadas con la salud'},
                {nombre: 'Educación', color: '#8b5cf6', descripcion: 'Cursos, clases y actividades de aprendizaje'},
                {nombre: 'Emergencia', color: '#ef4444', descripcion: 'Eventos urgentes que requieren atención inmediata'}
            ];

            let creadas = 0;
            let errores = 0;

            for (const categoria of categorias_defecto) {
                try {
                    const response = await fetch('../../api/categories.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(categoria)
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        creadas++;
                    } else {
                        console.warn(`No se pudo crear la categoría ${categoria.nombre}: ${result.error}`);
                        errores++;
                    }
                } catch (error) {
                    console.error(`Error creando categoría ${categoria.nombre}:`, error);
                    errores++;
                }
            }

            if (creadas > 0) {
                alert(`Se crearon ${creadas} categorías por defecto exitosamente.`);
                await loadCategorias();
            }
            
            if (errores > 0) {
                alert(`Hubo ${errores} errores al crear algunas categorías (posiblemente ya existan).`);
            }
        }

        // Handle main event form submission
        async function handleEventSubmit(e) {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const eventData = {
                titulo: formData.get('titulo'),
                descripcion: formData.get('descripcion'),
                fecha_inicio: formData.get('fecha_inicio'),
                hora_inicio: formData.get('hora_inicio'),
                hora_fin: formData.get('hora_fin'),
                tipo: formData.get('tipo'),
                categoria_id: formData.get('categoria_id')
            };
            
            // Validate required fields
            if (!eventData.titulo || !eventData.fecha_inicio || !eventData.categoria_id) {
                alert('Por favor completa todos los campos requeridos');
                return;
            }
            
            try {
                const submitButton = e.target.querySelector('button[type="submit"]');
                const originalText = submitButton.innerHTML;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creando...';
                submitButton.disabled = true;
                
                const response = await fetch('../../api/events.php', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(eventData)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alert('Evento creado exitosamente');
                    e.target.reset();
                    setDefaultDate();
                    
                    // Ask if user wants to go to calendar
                    if (confirm('¿Deseas ver el evento en el calendario?')) {
                        window.location.href = 'calendary.php';
                    }
                } else {
                    alert('Error: ' + (result.error || 'Error al crear evento'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error de conexión al crear evento');
            } finally {
                const submitButton = e.target.querySelector('button[type="submit"]');
                submitButton.innerHTML = '<i class="fas fa-save"></i> Crear Evento';
                submitButton.disabled = false;
            }
        }

        // Handle category form submission
        async function handleCategorySubmit(e) {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const categoryData = {
                nombre: formData.get('nombre'),
                color: formData.get('color'),
                descripcion: formData.get('descripcion')
            };
            
            try {
                const response = await fetch('../../api/categories.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(categoryData)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    cerrarModalCategoria();
                    await loadCategorias();
                    alert('Categoría creada exitosamente');
                } else {
                    alert('Error: ' + (result.error || 'Error al crear categoría'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error al crear categoría');
            }
        }

        // Load categories from API
        async function loadCategorias() {
            console.log('Cargando categorías...');
            const categoryOptionsContainer = document.getElementById('category-options');
            
            if (!categoryOptionsContainer) {
                console.error('No se encontró el contenedor de opciones de categorías');
                return;
            }
            
            try {
                const response = await fetch('../../api/categories.php', {
                    method: 'GET',
                    credentials: 'same-origin'
                });
                
                console.log('Respuesta API:', response.status);
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                const result = await response.json();
                console.log('Datos recibidos:', result);
                
                if (result.success && result.categories && result.categories.length > 0) {
                    // Limpiar opciones
                    categoryOptionsContainer.innerHTML = '';
                    
                    // Agregar opción por defecto
                    const defaultOption = document.createElement('div');
                    defaultOption.className = 'custom-option';
                    defaultOption.onclick = () => selectCategory('', 'Seleccionar categoría...', '#ccc');
                    defaultOption.innerHTML = `
                        <span class="category-color-dot" style="background: #ccc;"></span>
                        <span class="category-name">Seleccionar categoría...</span>
                    `;
                    categoryOptionsContainer.appendChild(defaultOption);
                    
                    // Agregar categorías
                    result.categories.forEach(category => {
                        const option = document.createElement('div');
                        option.className = 'custom-option';
                        option.onclick = () => selectCategory(category.id, category.nombre, category.color);
                        option.innerHTML = `
                            <span class="category-color-dot" style="background: ${safeColor(category.color)};"></span>
                            <span class="category-name">${escapeHtml(category.nombre)}</span>
                        `;
                        categoryOptionsContainer.appendChild(option);
                        console.log('Categoría agregada:', category.nombre);
                    });
                    
                    console.log(`✓ ${result.categories.length} categorías cargadas`);
                } else {
                    console.error('Sin categorías o error en respuesta:', result);
                    
                    // Mostrar mensaje más específico
                    let mensaje = 'No hay categorías disponibles';
                    if (result.error) {
                        mensaje = `Error: ${result.error}`;
                    } else if (!result.success) {
                        mensaje = 'Error en el servidor';
                    }
                    
                    categoryOptionsContainer.innerHTML = `
                        <div class="custom-option loading-option">
                            <span>${mensaje}</span>
                        </div>
                    `;
                    
                    // Intentar inicializar categorías por defecto automáticamente
                    console.log('Iniciando categorías por defecto...');
                    initializeCategoriesForUser();
                }
            } catch (error) {
                console.error('Error cargando categorías:', error);
                categoryOptionsContainer.innerHTML = `
                    <div class="custom-option loading-option">
                        <span>Error de conexión: ${error.message}</span>
                    </div>
                `;
                
                // Reintentar en unos segundos
                setTimeout(() => {
                    console.log('Reintentando cargar categorías...');
                    loadCategorias();
                }, 3000);
            }
        }

        // Toggle category dropdown
        function toggleCategoryDropdown() {
            const customSelect = document.getElementById('custom-category-select');
            customSelect.classList.toggle('open');
        }

        // Select category
        function selectCategory(id, name, color) {
            const hiddenInput = document.getElementById('event-category');
            const selectedOption = document.getElementById('selected-category');
            const customSelect = document.getElementById('custom-category-select');
            
            // Update hidden input
            hiddenInput.value = id;
            
            // Update display
            selectedOption.innerHTML = `
                <span class="category-color-dot" style="background: ${safeColor(color)};"></span>
                <span class="category-name">${escapeHtml(name)}</span>
                <i class="fas fa-chevron-down dropdown-arrow"></i>
            `;
            
            // Close dropdown
            customSelect.classList.remove('open');
            
            console.log('Categoría seleccionada:', name, id);
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const customSelect = document.getElementById('custom-category-select');
            if (customSelect && !customSelect.contains(event.target)) {
                customSelect.classList.remove('open');
            }
        });

        // Set default date
        function setDefaultDate() {
            const dateInput = document.getElementById('event-date');
            if (dateInput && !dateInput.value) {
                const today = new Date();
                dateInput.value = today.toISOString().split('T')[0];
            }
        }

        // Clear form
        function limpiarFormulario() {
            const form = document.getElementById('event-form');
            if (form) {
                form.reset();
                setDefaultDate();
                document.getElementById('event-title').focus();
            }
        }

        // Show category modal
        function mostrarFormularioCategoria() {
            const modal = document.getElementById('category-modal');
            if (modal) {
                modal.style.display = 'flex';
                const form = document.getElementById('category-form');
                if (form) {
                    form.reset();
                    document.getElementById('category-color').value = '#667eea';
                }
            }
        }

        // Close category modal
        function cerrarModalCategoria() {
            const modal = document.getElementById('category-modal');
            if (modal) {
                modal.style.display = 'none';
            }
        }

        // Quick event creation
        function crearEventoRapido(tipo) {
            const titleInput = document.getElementById('event-title');
            const typeSelect = document.getElementById('event-type');
            const dateInput = document.getElementById('event-date');
            const timeStartInput = document.getElementById('event-time-start');
            
            const today = new Date();
            const now = new Date();
            const hours = now.getHours().toString().padStart(2, '0');
            const minutes = now.getMinutes().toString().padStart(2, '0');
            
            switch(tipo) {
                case 'reunion':
                    titleInput.value = 'Reunión de trabajo';
                    typeSelect.value = 'evento';
                    timeStartInput.value = '10:00';
                    break;
                case 'cita':
                    titleInput.value = 'Cita médica';
                    typeSelect.value = 'evento';
                    timeStartInput.value = '09:00';
                    break;
                case 'tarea':
                    titleInput.value = 'Nueva tarea';
                    typeSelect.value = 'tarea';
                    timeStartInput.value = hours + ':' + minutes;
                    break;
                case 'recordatorio':
                    titleInput.value = 'Recordatorio importante';
                    typeSelect.value = 'recordatorio';
                    timeStartInput.value = hours + ':' + minutes;
                    break;
                case 'personal':
                    titleInput.value = 'Actividad personal';
                    typeSelect.value = 'evento';
                    timeStartInput.value = '18:00';
                    break;
                case 'cumpleanos':
                    titleInput.value = 'Cumpleaños';
                    typeSelect.value = 'evento';
                    timeStartInput.value = '12:00';
                    break;
                case 'ejercicio':
                    titleInput.value = 'Sesión de ejercicio';
                    typeSelect.value = 'tarea';
                    timeStartInput.value = '06:00';
                    break;
                case 'estudio':
                    titleInput.value = 'Sesión de estudio';
                    typeSelect.value = 'tarea';
                    timeStartInput.value = '15:00';
                    break;
                case 'compras':
                    titleInput.value = 'Ir de compras';
                    typeSelect.value = 'recordatorio';
                    timeStartInput.value = '10:00';
                    break;
            }
            
            dateInput.value = today.toISOString().split('T')[0];
            titleInput.focus();
            titleInput.select();
        }

        // Load recent events
        async function cargarEventosRecientes() {
            const container = document.getElementById('recent-events-list');
            
            try {
                const response = await fetch('../../api/events.php?action=recent&limit=6', {
                    method: 'GET',
                    credentials: 'same-origin'
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }
                
                const result = await response.json();
                
                if (result.success && result.events && result.events.length > 0) {
                    container.innerHTML = result.events.map(event => {
                        const fecha = new Date(event.fecha_inicio);
                        const fechaStr = fecha.toLocaleDateString('es-ES', { 
                            day: 'numeric', 
                            month: 'short' 
                        });
                        
                        return `
                            <div class="event-item">
                                <div class="event-time">
                                    <span class="time">${escapeHtml(event.hora_inicio || '00:00')}</span>
                                    <span class="date">${escapeHtml(fechaStr)}</span>
                                </div>
                                <div class="event-details">
                                    <h4 class="event-title">${escapeHtml(event.titulo)}</h4>
                                    <p class="event-description">${escapeHtml(event.descripcion || 'Sin descripción')}</p>
                                </div>
                                <div class="event-status" style="background-color: ${safeColor(event.categoria_color, '#667eea')}"></div>
                            </div>
                        `;
                    }).join('');
                } else {
                    container.innerHTML = `
                        <div class="empty-state">
                            <i class="fas fa-calendar-times"></i>
                            <p>No hay eventos recientes</p>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Error cargando eventos recientes:', error);
                container.innerHTML = `
                    <div class="error-state">
                        <i class="fas fa-exclamation-triangle"></i>
                        <p>Error al cargar eventos recientes</p>
                    </div>
                `;
            }
        }
    </script>
</body>
</html>