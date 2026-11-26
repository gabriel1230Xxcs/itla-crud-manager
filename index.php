<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

$pageTitle = 'Estudiantes | ITLA CRUD Manager';
$q = trim($_GET['q'] ?? '');

if ($q !== '') {
    $stmt = $pdo->prepare(
        'SELECT id, matricula, nombre, apellido, correo, carrera, created_at
         FROM estudiantes
         WHERE matricula LIKE :q OR nombre LIKE :q OR apellido LIKE :q OR correo LIKE :q
         ORDER BY id DESC'
    );
    $stmt->execute(['q' => "%{$q}%"]);
} else {
    $stmt = $pdo->query(
        'SELECT id, matricula, nombre, apellido, correo, carrera, created_at
         FROM estudiantes ORDER BY id DESC'
    );
}
$students = $stmt->fetchAll();
$message = $_SESSION['flash'] ?? '';
unset($_SESSION['flash']);
require __DIR__ . '/header.php';
?>
<section class="hero">
  <div><p class="eyebrow">PANEL DE ADMINISTRACIÓN</p><h1>Estudiantes</h1><p class="muted">Administra los registros académicos desde un solo lugar.</p></div>
  <a class="button primary" href="create.php">+ Registrar estudiante</a>
</section>
<?php if ($message): ?><div class="alert success"><?= e($message) ?></div><?php endif; ?>
<section class="panel">
  <form class="search" method="get">
    <input type="search" name="q" placeholder="Buscar por nombre, matrícula o correo..." value="<?= e($q) ?>">
    <button class="button dark" type="submit">Buscar</button>
    <?php if ($q !== ''): ?><a class="button light" href="index.php">Limpiar</a><?php endif; ?>
  </form>
  <div class="table-wrap">
    <table>
      <thead><tr><th>Matrícula</th><th>Estudiante</th><th>Correo</th><th>Carrera</th><th>Acciones</th></tr></thead>
      <tbody>
      <?php if (!$students): ?>
        <tr><td colspan="5" class="empty">No hay estudiantes para mostrar.</td></tr>
      <?php else: foreach ($students as $s): ?>
        <tr>
          <td><span class="tag"><?= e($s['matricula']) ?></span></td>
          <td><strong><?= e($s['nombre'] . ' ' . $s['apellido']) ?></strong></td>
          <td><?= e($s['correo']) ?></td>
          <td><?= e($s['carrera']) ?></td>
          <td class="actions">
            <a class="action edit" href="edit.php?id=<?= (int)$s['id'] ?>">Editar</a>
            <form method="post" action="delete.php" class="inline-form" onsubmit="return confirm('¿Seguro que deseas eliminar este estudiante?');">
              <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
              <input type="hidden" name="id" value="<?= (int)$s['id'] ?>">
              <button class="action delete" type="submit">Eliminar</button>
            </form>
          </td>
        </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
  <p class="table-note"><?= count($students) ?> registro(s) encontrado(s)</p>
</section>
<?php require __DIR__ . '/footer.php'; ?>
