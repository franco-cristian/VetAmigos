<?php
// includes/funciones.php

/**
 * Valida y sanitiza un string (evita XSS y limpia espacios)
 * @param string $dato
 * @return string
 */
function limpiarDato($dato) {
    return htmlspecialchars(trim($dato), ENT_QUOTES, 'UTF-8');
}

/**
 * Verifica si una fecha es válida y futura
 * @param string $fecha (formato YYYY-MM-DD)
 * @return bool
 */
function validarFecha($fecha) {
    $fechaActual = date('Y-m-d');
    return $fecha >= $fechaActual;
}

/**
 * Formatea una fecha de MySQL a formato legible
 * @param string $fechaMySQL
 * @return string
 */
function formatoFecha($fechaMySQL) {
    return date('d/m/Y', strtotime($fechaMySQL));
}

/**
 * Genera un token CSRF para formularios
 * @return string
 */
function generarTokenCSRF() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verifica un token CSRF
 * @param string $token
 * @return bool
 */
function verificarTokenCSRF($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}