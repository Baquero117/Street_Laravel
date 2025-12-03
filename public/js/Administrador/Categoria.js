function mostrarSeccion(seccion) {
    document.querySelectorAll('.seccion').forEach(div => div.classList.add('d-none'));
    document.getElementById('seccion-' + seccion).classList.remove('d-none');

    document.querySelectorAll('.sidebar .btn').forEach(btn => btn.classList.remove('active'));
    document.querySelector(`.sidebar .btn[onclick*="${seccion}"]`).classList.add('active');
}
