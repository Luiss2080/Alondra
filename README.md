# Alondra - Sistema de Calendario Estudiantil

Sistema de calendario web desarrollado en PHP para organización de actividades y tareas académicas.

## 🚀 Instalación y Configuración

### Prerequisitos

- XAMPP (Apache + MySQL + PHP 7.4+)
- Navegador web moderno

### 1. Configuración de Base de Datos

1. **Crear la base de datos:**

   ```sql
   CREATE DATABASE alondra CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

2. **Configurar conexión:**
   - Editar `config/config.php` con tus credenciales de MySQL
   - Por defecto usa: host=127.0.0.1, user=root, password=(vacío)

### 2. Ejecutar Migraciones y Seeds

**Opción A: Desde el navegador**

- Acceder a: `http://localhost/Alondra/database/run_migrations.php`

**Opción B: Desde línea de comandos**

```bash
cd c:\xampp\htdocs\Alondra\database
php run_migrations.php
```

**Opción C: Ejecutar manualmente en phpMyAdmin**

1. Ejecutar archivos en orden:
   - `database/migrations/001_create_usuarios.sql`
   - `database/migrations/002_create_eventos.sql`
   - `database/seeds/001_create_usuarios.sql`
   - `database/seeds/002_create_eventos.sql`

### 3. Acceder al Sistema

- URL: `http://localhost/Alondra/`
- El sistema redirige automáticamente al login

## 👥 Usuarios de Prueba

El seed incluye usuarios de ejemplo (contraseña: `123456`):

- admin@alondra.edu
- maria.gonzalez@estudiante.edu
- juan.perez@estudiante.edu
- ana.lopez@estudiante.edu

## 📁 Estructura de la Base de Datos

### Tabla `usuarios`

- **Campos:** id, nombre, email (único), password (hasheado), activo, creado_en, actualizado_en
- **Índices:** email, activo
- **Propósito:** Gestión de cuentas de usuario y autenticación

### Tabla `eventos`

- **Campos:** id, usuario_id, titulo, descripcion, fecha_inicio, hora_inicio, hora_fin, tipo, color, creado_en
- **Índices:** usuario_id, fecha_inicio
- **Propósito:** Almacenamiento de eventos del calendario
- **Tipos:** tarea, evento, recordatorio
- **Relaciones:** usuarios (CASCADE)

## 🔧 Características

- **Autenticación:** Registro, login, logout con sesiones PHP y verificación de usuarios activos
- **Dashboard:** Panel de control personalizado con próximas tareas
- **Calendario:** Visualización mensual con JavaScript y persistencia en BD
- **Eventos:** Creación, edición y eliminación con diferentes tipos y colores
- **API REST:** Endpoint simple para CRUD de eventos
- **Responsive:** Diseño adaptable a dispositivos móviles
- **Seguridad:** Contraseñas hasheadas, validación de sesiones, verificación de permisos

## 🚀 API Disponible

### Eventos (`/api/eventos.php`)

- **GET:** Obtener eventos del mes `?mes=10&año=2025`
- **POST:** Crear nuevo evento (titulo, descripcion, fecha_inicio, hora_inicio, hora_fin, tipo, color)
- **PUT:** Actualizar evento existente
- **DELETE:** Eliminar evento

## 🛠️ Tecnologías

- **Backend:** PHP 7.4+, PDO MySQL
- **Frontend:** HTML5, CSS3, JavaScript vanilla
- **Base de Datos:** MySQL con migraciones estructuradas (2 tablas simples)
- **API:** REST simple con JSON para eventos
- **Servidor:** Apache (XAMPP)
- **Autenticación:** Sesiones PHP con validación de permisos

## 📝 Funcionalidades

- ✅ Autenticación completa (registro, login, logout)
- ✅ Base de datos simple pero funcional
- ✅ Dashboard con información del usuario
- ✅ Calendario básico con JavaScript
- ✅ CRUD de eventos con persistencia en BD
- ✅ API REST para eventos
- ✅ Diferentes tipos de eventos (tarea, evento, recordatorio)
- ✅ Colores personalizables para eventos
- ⭕ Interfaz avanzada del calendario (próxima versión)
- ⭕ Notificaciones y recordatorios (próxima versión)

## � Estructura del Proyecto

```
Alondra/
├── api/                    # APIs REST
│   ├── eventos.php        # CRUD de eventos
│   └── categorias.php     # Gestión de categorías
├── auth/                   # Controladores de autenticación
│   ├── login.php
│   ├── register.php
│   └── logout.php
├── config/                 # Configuración
│   ├── config.php         # Configuración de BD y sistema
│   └── conexion.php       # Conexión PDO a MySQL
├── database/              # Base de datos
│   ├── migrations/        # Scripts de creación de tablas
│   │   ├── 001_create_usuarios.sql
│   │   ├── 002_create_eventos.sql
│   │   └── 003_create_categorias_eventos.sql
│   ├── seeds/            # Datos de ejemplo
│   │   ├── 001_create_usuarios.sql
│   │   ├── 002_create_eventos.sql
│   │   └── 003_create_categorias_eventos.sql
│   └── run_migrations.php # Ejecutor de migraciones
├── public/               # Archivos públicos
│   ├── css/
│   │   └── styles.css    # Estilos principales
│   └── home/
│       ├── dashboard.php # Panel de control
│       └── calendary.php # Calendario interactivo
└── views/                # Vistas
    ├── auth/
    │   ├── login.php     # Formulario de login
    │   └── register.php  # Formulario de registro
    └── layouts/
        ├── header.php    # Cabecera común
        ├── footer.php    # Pie de página
        └── sidebar.php   # Barra lateral
```
