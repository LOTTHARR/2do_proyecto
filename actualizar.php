<?php
require_once __DIR__ . '/config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
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

$nombre    = trim($_POST['nombre'] ?? '');
$apellido  = trim($_POST['apellido'] ?? '');
$telefono  = trim($_POST['telefono'] ?? '');
$email     = trim($_POST['email'] ?? '');
$direccion = trim($_POST['direccion'] ?? '');
$notas     = trim($_POST['notas'] ?? '');

if ($nombre === '' || $apellido === '' || $telefono === '') {
    setFlash('error', 'Los campos nombre, apellido y teléfono son obligatorios.');
    header('Location: editar.php?id=' . $id);
    exit;
}

if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    setFlash('error', 'El formato del correo electrónico no es válido.');
    header('Location: editar.php?id=' . $id);
    exit;
}

$nombreFoto = $contacto['foto'];

if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
    $resultadoFoto = procesarImagenSubida($_FILES['foto']);
    if (!$resultadoFoto['ok']) {
        setFlash('error', $resultadoFoto['error']);
        header('Location: editar.php?id=' . $id);
        exit;
    }

    $rutaAnterior = __DIR__ . '/archivos/' . $contacto['foto'];
    if (is_file($rutaAnterior)) {
        unlink($rutaAnterior);
    }
    $nombreFoto = $resultadoFoto['nombre'];
}

$sql = 'UPDATE contactos
        SET nombre = :nombre,
            apellido = :apellido,
            telefono = :telefono,
            foto = :foto,
            email = :email,
            direccion = :direccion,
            notas = :notas
        WHERE id = :id';

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':nombre'    => $nombre,
    ':apellido'  => $apellido,
    ':telefono'  => $telefono,
    ':foto'      => $nombreFoto,
    ':email'     => $email !== '' ? $email : null,
    ':direccion' => $direccion !== '' ? $direccion : null,
    ':notas'     => $notas !== '' ? $notas : null,
    ':id'        => $id,
]);

setFlash('success', 'Contacto actualizado correctamente.');
header('Location: ver.php?id=' . $id);
exit;
