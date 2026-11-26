<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';
$pageTitle = 'Nuevo estudiante | ITLA CRUD Manager';
$errors = [];
$data = ['matricula'=>'', 'nombre'=>'', 'apellido'=>'', 'correo'=>'', 'carrera'=>''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    foreach ($data as $key => $_) $data[$key] = trim($_POST[$key] ?? '');
    if ($data['matricula'] === '') $errors[] = 'La matrícula es obligatoria.';
    if ($data['nombre'] === '') $errors[] = 'El nombre es obligatorio.';
    if ($data['apellido'] === '') $errors[] = 'El apellido es obligatorio.';
    if (!filter_var($data['correo'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Introduce un correo válido.';
    if ($data['carrera'] === '') $errors[] = 'La carrera es obligatoria.';
    
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

    if (!$errors) {
        try {
            $stmt = $pdo->prepare('INSERT INTO estudiantes (matricula,nombre,apellido,correo,carrera) VALUES (:matricula,:nombre,:apellido,:correo,:carrera)');
            $stmt->execute($data);
            $_SESSION['flash'] = 'Estudiante registrado correctamente.';
            redirect('index.php');
        } catch (PDOException $e) {
            $errors[] = $e->getCode() === '23000' ? 'La matrícula o el correo ya están registrados.' : 'No fue posible guardar el registro.';
        }
    }
}
require __DIR__ . '/header.php';
?>
<section class="form-heading"><p class="eyebrow">REGISTRO</p><h1>Nuevo estudiante</h1><p class="muted">Completa los datos para agregar un estudiante.</p></section>
<?php if ($errors): ?><div class="alert error"><?php foreach ($errors as $error): ?><p><?= e($error) ?></p><?php endforeach; ?></div><?php endif; ?>
<form class="panel form-panel" method="post">
  <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
  <?php foreach (['matricula'=>'Matrícula','nombre'=>'Nombre','apellido'=>'Apellido','correo'=>'Correo electrónico','carrera'=>'Carrera'] as $key=>$label): ?>
    <label><?= e($label) ?><input name="<?= e($key) ?>" value="<?= e($data[$key]) ?>" <?= $key === 'correo' ? 'type="email"' : 'type="text"' ?> required maxlength="120"></label>
  <?php endforeach; ?>
  <div class="form-actions"><a class="button light" href="index.php">Cancelar</a><button class="button primary" type="submit">Guardar estudiante</button></div>
</form>
<?php require __DIR__ . '/footer.php'; ?>
