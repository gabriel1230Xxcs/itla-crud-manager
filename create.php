
<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

$pageTitle = 'Nuevo estudiante | ITLA CRUD Manager';

$errors = [];

$data = [
    'matricula' => '',
    'nombre' => '',
    'apellido' => '',
    'correo' => '',
    'carrera' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    verify_csrf();

    // Obtener y limpiar los datos
    foreach ($data as $key => $_) {
        $data[$key] = trim($_POST[$key] ?? '');
    }

    $data['correo'] = mb_strtolower($data['correo'], 'UTF-8');


    // Validar campos obligatorios
    if ($data['matricula'] === '') {
        $errors[] = 'La matrícula es obligatoria.';
    }

    if ($data['nombre'] === '') {
        $errors[] = 'El nombre es obligatorio.';
    }

    if ($data['apellido'] === '') {
        $errors[] = 'El apellido es obligatorio.';
    }

    if ($data['correo'] === '') {
        $errors[] = 'El correo es obligatorio.';
    } elseif (!filter_var($data['correo'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Introduce un correo válido.';
    }

    if ($data['carrera'] === '') {
        $errors[] = 'La carrera es obligatoria.';
    }

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

    // Registrar estudiante si no existen errores
    if (!$errors) {
        try {

            $stmt = $pdo->prepare(
                'INSERT INTO estudiantes
                (matricula, nombre, apellido, correo, carrera)
                VALUES
                (:matricula, :nombre, :apellido, :correo, :carrera)'
            );

            $stmt->execute($data);

            $_SESSION['flash'] = 'Estudiante registrado correctamente.';

            redirect('index.php');

        } catch (PDOException $e) {

            $errors[] = $e->getCode() === '23000'
                ? 'La matrícula o el correo ya están registrados.'
                : 'No fue posible guardar el registro.';
        }
    }
}

require __DIR__ . '/header.php';
?>

<section class="form-heading">

    <p class="eyebrow">REGISTRO</p>

    <h1>Nuevo estudiante</h1>

    <p class="muted">
        Completa los datos para agregar un estudiante.
    </p>

</section>

<?php if ($errors): ?>

    <div class="alert error">

        <?php foreach ($errors as $error): ?>

            <p><?= e($error) ?></p>

        <?php endforeach; ?>

    </div>

<?php endif; ?>

<form class="panel form-panel" method="post">

    <input
        type="hidden"
        name="csrf_token"
        value="<?= e(csrf_token()) ?>"
    >

    <label>
        Matrícula
        <input
            name="matricula"
            type="text"
            value="<?= e($data['matricula']) ?>"
            maxlength="30"
            required
        >
    </label>

    <label>
        Nombre
        <input
            name="nombre"
            type="text"
            value="<?= e($data['nombre']) ?>"
            maxlength="80"
            required
        >
    </label>

    <label>
        Apellido
        <input
            name="apellido"
            type="text"
            value="<?= e($data['apellido']) ?>"
            maxlength="80"
            required
        >
    </label>

    <label>
        Correo electrónico
        <input
            name="correo"
            type="email"
            value="<?= e($data['correo']) ?>"
            maxlength="150"
            required
        >
    </label>

    <label>
        Carrera
        <input
            name="carrera"
            type="text"
            value="<?= e($data['carrera']) ?>"
            maxlength="120"
            required
        >
    </label>

    <div class="form-actions">

        <a class="button light" href="index.php">
            Cancelar
        </a>

        <button class="button primary" type="submit">
            Guardar estudiante
        </button>

    </div>

</form>

<?php require __DIR__ . '/footer.php'; ?>
