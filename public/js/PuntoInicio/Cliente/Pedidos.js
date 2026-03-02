const dropdownUsuario = document.querySelector('.dropdown');
const dropdownMenu = dropdownUsuario?.querySelector('.dropdown-menu');
if (dropdownUsuario && dropdownMenu) {
    dropdownUsuario.addEventListener('mouseenter', () => dropdownMenu.classList.add('show'));
    dropdownUsuario.addEventListener('mouseleave', (e) => {
        if (!dropdownMenu.contains(e.relatedTarget)) dropdownMenu.classList.remove('show');
    });
    dropdownMenu.addEventListener('mouseleave', (e) => {
        if (!dropdownUsuario.contains(e.relatedTarget)) dropdownMenu.classList.remove('show');
    });
}