<?php
require_once 'auth.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($auth->login($_POST['username'], $_POST['password'])) {
        header('Location: index.php');
        exit;
    } else {
        $error = '❌ Usuario o contraseña incorrectos';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>🎣 Login - Pesca PRO Elite</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-primary bg-gradient d-flex align-items-center min-vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-5 col-xl-4">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-header bg-dark text-white text-center py-5">
                        <i class="bi bi-fish-fill display-1 text-warning mb-3 d-block"></i>
                        <h1 class="display-5 fw-bold mb-0">Pesca PRO</h1>
                        <p class="lead opacity-75 mt-2">Inicia sesión</p>
                    </div>
                    <div class="card-body p-5">
                        <?php if ($error): ?>
                            <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <?= $error ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-4">
                                <label class="form-label fw-bold fs-5 mb-2">Usuario</label>
                                <input type="text" name="username" class="form-control form-control-lg" 
                                       placeholder="admin" required autocomplete="username">
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold fs-5 mb-2">Contraseña</label>
                                <input type="password" name="password" class="form-control form-control-lg" 
                                       placeholder="123456" required autocomplete="current-password">
                            </div>
                            <button type="submit" class="btn btn-lg btn-primary w-100 mb-4 shadow-lg">
                                <i class="bi bi-box-arrow-in-right me-2"></i>
                                Entrar al Sistema
                            </button>
                        </form>
                        
                        <div class="row text-center pt-4">
                            <div class="col-md-6 border-end">
                                <div class="h6 fw-bold text-primary mb-1">👑 Admin</div>
                                <code class="small">admin / 123456</code>
                            </div>
                            <div class="col-md-6">
                                <div class="h6 fw-bold text-success mb-1">🎣 Usuario</div>
                                <code class="small">adrian_garcia / 123456</code>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
