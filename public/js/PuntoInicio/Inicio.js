document.addEventListener('DOMContentLoaded', () => {

    const modalImagen = document.getElementById('modalImagen');
    const modalTalla = document.getElementById('modalTalla');
    const modalColor = document.getElementById('modalColor');
    const modalPrecio = document.getElementById('modalPrecio');
    const btnAgregarCarrito = document.getElementById('btnAgregarCarrito');

    let idDetalleProducto = null;

    document.querySelectorAll('.ver-detalle').forEach(boton => {

        boton.addEventListener('click', () => {

            const imagen = boton.dataset.imagen;
            const talla = boton.dataset.talla;
            const color = boton.dataset.color;
            const precio = boton.dataset.precio;
            idDetalleProducto = boton.dataset.id;

            modalImagen.src = imagen;
            modalTalla.textContent = talla;
            modalColor.textContent = color;
            modalPrecio.textContent = precio;

        });

    });

    btnAgregarCarrito.addEventListener('click', () => {

        if (!idDetalleProducto) {
            alert('Error al agregar el producto');
            return;
        }

        console.log('Producto agregado al carrito:', idDetalleProducto);

        // AQUÍ más adelante hacemos el fetch al backend 🚀
        // fetch('/carrito/agregar', {...})

        alert('Producto agregado al carrito 🛒');

    });

});
