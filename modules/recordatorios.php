<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/database.php';
requireAuth();

// Solo veterinarios pueden enviar recordatorios
if (!isVeterinario()) {
    header("HTTP/1.1 403 Forbidden");
    exit("Acceso denegado");
}

$success = '';

// Procesar envío
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mascota_id = $_POST['mascota_id'];
    $mensaje = $_POST['mensaje'];
    $success = "Recordatorio enviado al dueño!";
}

$mascotas = $conn->query("SELECT id, nombre FROM mascotas")->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = "Recordatorios";
require_once __DIR__ . '/../includes/header.php';
?>

<h1 class="text-2xl font-bold mb-6">Recordatorios de Vacunación</h1>

<?php if ($success): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        <?= $success ?>
    </div>
<?php endif; ?>

<form method="POST" class="bg-white p-6 rounded-lg shadow-md max-w-2xl mx-auto">
    <div class="mb-4">
        <label class="block text-gray-700 mb-2">Mascota</label>
        <select name="mascota_id" required class="w-full px-3 py-2 border rounded-md">
            <?php foreach ($mascotas as $mascota): ?>
                <option value="<?= $mascota['id'] ?>"><?= $mascota['nombre'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="mb-6">
        <label class="block text-gray-700 mb-2">Mensaje Personalizado</label>
        <textarea 
            name="mensaje" 
            rows="4"
            class="w-full px-3 py-2 border rounded-md"
            placeholder="Ej: Recordatorio vacuna antirrábica..."
        ></textarea>
    </div>
    
    <button 
        type="submit" 
        class="bg-green-500 hover:bg-green-600 text-white py-2 px-6 rounded-md transition"
    >
        Enviar Recordatorio
    </button>
    <button 
        type="button"
        class="mt-6 bg-blue-500 hover:bg-blue-600 text-white py-2 px-6 rounded-md transition"
        onclick="window.location.href='../modules/dashboard.php';"
    >
        Volver
    </button>
</form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>