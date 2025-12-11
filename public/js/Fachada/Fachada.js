document.addEventListener("DOMContentLoaded", () => {
    const botones = document.querySelectorAll(".ver-detalle");

    botones.forEach(boton => {
        boton.addEventListener("click", function () {

            // Mensaje fijo
            const mensaje = "Debes iniciar sesión para visualizar este producto.";

            document.getElementById("modalMensaje").textContent = mensaje;

            // Mostrar el modal
            const modal = new bootstrap.Modal(document.getElementById("detalleModal"));
            modal.show();
        });
    });
});
