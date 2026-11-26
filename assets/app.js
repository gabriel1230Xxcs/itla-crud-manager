// Confirmaciones y pequeñas mejoras de interacción.
document.querySelectorAll('form[onsubmit]').forEach((form) => {
  form.addEventListener('submit', (event) => {
    if (!confirm('¿Deseas continuar con esta acción?')) event.preventDefault();
  });
});
