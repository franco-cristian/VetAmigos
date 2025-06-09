<?php
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/auth.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $stmt = $conn->prepare("SELECT id, email, password_hash, rol FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_rol'] = $user['rol'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Credenciales incorrectas";
    }
}

$pageTitle = "Iniciar Sesión";
require_once __DIR__ . '/../includes/header.php';
?>

<style>
    body {
        background-image: url('/VetAmigos/includes/mascotas.jpg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: scroll;
    }
    .bg-white {
        background-color: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(8px);
    }
</style>
<div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md mx-auto mt-10">
    <h1 class="text-2xl font-bold text-center mb-6">Inicio de Sesión</h1>
    <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?= $error ?>
        </div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Email</label>
            <input 
                type="email" 
                name="email" 
                required
                class="w-full px-3 py-2 border rounded-md"
            >
        </div>
        <div class="mb-6">
            <label class="block text-gray-700 mb-2">Contraseña</label>
            <input 
                type="password" 
                name="password" 
                required
                class="w-full px-3 py-2 border rounded-md"
            >
        </div>
        <button 
            type="submit" 
            class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600 transition"
        >
            Iniciar Sesión
        </button>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>