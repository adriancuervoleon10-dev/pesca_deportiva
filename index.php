<?php
require_once 'auth.php';
$auth->requireLogin();

require_once 'config.php';
$db = new PescaDB();
$conn = $db->getConnection();

$user_id = $_SESSION['user_id'];
$where_user = $_SESSION['es_admin'] ? "" : "WHERE c.usuario_id = $user_id";

// Crear captura
if ($_POST && isset($_POST['action']) && $_POST['action'] === 'create') {
    $stmt = $conn->prepare("INSERT INTO capturas (usuario_id, pescador_id, especie, peso, largo, lugar, fecha, señuelo, tecnica, condiciones, notas, trofeo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisdsdsssssi", $user_id, $_POST['pescador_id'], $_POST['especie'], $_POST['peso'], 
                      $_POST['largo'], $_POST['lugar'], $_POST['fecha'], $_POST['señuelo'], $_POST['tecnica'], 
                      $_POST['condiciones'], $_POST['notas'], $_POST['trofeo']);
    $stmt->execute();
    header('Location: index.php');
    exit;
}

// Estadísticas
$total_capturas = $conn->query("SELECT COUNT(*) as total FROM capturas $where_user")->fetch_assoc()['total'];
$record_personal = $conn->query("SELECT MAX(peso) as record FROM capturas $where_user")->fetch_assoc()['record'] ?? 0;
$result = $conn->query("SELECT c.*, p.nombre as pescador FROM capturas c LEFT JOIN pescadores p ON c.pescador_id = p.id $where_user ORDER BY c.fecha DESC LIMIT 50");
$pescadores = $conn->query("SELECT * FROM pescadores");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>🎣 Pesca PRO Elite</title>
    
    <!-- BOOTSTRAP 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Bootstrap Utilities para pesca -->
    <style>
        :root {
            --bs-gradient-fish: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%);
            --bs-gradient-water: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            --bs-gradient-gold: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }
    </style>
