<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('index.php');
verify_csrf();
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if ($id) {
    $stmt = $pdo->prepare('DELETE FROM estudiantes WHERE id = ?');
    $stmt->execute([$id]);
    $_SESSION['flash'] = $stmt->rowCount() ? 'Estudiante eliminado correctamente.' : 'El estudiante ya no existe.';
}
redirect('index.php');
