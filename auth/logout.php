<?php
session_start();
// Destruir sesión y redirigir a login
session_unset();
session_destroy();
header('Location: login.php');
exit;