</head>
<body class="bg-primary bg-gradient min-vh-100">
    
    <!-- 🐟 NAVBAR BOOTSTRAP -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark bg-opacity-90 border-bottom border-primary-subtle">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3" href="#">
                <i class="bi bi-fish-fill text-warning me-2"></i>
                Pesca PRO Elite
            </a>
            <div class="navbar-nav ms-auto align-items-center">
                <span class="nav-link text-white me-3">
                    <i class="bi bi-person-circle me-1"></i>
                    <?= $_SESSION['nombre'] ?>
                    <?php if ($_SESSION['es_admin']): ?>
                        <span class="badge bg-danger">ADMIN</span>
                    <?php endif; ?>
                </span>
                <a href="logout.php" class="btn btn-outline-warning btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Salir
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <!-- 🏆 HERO JUMBOTRON BOOTSTRAP -->
        <div class="card border-0 shadow-lg bg-white bg-opacity-90 rounded-4 mb-5 overflow-hidden">
            <div class="card-body p-5 text-center position-relative">
                <div class="position-absolute top-0 start-50 translate-middle-x pt-3">
                    <i class="bi bi-fish display-1 text-primary opacity-25"></i>
                </div>
                <h1 class="display-4 fw-bold text-primary mb-4">
                    🎣 ¡Hola <?= $_SESSION['nombre'] ?>!
                </h1>
                <p class="lead text-muted mb-5">Gestiona tus capturas de pesca deportiva</p>
                
                <!-- STATS CARDS BOOTSTRAP -->
                <div class="row g-4 justify-content-center">
                    <div class="col-lg-4 col-md-6">
                        <div class="card border-0 h-100 shadow-sm bg-gradient bg-primary text-white text-center p-4 rounded-3">
                            <i class="bi bi-fish-fill display-6 mb-3 opacity-75"></i>
                            <div class="h2 mb-1"><?= number_format($total_capturas) ?></div>
                            <div class="h6 fw-normal">Capturas Totales</div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="card border-0 h-100 shadow-sm bg-gradient bg-warning text-dark text-center p-4 rounded-3">
                            <i class="bi bi-trophy-fill display-6 mb-3"></i>
                            <div class="h2 mb-1"><?= number_format($record_personal, 1) ?>kg</div>
                            <div class="h6 fw-normal">RECORD PERSONAL</div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="card border-0 h-100 shadow-sm bg-success bg-gradient text-white text-center p-4 rounded-3">
                            <i class="bi bi-graph-up-arrow display-6 mb-3"></i>
                            <div class="h4 mb-1">ACTIVO</div>
                            <div class="h6 fw-normal">Estado Premium</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 📝 FORM NUEVA CAPTURA BOOTSTRAP -->
        <div class="card border-0 shadow-lg rounded-4 mb-5 overflow-hidden">
            <div class="card-header bg-gradient bg-primary text-white py-4">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <i class="bi bi-plus-circle-fill display-6"></i>
                    </div>
                    <div class="col">
                        <h3 class="mb-0 fw-bold">Nueva Captura</h3>
                        <small>Registra tu próxima pesca legendaria</small>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <form method="POST" class="row g-3">
                    <input type="hidden" name="action" value="create">
                    
                    <div class="col-lg-2 col-md-3 col-sm-6">
                        <label class="form-label fw-semibold">Pescador</label>
                        <select name="pescador_id" class="form-select form-select-lg" required>
                            <option value="">Selecciona...</option>
                            <?php while($p = $pescadores->fetch_assoc()): ?>
                            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nombre']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="col-lg-2 col-md-3 col-sm-6">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-fish me-1"></i> Especie
                        </label>
                        <input type="text" name="especie" class="form-control form-control-lg" 
                               placeholder="Black Bass" required>
                    </div>
                    
                    <div class="col-lg-1 col-md-2 col-sm-6">
                        <label class="form-label fw-semibold">Peso (kg)</label>
                        <input type="number" step="0.01" min="0" name="peso" class="form-control form-control-lg" required>
                    </div>
                    
                    <div class="col-lg-1 col-md-2 col-sm-6">
                        <label class="form-label fw-semibold">Largo (cm)</label>
                        <input type="number" step="0.1" min="0" name="largo" class="form-control form-control-lg" required>
                    </div>
                    
                    <div class="col-lg-2 col-md-3 col-sm-6">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-geo-alt me-1"></i> Lugar
                        </label>
                        <input type="text" name="lugar" class="form-control form-control-lg" 
                               placeholder="Riosequillo" required>
                    </div>
                    
                    <div class="col-lg-1 col-md-2 col-sm-6">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-calendar me-1"></i> Fecha
                        </label>
                        <input type="date" name="fecha" class="form-control form-control-lg" 
                               value="<?= date('Y-m-d') ?>" required>
                    </div>
                    
                    <div class="col-lg-1 col-md-2 col-sm-6">
                        <label class="form-label fw-semibold">🦈 Trofeo</label>
                        <select name="trofeo" class="form-select form-select-lg">
                            <option value="0">No</option>
                            <option value="1">Sí</option>
                        </select>
                    </div>
                    
                    <div class="col-lg-2 col-md-3 col-sm-6 d-grid">
                        <button type="submit" class="btn btn-lg btn-warning text-dark fw-bold shadow-lg">
                            <i class="bi bi-plus-circle me-2"></i>
                            REGISTRAR
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 📊 TABLA RESPONSIVE BOOTSTRAP -->
        <div class="card border-0 shadow-xl rounded-4 overflow-hidden">
            <div class="card-header bg-gradient bg-success bg-opacity-90 text-white py-4">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="mb-0 fw-bold">
                            <i class="bi bi-table me-2"></i>
                            Últimas Capturas
                        </h3>
                        <small class="opacity-75">Total: <?= mysqli_num_rows($result) ?> registros</small>
                    </div>
                    <div class="col-auto">
                        <span class="badge bg-light text-dark fs-6 fw-bold">
                            <?= $_SESSION['es_admin'] ? '👑 VISTA ADMIN' : '👤 VISTA PERSONAL' ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="border-0 py-4">
                                    <i class="bi bi-hash"></i> ID
                                </th>
                                <th class="border-0 py-4">
                                    <i class="bi bi-person"></i> Pescador
                                </th>
                                <th class="border-0 py-4">
                                    <i class="bi bi-fish"></i> Especie
                                </th>
                                <th class="border-0 py-4">
                                    <i class="bi bi-weigh-scale"></i> Peso
                                </th>
                                <th class="border-0 py-4">Largo</th>
                                <th class="border-0 py-4">
                                    <i class="bi bi-geo-alt"></i>
                                </th>
                                <th class="border-0 py-4">
                                    <i class="bi bi-calendar"></i>
                                </th>
                                <th class="border-0 py-4">Estado</th>
                                <th class="border-0 py-4">
                                    <i class="bi bi-gear"></i>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $result->fetch_assoc()): ?>
                            <tr class="border-bottom border-secondary-subtle">
                                <td class="fw-bold text-primary py-3">#<?= $row['id'] ?></td>
                                <td class="text-muted small"><?= htmlspecialchars($row['pescador'] ?: '-') ?></td>
                                <td>
                                    <strong class="text-dark"><?= htmlspecialchars($row['especie']) ?></strong>
                                </td>
                                <td class="fw-bold fs-5 text-warning">
                                    <?= number_format($row['peso'], 2) ?>kg
                                </td>
                                <td class="fw-semibold"><?= $row['largo'] ?>cm</td>
                                <td class="fw-semibold text-primary"><?= htmlspecialchars($row['lugar']) ?></td>
                                <td class="text-muted small"><?= date('d/m/Y', strtotime($row['fecha'])) ?></td>
                                <td>
                                    <?php if($row['trofeo']): ?>
                                        <span class="badge bg-warning text-dark fw-bold px-3 py-2">
                                            <i class="bi bi-trophy-fill me-1"></i> TROFEO
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary px-3 py-2">Normal</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger" 
                                           onclick="return confirm('🗑️ Eliminar <?= htmlspecialchars($row['especie']) ?>?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- BOOTSTRAP JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
