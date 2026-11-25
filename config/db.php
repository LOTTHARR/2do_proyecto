<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = 'localhost';
$dbname = 'agenda_contactos';
$username = 'root';
$password = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    error_log('Error de conexión PDO: ' . $e->getMessage());
    exit('Ocurrió un error al conectar con la base de datos. Inténtalo más tarde.');
}

function e($valor): string
{
    return htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8');
}

function setFlash(string $tipo, string $mensaje): void
{
    $_SESSION['flash'] = [
        'tipo' => $tipo,
        'mensaje' => $mensaje,
    ];
}

function getFlash(): ?array
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

function generarCsrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verificarCsrfToken(?string $token): bool
{
    return isset($_SESSION['csrf_token']) && is_string($token) && hash_equals($_SESSION['csrf_token'], $token);
}

function procesarImagenSubida(array $archivo, string $directorioDestino = __DIR__ . '/../archivos'): array
{
    if ($archivo['error'] !== UPLOAD_ERR_OK) {
        return ['ok' => false, 'error' => 'Error al subir la imagen.'];
    }

    $maxBytes = 3 * 1024 * 1024; // 3MB
    if ($archivo['size'] > $maxBytes) {
        return ['ok' => false, 'error' => 'La imagen supera el tamaño máximo permitido de 3MB.'];
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($archivo['tmp_name']);
    $extensionesPermitidas = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
    ];

    if (!isset($extensionesPermitidas[$mime]) || @getimagesize($archivo['tmp_name']) === false) {
        return ['ok' => false, 'error' => 'Formato de imagen no válido. Solo se permiten JPG, PNG o WEBP.'];
    }

    if (!is_dir($directorioDestino)) {
        mkdir($directorioDestino, 0755, true);
    }

    $nombreArchivo = uniqid('contacto_', true) . '.' . $extensionesPermitidas[$mime];
    $rutaDestino = rtrim($directorioDestino, '/\\') . DIRECTORY_SEPARATOR . $nombreArchivo;

    if (!move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
        return ['ok' => false, 'error' => 'No se pudo guardar la imagen en el servidor.'];
    }

    return ['ok' => true, 'nombre' => $nombreArchivo];
}
