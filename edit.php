<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) redirect('index.php');
$stmt = $pdo->prepare('SELECT * FROM estudiantes WHERE id = ?');
$stmt->execute([$id]);
$data = $stmt->fetch();
if (!$data) { http_response_code(404); exit('Estudiante no encontrado.'); }

$pageTitle = 'Editar estudiante | ITLA CRUD Manager';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    foreach (['matricula','nombre','apellido','correo','carrera'] as $key) $data[$key] = trim($_POST[$key] ?? '');
    if ($data['matricula'] === '' || $data['nombre'] === '' || $data['apellido'] === '' || $data['carrera'] === '') $errors[] = 'Todos los campos son obligatorios.';
    if (!filter_var($data['correo'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Introduce un correo válido.';
    if (!$errors) {
        // Validar longitud máxima de los campos
if (mb_strlen($data['matricula']) > 30) {
    $errors[] = 'La matrícula no puede superar los 30 caracteres.';
}

if (mb_strlen($data['nombre']) > 80) {
    $errors[] = 'El nombre no puede superar los 80 caracteres.';
}

if (mb_strlen($data['apellido']) > 80) {
    $errors[] = 'El apellido no puede superar los 80 caracteres.';
}

if (mb_strlen($data['correo']) > 150) {
    $errors[] = 'El correo no puede superar los 150 caracteres.';
}

if (mb_strlen($data['carrera']) > 120) {
    $errors[] = 'La carrera no puede superar los 120 caracteres.';
}
        try {
            $update = $pdo->prepare('UPDATE estudiantes SET matricula=:matricula,nombre=:nombre,apellido=:apellido,correo=:correo,carrera=:carrera WHERE id=:id');
            $update->execute(['matricula'=>$data['matricula'],'nombre'=>$data['nombre'],'apellido'=>$data['apellido'],'correo'=>$data['correo'],'carrera'=>$data['carrera'],'id'=>$id]);
            $_SESSION['flash'] = 'Estudiante actualizado correctamente.';
            redirect('index.php');
        } catch (PDOException $e) {
            $errors[] = $e->getCode() === '23000' ? 'La matrícula o el correo ya pertenecen a otro estudiante.' : 'No fue posible actualizar el registro.';
        }
    }
}
require __DIR__ . '/header.php';
?>
<section class="form-heading"><p class="eyebrow">ACTUALIZACIÓN</p><h1>Editar estudiante</h1><p class="muted">Modifica la información del registro.</p></section>
<?php if ($errors): ?><div class="alert error"><?php foreach ($errors as $error): ?><p><?= e($error) ?></p><?php endforeach; ?></div><?php endif; ?>
<form class="panel form-panel" method="post">
  <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
  <?php foreach (['matricula'=>'Matrícula','nombre'=>'Nombre','apellido'=>'Apellido','correo'=>'Correo electrónico','carrera'=>'Carrera'] as $key=>$label): ?>
    <label><?= e($label) ?><input name="<?= e($key) ?>" value="<?= e($data[$key]) ?>" <?= $key === 'correo' ? 'type="email"' : 'type="text"' ?> required maxlength="120"></label>
  <?php endforeach; ?>
  <div class="form-actions"><a class="button light" href="index.php">Cancelar</a><button class="button primary" type="submit">Guardar cambios</button></div>
</form>
<?php require __DIR__ . '/footer.php'; ?>
