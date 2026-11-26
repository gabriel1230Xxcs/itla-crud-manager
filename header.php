<?php require_once __DIR__ . '/functions.php'; ?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($pageTitle ?? 'ITLA CRUD Manager') ?></title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="topbar">
  <a class="brand" href="index.php">ITLA <span>CRUD Manager</span></a>
  <nav><a href="index.php">Estudiantes</a><a class="nav-button" href="create.php">+ Nuevo estudiante</a></nav>
</header>
<main class="container">
