        <footer class="app-footer">
            <div class="footer-content">
                <div class="footer-text">
                    <p>&copy; <?php echo date('Y'); ?> RemindMe - Sistema de Calendario Estudiantil</p>
                    <p>Desarrollado para mejorar tu productividad académica</p>
                </div>
                <div class="footer-logo">
                    <img src="/Alondra/public/img/ColegioLogo.png" alt="Logo del Colegio" class="colegio-logo">
                </div>
            </div>
        </footer>
    </div>

    <script>
        function toggleMobileMenu() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('mobile-open');
        }

        // Cerrar menú móvil al hacer clic fuera del sidebar
        document.addEventListener('click', function(event) {
            const sidebar = document.querySelector('.sidebar');
            const toggleBtn = document.querySelector('.mobile-menu-toggle');
            
            if (!sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
                sidebar.classList.remove('mobile-open');
            }
        });

        // Cerrar menú móvil al cambiar el tamaño de ventana a desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                const sidebar = document.querySelector('.sidebar');
                sidebar.classList.remove('mobile-open');
            }
        });
    </script>
</body>
</html>
