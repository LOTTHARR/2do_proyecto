<?php
require_once __DIR__ . '/config/db.php';

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

$tituloPagina = $contacto['nombre'] . ' ' . $contacto['apellido'] . ' - Agenda de Contactos';
require_once __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <h1>Detalle del contacto</h1>
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
            <p class="contact-meta">Registrado el <?= e($contacto['created_at']) ?></p>
        </div>
    </div>

    <div class="detail-list">
        <div class="detail-item">
            <strong>Teléfono:</strong> <?= e($contacto['telefono']) ?>
        </div>
        <div class="detail-item">
            <strong>Email:</strong> <?= !empty($contacto['email']) ? e($contacto['email']) : 'No especificado' ?>
        </div>
        <div class="detail-item">
            <strong>Dirección:</strong> <?= !empty($contacto['direccion']) ? e($contacto['direccion']) : 'No especificada' ?>
        </div>
        <div class="detail-item">
            <strong>Notas:</strong> <?= !empty($contacto['notas']) ? nl2br(e($contacto['notas'])) : 'Sin notas' ?>
        </div>
    </div>

    <div class="form-actions">
        <a href="editar.php?id=<?= e($contacto['id']) ?>" class="btn btn-primary">Editar</a>
        <a href="eliminar.php?id=<?= e($contacto['id']) ?>" class="btn btn-danger">Eliminar</a>
        <a href="index.php" class="btn btn-secondary">Volver</a>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
