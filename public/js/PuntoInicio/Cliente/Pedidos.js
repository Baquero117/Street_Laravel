// ============================================================
//  Dropdown usuario — desktop Y móvil con contador
// ============================================================
function initDropdownUsuario() {
    const navbar = document.getElementById('mainNavbar');
    let abiertos = 0;

    function setupDropdown(toggleId, menuId) {
        const toggle = document.getElementById(toggleId);
        const menu   = document.getElementById(menuId);
        if (!toggle || !menu) return;

        let estaAbierto = false;

        toggle.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            estaAbierto ? cerrar() : abrir();
        });

        document.addEventListener('click', function (e) {
            if (estaAbierto && !toggle.contains(e.target) && !menu.contains(e.target)) cerrar();
        });

        function abrir() {
            if (estaAbierto) return;
            estaAbierto = true;
            abiertos++;
            menu.classList.add('show');
            toggle.setAttribute('aria-expanded', 'true');
            if (navbar) navbar.classList.add('dropdown-open');
        }

        function cerrar() {
            if (!estaAbierto) return;
            estaAbierto = false;
            abiertos = Math.max(0, abiertos - 1);
            menu.classList.remove('show');
            toggle.setAttribute('aria-expanded', 'false');
            if (abiertos === 0 && navbar) navbar.classList.remove('dropdown-open');
        }
    }

    setupDropdown('userDropdownToggle',       'userDropdownMenu');
    setupDropdown('userDropdownToggleMobile', 'userDropdownMenuMobile');
}

// ============================================================
//  Ver más / Ver menos pedidos
// ============================================================
let expandido = false;

function toggleVerMas() {
    const btn     = document.getElementById('btnVerMas');
    const ocultos = document.querySelectorAll('.pedido-oculto');
    const total   = ocultos.length;

    if (!expandido) {
        ocultos.forEach((card, i) => {
            card.classList.add('mostrando');
            card.style.animationDelay = `${i * 60}ms`;
        });
        btn.classList.add('expandido');
        btn.innerHTML = '<i class="bi bi-chevron-up me-1"></i> Ver menos';
        expandido = true;
    } else {
        ocultos.forEach(card => {
            card.classList.remove('mostrando');
            card.style.animationDelay = '';
        });
        btn.classList.remove('expandido');
        btn.innerHTML = `<i class="bi bi-chevron-down me-1"></i> Ver ${total} pedidos más`;
        expandido = false;
        document.getElementById('lista-pedidos')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

// ============================================================
//  DOMContentLoaded
// ============================================================
document.addEventListener('DOMContentLoaded', function () {
    initDropdownUsuario();
});