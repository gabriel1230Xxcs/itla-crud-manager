# Plan Git Flow y 15 Pull Requests

## Ramas permanentes
- `main`: versión final entregable.
- `dev`: integración de desarrollo.
- `qa`: integración para pruebas.

## Cinco ramas de trabajo
1. `feature/student-create`
2. `feature/student-read`
3. `feature/student-update`
4. `feature/student-delete`
5. `feature/student-search`

Cada rama debe contener un cambio propio y documentado. En este proyecto, agrega o actualiza el archivo correspondiente dentro de `docs/features/` con la evidencia de la funcionalidad que trabajaste.

## Matriz obligatoria de PR

| Rama fuente | PR destino 1 | PR destino 2 | PR destino 3 |
|---|---|---|---|
| feature/student-create | dev | qa | main |
| feature/student-read | dev | qa | main |
| feature/student-update | dev | qa | main |
| feature/student-delete | dev | qa | main |
| feature/student-search | dev | qa | main |
| **Total** | **5** | **5** | **5** |

## Orden recomendado
Para cada rama, crea primero el PR hacia `dev`, luego hacia `qa` y finalmente hacia `main`. Fusiona cada PR antes de continuar. Comprueba que los 15 estén en estado `Merged` o `Closed` y que las cinco contribuciones estén integradas en `main`.

## Comandos iniciales
```bash
git init
git add .
git commit -m "chore: initial CRUD project"
git branch -M main
git remote add origin https://github.com/TU-USUARIO/itla-crud-manager.git
git push -u origin main

git switch -c dev
git push -u origin dev
git switch -c qa
git push -u origin qa
git switch main
```

## Ejemplo para una rama
```bash
git switch main
git switch -c feature/student-create
# Realiza el cambio y crea/actualiza su archivo de evidencia
git add .
git commit -m "feat: document student creation feature"
git push -u origin feature/student-create
```
En GitHub, abre tres Pull Requests usando como base `dev`, `qa` y `main`, respectivamente. Fusiona los PR en ese orden. Repite el procedimiento para las otras cuatro ramas.

## Verificación
```bash
git branch -a
git log --oneline --graph --decorate --all
```
En GitHub, revisa la pestaña **Pull requests** y confirma que haya 15 PR cerrados/merged. Comprueba también que los cinco archivos de `docs/features/` aparezcan en `main`.
