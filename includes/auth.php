<?php
session_start();

// Verificar sesión activa
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Verificar rol de veterinario
function isVeterinario() {
    return isset($_SESSION['user_rol']) && $_SESSION['user_rol'] === 'veterinario';
}

// Verificar rol administrativo
function isAdministrativo() {
    return isset($_SESSION['user_rol']) && $_SESSION['user_rol'] === 'administrativo';
}

// Redirigir si no está autenticado
function requireAuth() {
    if (!isLoggedIn()) {
        header("Location: /modules/login.php");
        exit();
    }
}

// Encriptar contraseñas
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}
?>