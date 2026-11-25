<?php
require_once __DIR__ . '/config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $token = $_POST['csrf_token'] ?? null;

    if (!$id || !verificarCsrfToken($token)) {
        setFlash('error', 'Solicitud de eliminación no válida.');
        header('Location: index.php');
        exit;
    }

    $stmt = $pdo->prepare('SELECT foto FROM contactos WHERE id = :id');
    $stmt->execute([':id' => $id]);
    $contacto = $stmt->fetch();

    if ($contacto) {
        $stmtDel = $pdo->prepare('DELETE FROM contactos WHERE id = :id');
        $stmtDel->execute([':id' => $id]);

        $rutaFoto = __DIR__ . '/archivos/' . $contacto['foto'];
        if (is_file($rutaFoto)) {
            unlink($rutaFoto);
        }
        setFlash('success', 'Contacto eliminado correctamente.');
    } else {
        setFlash('error', 'El contacto no existe.');
    }

    header('Location: index.php');
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    setFlash('error', 'Contacto no válido.');
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM contactos WHERE id = :id');
$stmt->execute([':id' => $id]);
$contacto = $stmt->fetch();

if (!$contacto) {
    setFlash('error', 'Contacto no encontrado.');
    header('Location: index.php');
    exit;
}

$csrfToken = generarCsrfToken();
$tituloPagina = 'Eliminar Contacto - Agenda de Contactos';
require_once __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <h1>Eliminar contacto</h1>
    <a href="index.php" class="btn btn-secondary">Volver al listado</a>
</div>

<section class="detail-card">
    <div class="detail-header">
        <img
            src="archivos/<?= e($contacto['foto']) ?>"
            alt="Foto de <?= e($contacto['nombre'] . ' ' . $contacto['apellido']) ?>"
            class="contact-avatar-lg"
        >
        <div>
            <h2><?= e($contacto['nombre'] . ' ' . $contacto['apellido']) ?></h2>
            <p class="contact-meta">Tel: <?= e($contacto['telefono']) ?></p>
            <?php if (!empty($contacto['email'])): ?>
                <p class="contact-meta">Email: <?= e($contacto['email']) ?></p>
            <?php endif; ?>
        </div>
    </div>

    <p>¿Estás seguro de que deseas eliminar este contacto? Esta acción no se puede deshacer.</p>

    <form action="eliminar.php" method="POST" class="form-actions">
        <input type="hidden" name="id" value="<?= e($contacto['id']) ?>">
        <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
        <button type="submit" class="btn btn-danger">Confirmar eliminación</button>
        <a href="ver.php?id=<?= e($contacto['id']) ?>" class="btn btn-secondary">Cancelar</a>
    </form>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
