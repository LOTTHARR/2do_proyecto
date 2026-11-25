<?php
require_once __DIR__ . '/config/db.php';

$q = trim($_GET['q'] ?? '');

if ($q !== '') {
    $sql = 'SELECT * FROM contactos
            WHERE nombre LIKE :q1
               OR apellido LIKE :q2
               OR telefono LIKE :q3
               OR email LIKE :q4
            ORDER BY nombre ASC, apellido ASC';
    $stmt = $pdo->prepare($sql);
    $termino = '%' . $q . '%';
    $stmt->execute([
        ':q1' => $termino,
        ':q2' => $termino,
        ':q3' => $termino,
        ':q4' => $termino,
    ]);
} else {
    $stmt = $pdo->prepare('SELECT * FROM contactos ORDER BY nombre ASC, apellido ASC');
    $stmt->execute();
}

$contactos = $stmt->fetchAll();

$tituloPagina = 'Agenda de Contactos';
require_once __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <h1>Contactos</h1>
    <a href="crear.php" class="btn btn-primary">+ Agregar contacto</a>
</div>

<form method="GET" action="index.php" class="search-form">
    <input
        type="search"
        name="q"
        value="<?= e($q) ?>"
        placeholder="Buscar por nombre, apellido, teléfono o email..."
        aria-label="Buscar contactos"
    >
    <button type="submit" class="btn btn-primary">Buscar</button>
    <?php if ($q !== ''): ?>
        <a href="index.php" class="btn btn-secondary">Limpiar</a>
    <?php endif; ?>
</form>

<?php if (empty($contactos)): ?>
    <div class="empty-state">
        <?php if ($q !== ''): ?>
            <p>No se encontraron contactos para "<strong><?= e($q) ?></strong>".</p>
        <?php else: ?>
            <p>Aún no hay contactos registrados. ¡Agrega el primero!</p>
        <?php endif; ?>
    </div>
<?php else: ?>
    <div class="grid-contactos">
        <?php foreach ($contactos as $contacto): ?>
            <article class="card">
                <div class="card-body">
                    <img
                        src="archivos/<?= e($contacto['foto']) ?>"
                        alt="Foto de <?= e($contacto['nombre'] . ' ' . $contacto['apellido']) ?>"
                        class="contact-avatar"
                    >
                    <h2 class="contact-name"><?= e($contacto['nombre'] . ' ' . $contacto['apellido']) ?></h2>
                    <p class="contact-meta">Tel: <?= e($contacto['telefono']) ?></p>
                    <?php if (!empty($contacto['email'])): ?>
                        <p class="contact-meta">Email: <?= e($contacto['email']) ?></p>
                    <?php endif; ?>
                </div>
                <div class="card-actions">
                    <a href="ver.php?id=<?= e($contacto['id']) ?>" class="btn btn-secondary btn-sm">Ver</a>
                    <a href="editar.php?id=<?= e($contacto['id']) ?>" class="btn btn-primary btn-sm">Editar</a>
                    <a href="eliminar.php?id=<?= e($contacto['id']) ?>" class="btn btn-danger btn-sm">Eliminar</a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
