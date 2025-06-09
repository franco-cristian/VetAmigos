<?php
if (!isset($_SESSION)) session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VetAmigos - <?= $pageTitle ?? 'Panel' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Barra de navegación -->
    <header class="bg-blue-600 text-white p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <a class="text-2xl font-bold">VetAmigos</a>
            <div class="flex items-center space-x-4">
                <?php if (isset($_SESSION['user_email'])): ?>
                    <span>Hola, <?= $_SESSION['user_email'] ?></span>
                    <a 
                        href="../modules/logout.php" 
                        class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded transition"
                    >
                        Cerrar Sesión
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>
    <main class="container mx-auto p-4 md:p-6">