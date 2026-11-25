<?php
require_once __DIR__ . '/config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$nombre    = trim($_POST['nombre'] ?? '');
$apellido  = trim($_POST['apellido'] ?? '');
$telefono  = trim($_POST['telefono'] ?? '');
$email     = trim($_POST['email'] ?? '');
$direccion = trim($_POST['direccion'] ?? '');
$notas     = trim($_POST['notas'] ?? '');

if ($nombre === '' || $apellido === '' || $telefono === '') {
    setFlash('error', 'Los campos nombre, apellido y teléfono son obligatorios.');
    header('Location: crear.php');
    exit;
}

if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    setFlash('error', 'El formato del correo electrónico no es válido.');
    header('Location: crear.php');
    exit;
}

if (!isset($_FILES['foto']) || $_FILES['foto']['error'] === UPLOAD_ERR_NO_FILE) {
    setFlash('error', 'La foto del contacto es obligatoria.');
    header('Location: crear.php');
    exit;
}

$resultadoFoto = procesarImagenSubida($_FILES['foto']);
if (!$resultadoFoto['ok']) {
    setFlash('error', $resultadoFoto['error']);
    header('Location: crear.php');
    exit;
}

$sql = 'INSERT INTO contactos (nombre, apellido, telefono, foto, email, direccion, notas)
        VALUES (:nombre, :apellido, :telefono, :foto, :email, :direccion, :notas)';

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':nombre'    => $nombre,
    ':apellido'  => $apellido,
    ':telefono'  => $telefono,
    ':foto'      => $resultadoFoto['nombre'],
    ':email'     => $email !== '' ? $email : null,
    ':direccion' => $direccion !== '' ? $direccion : null,
    ':notas'     => $notas !== '' ? $notas : null,
]);

setFlash('success', 'Contacto guardado correctamente.');
header('Location: index.php');
exit;
