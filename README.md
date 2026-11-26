# ITLA CRUD Manager

Proyecto académico CRUD de estudiantes para Programación III (ITLA).

## Tecnologías
- PHP 8+
- MySQL 8+
- HTML, CSS y JavaScript
- PDO con consultas preparadas

## Funcionalidades
- Crear estudiantes.
- Listar y consultar estudiantes.
- Editar estudiantes.
- Eliminar estudiantes con confirmación.
- Buscar por matrícula, nombre, apellido o correo.
- Validación de campos, unicidad de matrícula/correo y token CSRF.

## Instalación local con XAMPP
1. Copia la carpeta `itla-crud-manager` dentro de `C:\xampp\htdocs\`.
2. Abre XAMPP e inicia Apache y MySQL.
3. Entra a `http://localhost/phpmyadmin`.
4. Importa `database/schema.sql`.
5. Si tu usuario o contraseña de MySQL son distintos, edita `config.php`.
6. Abre `http://localhost/itla-crud-manager/`.

## Git Flow solicitado por la práctica
La guía de ramas y Pull Requests está en `docs/GIT-FLOW.md`.
La práctica exige cinco ramas `feature/` o `hotfix/` y tres PR por cada una: hacia `dev`, `qa` y `main` (15 PR cerrados/merged en total).

## Nota
Este repositorio es un punto de partida funcional. Antes de entregarlo, ejecútalo en tu equipo, prueba las operaciones CRUD y verifica los PR desde tu cuenta pública de GitHub.
