
<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

$pageTitle = 'Estudiantes | ITLA CRUD Manager';

$q = trim($_GET['q'] ?? '');

$orden = $_GET['orden'] ?? 'fecha';

$ordenPermitido = ['fecha', 'nombre', 'matricula'];

if (!in_array($orden, $ordenPermitido, true)) {
    $orden = 'fecha';
}

$orderBy = 'id DESC';

if ($orden === 'nombre') {
    $orderBy = 'nombre ASC, apellido ASC';
} elseif ($orden === 'matricula') {
    $orderBy = 'matricula ASC';
}

$orden = $_GET['orden'] ?? 'fecha';

$ordenesPermitidos = [
    'nombre' => 'nombre ASC',
    'matricula' => 'matricula ASC',
    'fecha' => 'id DESC'
];

if (!isset($ordenesPermitidos[$orden])) {
    $orden = 'fecha';
}

$ordenSQL = $ordenesPermitidos[$orden];

$students = [];
$errorMessage = '';

try {

    // CONSULTAR ESTUDIANTES

    if ($q !== '') {

        $sql = "
            SELECT
                id,
                matricula,
                nombre,
                apellido,
                correo,
                carrera,
                created_at
            FROM estudiantes
            WHERE matricula LIKE ?
               OR nombre LIKE ?
               OR apellido LIKE ?
               OR correo LIKE ?
               OR carrera LIKE ?
            ORDER BY {$ordenSQL}
        ";

        $stmt = $pdo->prepare($sql);

        $busqueda = '%' . $q . '%';

        $stmt->execute([
            $busqueda,
            $busqueda,
            $busqueda,
            $busqueda,
            $busqueda
        ]);

    } else {

        $sql = "
            SELECT
                id,
                matricula,
                nombre,
                apellido,
                correo,
                carrera,
                created_at
            FROM estudiantes
            ORDER BY $orderBy
        ";

        $stmt = $pdo->query($sql);
    }

    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {

    error_log($e->getMessage());

    $errorMessage = 'Error al consultar los estudiantes.';

}

// MENSAJES

$message = $_SESSION['flash'] ?? '';

unset($_SESSION['flash']);

require __DIR__ . '/header.php';

?>

<section class="hero">

    <div>

        <p class="eyebrow">PANEL DE ADMINISTRACIÓN</p>

        <h1>Estudiantes</h1>

        <p class="muted">
            Administra los registros académicos desde un solo lugar.
        </p>

    </div>

    <a class="button primary" href="create.php">
        + Registrar estudiante
    </a>

</section>

<?php if ($message): ?>

    <div class="alert success">
        <?= e($message) ?>
    </div>

<?php endif; ?>

<?php if ($errorMessage): ?>

    <div class="alert error">
        <?= e($errorMessage) ?>
    </div>

<?php endif; ?>

<section class="panel">

    <form class="search" method="get" action="index.php">
      <select name="orden" onchange="this.form.submit()">
    <option value="fecha" <?= $orden === 'fecha' ? 'selected' : '' ?>>
        Más recientes
    </option>
    <option value="nombre" <?= $orden === 'nombre' ? 'selected' : '' ?>>
        Nombre (A-Z)
    </option>
    <option value="matricula" <?= $orden === 'matricula' ? 'selected' : '' ?>>
        Matrícula
    </option>
</select>

        <input
            type="search"
            name="q"
            placeholder="Buscar por nombre, matrícula, correo o carrera..."
            value="<?= e($q) ?>"
        >

        <button class="button dark" type="submit">
            Buscar
        </button>

        <?php if ($q !== ''): ?>

            <a class="button light" href="index.php">
                Limpiar
            </a>

        <?php endif; ?>

    </form>

    <div class="table-wrap">

        <table>

            <thead>

                <tr>
                    <th>Matrícula</th>
                    <th>Estudiante</th>
                    <th>Correo</th>
                    <th>Carrera</th>
                    <th>Acciones</th>
                </tr>

            </thead>

            <tbody>

                <?php if (empty($students)): ?>

                    <tr>
                        <td colspan="5" class="empty">
                            No hay estudiantes para mostrar.
                        </td>
                    </tr>

                <?php else: ?>

                    <?php foreach ($students as $s): ?>

                        <tr>

                            <td>
                                <span class="tag">
                                    <?= e($s['matricula']) ?>
                                </span>
                            </td>

                            <td>
                                <strong>
                                    <?= e($s['nombre'] . ' ' . $s['apellido']) ?>
                                </strong>
                            </td>

                            <td>
                                <?= e($s['correo']) ?>
                            </td>

                            <td>
                                <?= e($s['carrera']) ?>
                            </td>

                            <td class="actions">

                                <a
                                    class="action edit"
                                    href="edit.php?id=<?= (int)$s['id'] ?>"
                                >
                                    Editar
                                </a>

                                <form
                                    method="post"
                                    action="delete.php"
                                    class="inline-form"
                                    onsubmit="return confirm('¿Seguro que deseas eliminar este estudiante?');"
                                >

                                    <input
                                        type="hidden"
                                        name="csrf_token"
                                        value="<?= e(csrf_token()) ?>"
                                    >

                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?= (int)$s['id'] ?>"
                                    >

                                    <button
                                        class="action delete"
                                        type="submit"
                                    >
                                        Eliminar
                                    </button>

                                </form>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

    <p class="table-note">
        <?= count($students) ?> registro(s) encontrado(s)
    </p>

</section>

<?php require __DIR__ . '/footer.php'; ?>
