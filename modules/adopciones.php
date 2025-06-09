<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/funciones.php';
requireAuth();

// Solo veterinarios pueden gestionar adopciones
if (!isVeterinario()) {
    header("HTTP/1.1 403 Forbidden");
    exit("Acceso denegado");
}

$error = '';
$success = '';

// Procesar nueva adopción
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mascota_id = limpiarDato($_POST['mascota_id']);
    $adoptante_nombre = limpiarDato($_POST['adoptante_nombre']);
    $adoptante_telefono = limpiarDato($_POST['adoptante_telefono']);
    $fecha_adopcion = limpiarDato($_POST['fecha_adopcion']);
    $observaciones = limpiarDato($_POST['observaciones']);
    
    try {
        $conn->beginTransaction();
        
        // Registrar adopción
        $stmt = $conn->prepare("INSERT INTO adopciones 
            (mascota_id, adoptante_nombre, adoptante_telefono, fecha_adopcion, observaciones) 
            VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $mascota_id, 
            $adoptante_nombre, 
            $adoptante_telefono, 
            $fecha_adopcion, 
            $observaciones
        ]);
        
        // Marcar mascota como adoptada
        $conn->exec("UPDATE mascotas SET en_adopcion = 0 WHERE id = $mascota_id");
        
        $conn->commit();
        $success = "Adopción registrada exitosamente!";
    } catch (PDOException $e) {
        $conn->rollBack();
        $error = "Error: " . $e->getMessage();
    }
}

// Obtener mascotas disponibles (no adoptadas)
$mascotas = $conn->query("
    SELECT id, nombre 
    FROM mascotas 
    WHERE en_adopcion = 1  -- Solo las marcadas para adopción!
")->fetchAll(PDO::FETCH_ASSOC);

// Obtener historial de adopciones
$adopciones = $conn->query("
    SELECT a.*, m.nombre AS mascota 
    FROM adopciones a
    JOIN mascotas m ON a.mascota_id = m.id
    ORDER BY a.fecha_adopcion DESC
")->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = "Gestión de Adopciones";
require_once __DIR__ . '/../includes/header.php';
?>

<h1 class="text-2xl font-bold mb-6">Adopciones de Mascotas</h1>

<!-- Mensajes de estado -->
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

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Formulario de adopción -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4">Registrar Nueva Adopción</h2>
        <form method="POST">
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Mascota</label>
                <select name="mascota_id" required class="w-full px-3 py-2 border rounded-md">
                    <?php foreach ($mascotas as $mascota): ?>
                        <option value="<?= $mascota['id'] ?>">
                            <?= $mascota['nombre'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 mb-2">Nombre del Adoptante</label>
                    <input 
                        type="text" 
                        name="adoptante_nombre" 
                        required
                        class="w-full px-3 py-2 border rounded-md"
                    >
                </div>
                
                <div>
                    <label class="block text-gray-700 mb-2">Teléfono</label>
                    <input 
                        type="tel" 
                        name="adoptante_telefono" 
                        required
                        class="w-full px-3 py-2 border rounded-md"
                    >
                </div>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Fecha de Adopción</label>
                <input 
                    type="date" 
                    name="fecha_adopcion" 
                    required
                    class="w-full px-3 py-2 border rounded-md"
                    value="<?= date('Y-m-d') ?>"
                >
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 mb-2">Observaciones</label>
                <textarea 
                    name="observaciones" 
                    rows="3"
                    class="w-full px-3 py-2 border rounded-md"
                    placeholder="Ej: Se entrega con cartilla de vacunación..."
                ></textarea>
            </div>
            
            <button 
                type="submit" 
                class="bg-purple-600 hover:bg-purple-700 text-white py-2 px-6 rounded-md transition"
            >
                Registrar Adopción
            </button>
            <button 
                type="button"
                class="mt-6 bg-blue-500 hover:bg-blue-600 text-white py-2 px-6 rounded-md transition"
                onclick="window.location.href='../modules/dashboard.php';"
            >
                Volver
            </button>
        </form>
    </div>
    
    <!-- Historial de adopciones -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4">Historial de Adopciones</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left">Mascota</th>
                        <th class="px-4 py-3 text-left">Adoptante</th>
                        <th class="px-4 py-3 text-left">Fecha</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($adopciones as $adopcion): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3"><?= $adopcion['mascota'] ?></td>
                        <td class="px-4 py-3"><?= $adopcion['adoptante_nombre'] ?></td>
                        <td class="px-4 py-3"><?= date('d/m/Y', strtotime($adopcion['fecha_adopcion'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>