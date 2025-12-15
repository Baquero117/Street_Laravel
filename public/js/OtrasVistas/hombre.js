document.addEventListener("DOMContentLoaded", () => {
  const botones = document.querySelectorAll(".ver-detalle");

  botones.forEach(boton => {
    boton.addEventListener("click", function () {
      // Extraer la info de los atributos data
      const nombre = this.getAttribute("data-nombre");
      const descripcion = this.getAttribute("data-descripcion");
      const categoria = this.getAttribute("data-categoria");
      const color = this.getAttribute("data-color");
      const talla = this.getAttribute("data-talla");

      // Pasar la info al modal
      document.getElementById("modalNombre").textContent = nombre;
      document.getElementById("modalDescripcion").textContent = descripcion;
      document.getElementById("modalCategoria").textContent = categoria;
      document.getElementById("modalColor").textContent = color;
      document.getElementById("modalTalla").textContent = talla;

      // Mostrar modal
      const modal = new bootstrap.Modal(document.getElementById("detalleModal"));
      modal.show();
    });
  });
});
