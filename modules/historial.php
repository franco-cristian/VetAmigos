<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/database.php';
requireAuth();

// Obtener historial de visitas
$historial = $conn->query("
    SELECT m.nombre AS mascota, d.nombre AS duenio, t.fecha, t.hora, t.motivo 
    FROM turnos t
    JOIN mascotas m ON t.mascota_id = m.id
    JOIN duenios d ON m.duenio_id = d.id
    ORDER BY t.fecha DESC
")->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = "Historial de Visitas";
require_once __DIR__ . '/../includes/header.php';
?>

<h1 class="text-2xl font-bold mb-6">Historial de Visitas</h1>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left">Mascota</th>
                <th class="px-6 py-3 text-left">Dueño</th>
                <th class="px-6 py-3 text-left">Fecha</th>
                <th class="px-6 py-3 text-left">Hora</th>
                <th class="px-6 py-3 text-left">Motivo</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            <?php foreach ($historial as $visita): ?>
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4"><?= $visita['mascota'] ?></td>
                <td class="px-6 py-4"><?= $visita['duenio'] ?></td>
                <td class="px-6 py-4"><?= date('d/m/Y', strtotime($visita['fecha'])) ?></td>
                <td class="px-6 py-4"><?= $visita['hora'] ?></td>
                <td class="px-6 py-4"><?= $visita['motivo'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
        <button 
        type="button"
        class="mt-6 bg-blue-500 hover:bg-blue-600 text-white py-2 px-6 rounded-md transition"
        onclick="window.location.href='../modules/dashboard.php';"
    >
        Volver
    </button>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>