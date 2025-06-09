<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/database.php';
requireAuth();

$error = '';
$success = '';

// Procesar nuevo turno
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mascota_id = $_POST['mascota_id'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $motivo = $_POST['motivo'];
    
    try {
        $stmt = $conn->prepare("INSERT INTO turnos (mascota_id, fecha, hora, motivo) VALUES (?, ?, ?, ?)");
        $stmt->execute([$mascota_id, $fecha, $hora, $motivo]);
        $success = "Turno agendado correctamente!";
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Obtener mascotas para el select
$mascotas = $conn->query("SELECT id, nombre FROM mascotas")->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = "Agendar Turnos";
require_once __DIR__ . '/../includes/header.php';
?>

<h1 class="text-2xl font-bold mb-6">Agendar Turnos</h1>

<?php if ($error): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <?= $error ?>
    </div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        <?= $success ?>
    </div>
<?php endif; ?>

<!-- Formulario de turnos -->
<form method="POST" class="bg-white p-6 rounded-lg shadow-md max-w-2xl mx-auto">
    <div class="mb-4">
        <label class="block text-gray-700 mb-2">Mascota</label>
        <select name="mascota_id" required class="w-full px-3 py-2 border rounded-md">
            <?php foreach ($mascotas as $mascota): ?>
                <option value="<?= $mascota['id'] ?>"><?= $mascota['nombre'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
        <div>
            <label class="block text-gray-700 mb-2">Fecha</label>
            <input 
                type="date" 
                name="fecha" 
                required
                class="w-full px-3 py-2 border rounded-md"
            >
        </div>
        
        <div>
            <label class="block text-gray-700 mb-2">Hora</label>
            <input 
                type="time" 
                name="hora" 
                required
                class="w-full px-3 py-2 border rounded-md"
            >
        </div>
    </div>
    
    <div class="mb-6">
        <label class="block text-gray-700 mb-2">Motivo</label>
        <textarea 
            name="motivo" 
            rows="3"
            required
            class="w-full px-3 py-2 border rounded-md"
        ></textarea>
    </div>
    
    <button 
        type="submit" 
        class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-6 rounded-md transition"
    >
        Agendar Turno
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