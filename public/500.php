<?php
http_response_code(500);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error del servidor - Alondra</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #6a3bd6 0%, #8b5cf6 50%, #e8e2ff 100%);
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .error-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 25px 50px rgba(106, 59, 214, 0.15);
            padding: 40px;
            max-width: 420px;
            text-align: center;
        }
        h1 { color: #6a3bd6; font-size: 64px; margin: 0; }
        p { color: #374151; font-size: 16px; }
        a {
            display: inline-block;
            margin-top: 16px;
            padding: 12px 24px;
            background: linear-gradient(135deg, #6a3bd6, #8b5cf6);
            color: white;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="error-card">
        <h1>500</h1>
        <p>Ocurrió un error inesperado en el servidor. Ya quedó registrado, inténtalo de nuevo en unos minutos.</p>
        <a href="/Alondra/index.php">Volver al inicio</a>
    </div>
</body>
</html>
