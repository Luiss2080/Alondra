<?php
// Sidebar reutilizable - Estilo RemindMe en Español
?>
<aside class="sidebar">
    <div class="sidebar-header">
        <img src="/Alondra/public/img/LogoSecretariado.png" alt="Alondra Logo" class="logo-img">
    </div>
    
    <nav class="sidebar-nav">
        <ul class="nav-list">
            <li class="nav-item">
                <a href="/Alondra/views/home/dashboard.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : ''; ?>">
                    <i class="fas fa-th-large"></i>
                    <span>Tablero</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="/Alondra/views/home/calendary.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'calendary.php') ? 'active' : ''; ?>">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Calendario</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="/Alondra/views/home/frmEvento.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'frmEvento.php') ? 'active' : ''; ?>">
                    <i class="fas fa-calendar-plus"></i>
                    <span>Gestión de Eventos</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-tasks"></i>
                    <span>Mis Tareas</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-bell"></i>
                    <span>Notificaciones</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-cog"></i>
                    <span>Configuración</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-question-circle"></i>
                    <span>Ayuda</span>
                </a>
            </li>
        </ul>
    </nav>
    
    <div class="sidebar-footer">
        <a href="/Alondra/auth/logout.php" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i>
            <span>Cerrar Sesión</span>
        </a>
    </div>
</aside>
