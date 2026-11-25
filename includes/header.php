<?php
$tituloPagina = $tituloPagina ?? 'Agenda de Contactos';
$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($tituloPagina) ?></title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header class="site-header">
        <div class="container header-content">
            <a href="index.php" class="brand">Agenda de Contactos</a>
            <?php require_once __DIR__ . '/menu.php'; ?>
        </div>
    </header>
    <main class="container main-content">
        <?php if ($flash): ?>
            <div class="alert alert-<?= e($flash['tipo']) ?>" role="alert">
                <?= e($flash['mensaje']) ?>
            </div>
        <?php endif; ?>
