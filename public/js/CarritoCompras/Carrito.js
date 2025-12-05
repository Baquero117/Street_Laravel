

document.addEventListener('DOMContentLoaded', () => {
    
    const botonesAgregar = document.querySelectorAll('.btn-agregar-carrusel');

    
    botonesAgregar.forEach(button => {
        
        
        const clickInicial = (event) => {
            
            const btn = event.currentTarget; 
            
            
            
            
            btn.textContent = 'Ver Carrito';
            btn.classList.add('btn-primary');     
            btn.classList.remove('btn-boton-pago'); 
            
            
            btn.removeEventListener('click', clickInicial);
            btn.addEventListener('click', clickVerCarrito);
        };
        
        
        const clickVerCarrito = () => { 
        button.addEventListener('click', clickInicial);
        }
    });
});



document.addEventListener('DOMContentLoaded', () => {
    

    const btnPago = document.getElementById('btnProcederPago');
    
    if (btnPago) {
        btnPago.addEventListener('click', () => {
            
           
            btnPago.disabled = true;
            
            
            btnPago.innerHTML = `
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Procesando...
            `;
            
           
            btnPago.classList.remove('btn-boton-pago');
            btnPago.classList.add('btn-secondary'); 
            
            
            
            setTimeout(() => {
                
                
                
                
                btnPago.innerHTML = '¡Pedido Confirmado!';
                
                
                btnPago.classList.remove('btn-secondary');
                btnPago.classList.add('btn-success');
                
                
                btnPago.disabled = true; 
                
            }, 2000); 
        });
    }
});


// ==== Abrir Modal ====
function abrirModal() {
  const modal = document.getElementById("modalMenu");
  modal.style.display = "block";
  setTimeout(() => {
    modal.classList.add("show");
  }, 10); 
}

// ==== Cerrar Modal ====
document.getElementById("cerrarMenu").addEventListener("click", () => {
  cerrarModal();
});

function cerrarModal() {
  const modal = document.getElementById("modalMenu");
  modal.classList.remove("show");
  setTimeout(() => {
    modal.style.display = "none";
  }, 400); 
}

// ==== Cerrar modal al hacer click fuera del contenido ====
window.addEventListener("click", (e) => {
  const modal = document.getElementById("modalMenu");
  if (e.target === modal) {
    cerrarModal();
  }
});








