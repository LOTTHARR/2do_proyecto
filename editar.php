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

$tituloPagina = 'Editar Contacto - Agenda de Contactos';
require_once __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <h1>Editar contacto</h1>
    <a href="index.php" class="btn btn-secondary">Volver al listado</a>
</div>

<form action="actualizar.php" method="POST" enctype="multipart/form-data" class="form-card">
    <input type="hidden" name="id" value="<?= e($contacto['id']) ?>">

    <div class="form-group">
        <label for="nombre">Nombre *</label>
        <input type="text" id="nombre" name="nombre" maxlength="100" value="<?= e($contacto['nombre']) ?>" required>
    </div>

    <div class="form-group">
        <label for="apellido">Apellido *</label>
        <input type="text" id="apellido" name="apellido" maxlength="100" value="<?= e($contacto['apellido']) ?>" required>
    </div>

    <div class="form-group">
        <label for="telefono">Teléfono *</label>
        <input type="tel" id="telefono" name="telefono" maxlength="30" value="<?= e($contacto['telefono']) ?>" required>
    </div>

    <div class="form-group">
        <label>Foto actual</label>
        <div>
            <img
                src="archivos/<?= e($contacto['foto']) ?>"
                alt="Foto actual de <?= e($contacto['nombre']) ?>"
                class="contact-avatar"
            >
        </div>
        <label for="foto">Cambiar foto (opcional, JPG, PNG o WEBP, máx. 3MB)</label>
        <input type="file" id="foto" name="foto" accept="image/*">
    </div>

    <div class="form-group">
        <label for="email">Correo electrónico</label>
        <input type="email" id="email" name="email" maxlength="150" value="<?= e($contacto['email'] ?? '') ?>">
    </div>

    <div class="form-group">
        <label for="direccion">Dirección</label>
        <input type="text" id="direccion" name="direccion" maxlength="255" value="<?= e($contacto['direccion'] ?? '') ?>">
    </div>

    <div class="form-group">
        <label for="notas">Notas</label>
        <textarea id="notas" name="notas" rows="4"><?= e($contacto['notas'] ?? '') ?></textarea>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Actualizar contacto</button>
        <a href="ver.php?id=<?= e($contacto['id']) ?>" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
