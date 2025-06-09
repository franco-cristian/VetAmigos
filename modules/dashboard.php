<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/database.php';
requireAuth();

$pageTitle = "Panel de Control";
require_once __DIR__ . '/../includes/header.php';
?>

<h1 class="text-2xl font-bold mb-6">Bienvenido a VetAmigos</h1>

<!-- Tarjetas de funcionalidades -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <a href="mascotas.php" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition block">
        <h3 class="text-xl font-semibold mb-2">🐾 Mascotas</h3>
        <p>Registrar mascotas y dueños</p>
    </a>
    
    <a href="turnos.php" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition block">
        <h3 class="text-xl font-semibold mb-2">📅 Turnos</h3>
        <p>Agendar consultas</p>
    </a>
    
    <a href="recordatorios.php" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition block">
        <h3 class="text-xl font-semibold mb-2">🔔 Recordatorios</h3>
        <p>Enviar avisos de vacunación</p>
    </a>
    
    <a href="historial.php" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition block">
        <h3 class="text-xl font-semibold mb-2">📋 Historial</h3>
        <p>Consultar visitas anteriores</p>
    </a>
    
    <a href="adopciones.php" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition block">
        <h3 class="text-xl font-semibold mb-2">🏠 Adopciones</h3>
        <p>Gestionar adopciones de mascotas</p>
    </a>
</div>

<!-- Información de roles -->
<div class="mt-8 bg-white p-6 rounded-lg shadow-md">
    <h3 class="text-xl font-semibold mb-4">Tu Rol: 
        <span class="<?= isVeterinario() ? 'text-blue-600' : 'text-green-600' ?>">
            <?= $_SESSION['user_rol'] === 'veterinario' ? 'Veterinario' : 'Administrativo' ?>
        </span>
    </h3>
    <p class="mb-2">
        <?php if (isVeterinario()): ?>
            <span class="font-medium">Permisos completos:</span> Gestión de mascotas, turnos, historial médico.
        <?php else: ?>
            <span class="font-medium">Permisos básicos:</span> Registro de mascotas y agendamiento de turnos.
        <?php endif; ?>
    </p>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>