<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/database.php';
requireAuth();

// Solo veterinarios y administrativos pueden acceder
if (!isVeterinario() && !isAdministrativo()) {
    header("HTTP/1.1 403 Forbidden");
    exit("Acceso denegado");
}

$error = '';
$success = '';

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $tipo = $_POST['tipo'];
    $duenio = $_POST['duenio'];
    $telefono = $_POST['telefono'];
    $en_adopcion = isset($_POST['en_adopcion']) ? 1 : 0;
    
    try {
        $conn->beginTransaction();
        
        // Insertar dueño
        $stmtDuenio = $conn->prepare("INSERT INTO duenios (nombre, telefono) VALUES (?, ?)");
        $stmtDuenio->execute([$duenio, $telefono]);
        $duenioId = $conn->lastInsertId();
        
        // Insertar mascota
        $stmtMascota = $conn->prepare("INSERT INTO mascotas (nombre, tipo, duenio_id) VALUES (?, ?, ?)");
        $stmtMascota->execute([$nombre, $tipo, $duenioId]);
        
        //Marcar si está en adopción
        $stmtMascota = $conn->prepare("INSERT INTO mascotas (nombre, tipo, duenio_id, en_adopcion) VALUES (?, ?, ?, ?)"); 
        $stmtMascota->execute([$nombre, $tipo, $duenioId, $en_adopcion]);

        $conn->commit();
        $success = "Mascota y dueño registrados exitosamente!";
    } catch (PDOException $e) {
        $conn->rollBack();
        $error = "Error: " . $e->getMessage();
    }
}

$pageTitle = "Registro de Mascotas";
require_once __DIR__ . '/../includes/header.php';
?>

<h1 class="text-2xl font-bold mb-6">Registro de Mascotas</h1>

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

<form method="POST" class="bg-white p-6 rounded-lg shadow-md">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Datos del Dueño -->
        <div>
            <h3 class="text-xl font-semibold mb-4 text-blue-600">Datos del Dueño</h3>
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Nombre Completo</label>
                <input 
                    type="text" 
                    name="duenio" 
                    required
                    class="w-full px-3 py-2 border rounded-md"
                >
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Teléfono</label>
                <input 
                    type="tel" 
                    name="telefono" 
                    required
                    class="w-full px-3 py-2 border rounded-md"
                >
            </div>
        </div>
        
        <!-- Datos de la Mascota -->
        <div>
            <h3 class="text-xl font-semibold mb-4 text-blue-600">Datos de la Mascota</h3>
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Nombre</label>
                <input 
                    type="text" 
                    name="nombre" 
                    required
                    class="w-full px-3 py-2 border rounded-md"
                >
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Tipo</label>
                <select name="tipo" class="w-full px-3 py-2 border rounded-md">
                    <option value="perro">Perro</option>
                    <option value="gato">Gato</option>
                    <option value="ave">Ave</option>
                    <option value="roedor">Roedor</option>
                    <option value="reptil">Reptil</option>
                    <option value="otro">Otro</option>
                </select>
            </div>
            <div class="mb-4">
            <label class="flex items-center space-x-2">
                <input 
                    type="checkbox" 
                    name="en_adopcion" 
                    value="1"
                    class="rounded"
                >
                <span>¿Está disponible para adopción?</span>
            </label>
        </div>
        </div>
    </div>
    
    <button 
        type="submit" 
        class="mt-6 bg-blue-500 hover:bg-blue-600 text-white py-2 px-6 rounded-md transition"
    >
        Registrar Mascota
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