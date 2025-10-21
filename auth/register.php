<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alondra - Registrarse</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #6a3bd6 0%, #8b5cf6 50%, #e8e2ff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: white;
            border-radius: 24px;
            box-shadow: 0 25px 50px rgba(106, 59, 214, 0.15);
            padding: 35px;
            width: 100%;
            max-width: 420px;
            position: relative;
            overflow: hidden;
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #6a3bd6, #8b5cf6);
        }

        .logo-wrapper {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 20px;
            background: 
                radial-gradient(circle at 20% 30%, #8b5cf6 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, #6a3bd6 0%, transparent 50%),
                radial-gradient(circle at 40% 90%, #a855f7 0%, transparent 50%),
                radial-gradient(circle at 90% 20%, #7c3aed 0%, transparent 50%),
                linear-gradient(135deg, #6a3bd6 0%, #8b5cf6 50%, #a855f7 100%);
            border-radius: 20px;
            padding: 25px 20px;
            box-shadow: 
                0 20px 40px rgba(106, 59, 214, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
            display: inline-block;
        }

        .logo-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: 
                radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 20px 20px;
            animation: float 6s ease-in-out infinite;
        }

        .logo-img {
            max-width: 160px;
            height: auto;
            position: relative;
            z-index: 2;
            filter: brightness(1.2) contrast(1.1) drop-shadow(0 4px 8px rgba(0,0,0,0.2));
        }

        @keyframes float {
            0%, 100% { transform: rotate(0deg) translate(0, 0); }
            50% { transform: rotate(180deg) translate(-10px, -10px); }
        }

        .login-title {
            color: #1f2937;
            font-size: 28px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 8px;
        }

        .login-subtitle {
            color: #6b7280;
            font-size: 16px;
            text-align: center;
            margin-bottom: 32px;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 500;
        }

        .alert-error {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .alert-success {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            color: #374151;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 16px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #f9fafb;
        }

        .form-input:focus {
            outline: none;
            border-color: #6a3bd6;
            background: white;
            box-shadow: 0 0 0 3px rgba(106, 59, 214, 0.1);
        }

        .password-container {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            cursor: pointer;
            font-size: 18px;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: #6a3bd6;
        }

        .login-btn {
            width: 100%;
            background: linear-gradient(135deg, #6a3bd6, #8b5cf6);
            color: white;
            border: none;
            padding: 16px 24px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 24px;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(106, 59, 214, 0.3);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .register-link {
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }

        .register-link a {
            color: #6a3bd6;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .register-link a:hover {
            color: #8b5cf6;
        }

        @media (max-width: 480px) {
            .login-container {
                margin: 10px;
                padding: 30px 24px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo-wrapper">
            <div class="logo-container">
                <img src="/Alondra/public/img/AlondraLogo.png" alt="Alondra Logo" class="logo-img">
            </div>
        </div>
        
        <h1 class="login-title">Registrarse</h1>
        <p class="login-subtitle">Crea tu cuenta en el sistema de gestión académica</p>
        
        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle"></i>
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <form action="../controllers/register_process.php" method="post">
            <div class="form-group">
                <label for="nombre" class="form-label">Nombre completo</label>
                <input type="text" id="nombre" name="nombre" class="form-input" placeholder="Tu nombre completo" required>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" id="email" name="email" class="form-input" placeholder="admin@alondra.edu" required>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Contraseña</label>
                <div class="password-container">
                    <input type="password" id="password" name="password" class="form-input" placeholder="••••••••" required>
                    <i class="fas fa-eye password-toggle" onclick="togglePassword()"></i>
                </div>
            </div>

            <button type="submit" class="login-btn">
                <i class="fas fa-user-plus"></i>
                Crear cuenta
            </button>
        </form>

        <div class="register-link">
            ¿Ya tienes cuenta? <a href="login.php">Iniciar sesión</a>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.querySelector('.password-toggle');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>