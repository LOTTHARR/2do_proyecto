<?php
require_once __DIR__ . '/config/db.php';

$tituloPagina = 'Nuevo Contacto - Agenda de Contactos';
require_once __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <h1>Nuevo contacto</h1>
    <a href="index.php" class="btn btn-secondary">Volver al listado</a>
</div>

<form action="guardar.php" method="POST" enctype="multipart/form-data" class="form-card">
    <div class="form-group">
        <label for="nombre">Nombre *</label>
        <input type="text" id="nombre" name="nombre" maxlength="100" required>
    </div>

    <div class="form-group">
        <label for="apellido">Apellido *</label>
        <input type="text" id="apellido" name="apellido" maxlength="100" required>
    </div>

    <div class="form-group">
        <label for="telefono">Teléfono *</label>
        <input type="tel" id="telefono" name="telefono" maxlength="30" required>
    </div>

    <div class="form-group">
        <label for="foto">Foto * (JPG, PNG o WEBP, máx. 3MB)</label>
        <input type="file" id="foto" name="foto" accept="image/*" required>
    </div>

    <div class="form-group">
        <label for="email">Correo electrónico</label>
        <input type="email" id="email" name="email" maxlength="150">
    </div>

    <div class="form-group">
        <label for="direccion">Dirección</label>
        <input type="text" id="direccion" name="direccion" maxlength="255">
    </div>

    <div class="form-group">
        <label for="notas">Notas</label>
        <textarea id="notas" name="notas" rows="4"></textarea>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Guardar contacto</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
