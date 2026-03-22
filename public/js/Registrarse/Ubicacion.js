const COLOMBIA = {
    "Amazonas": ["Leticia", "Puerto Nariño"],
    "Antioquia": ["Medellín", "Bello", "Itagüí", "Envigado", "Apartadó", "Turbo", "Rionegro", "Caucasia", "Sabaneta", "La Estrella"],
    "Arauca": ["Arauca", "Saravena", "Tame", "Fortul"],
    "Atlántico": ["Barranquilla", "Soledad", "Malambo", "Sabanalarga", "Baranoa"],
    "Bolívar": ["Cartagena", "Magangué", "Turbaco", "El Carmen de Bolívar"],
    "Boyacá": ["Tunja", "Duitama", "Sogamoso", "Chiquinquirá", "Paipa"],
    "Caldas": ["Manizales", "Villamaría", "La Dorada", "Riosucio", "Chinchiná"],
    "Caquetá": ["Florencia", "San Vicente del Caguán", "Puerto Rico"],
    "Casanare": ["Yopal", "Aguazul", "Villanueva", "Tauramena"],
    "Cauca": ["Popayán", "Santander de Quilichao", "Puerto Tejada", "Patía"],
    "Cesar": ["Valledupar", "Aguachica", "Bosconia", "La Jagua de Ibirico"],
    "Chocó": ["Quibdó", "Istmina", "Condoto"],
    "Córdoba": ["Montería", "Lorica", "Sahagún", "Cereté", "Montelíbano"],
    "Cundinamarca": ["Bogotá D.C.", "Soacha", "Facatativá", "Zipaquirá", "Chía", "Mosquera", "Madrid", "Fusagasugá", "Girardot"],
    "Guainía": ["Inírida"],
    "Guaviare": ["San José del Guaviare", "El Retorno"],
    "Huila": ["Neiva", "Pitalito", "Garzón", "La Plata"],
    "La Guajira": ["Riohacha", "Maicao", "Uribia", "Manaure"],
    "Magdalena": ["Santa Marta", "Ciénaga", "Fundación", "El Banco"],
    "Meta": ["Villavicencio", "Acacías", "Granada", "Puerto López"],
    "Nariño": ["Pasto", "Tumaco", "Ipiales", "Túquerres"],
    "Norte de Santander": ["Cúcuta", "Ocaña", "Pamplona", "Villa del Rosario", "Los Patios"],
    "Putumayo": ["Mocoa", "Puerto Asís", "Orito", "Valle del Guamuez"],
    "Quindío": ["Armenia", "Calarcá", "Montenegro", "Quimbaya"],
    "Risaralda": ["Pereira", "Dosquebradas", "Santa Rosa de Cabal", "La Virginia"],
    "San Andrés y Providencia": ["San Andrés", "Providencia"],
    "Santander": ["Bucaramanga", "Floridablanca", "Girón", "Piedecuesta", "Barrancabermeja"],
    "Sucre": ["Sincelejo", "Corozal", "Sampués", "San Marcos"],
    "Tolima": ["Ibagué", "Espinal", "Melgar", "Honda", "Chaparral"],
    "Valle del Cauca": ["Cali", "Buenaventura", "Palmira", "Tuluá", "Buga", "Cartago", "Yumbo", "Jamundí"],
    "Vaupés": ["Mitú"],
    "Vichada": ["Puerto Carreño"]
};

function initSelectUbicacion(deptoActual = null, municipioActual = null) {
    const selectDepto     = document.getElementById('selectDepartamento');
    const selectMunicipio = document.getElementById('selectMunicipio');
    if (!selectDepto || !selectMunicipio) return;

    // Llenar departamentos ordenados
    Object.keys(COLOMBIA).sort().forEach(depto => {
        const opt = document.createElement('option');
        opt.value = depto;
        opt.textContent = depto;
        if (depto === deptoActual) opt.selected = true;
        selectDepto.appendChild(opt);
    });

    // Si hay depto preseleccionado, cargar sus municipios
    if (deptoActual && COLOMBIA[deptoActual]) {
        cargarMunicipios(deptoActual, municipioActual);
    }

    // Al cambiar departamento, recargar municipios
    selectDepto.addEventListener('change', function () {
        cargarMunicipios(this.value, null);
    });
}

function cargarMunicipios(departamento, municipioActual = null) {
    const selectMunicipio = document.getElementById('selectMunicipio');
    selectMunicipio.innerHTML = '<option value="">-- Selecciona un municipio --</option>';

    if (!departamento || !COLOMBIA[departamento]) return;

    COLOMBIA[departamento].forEach(municipio => {
        const opt = document.createElement('option');
        opt.value = municipio;
        opt.textContent = municipio;
        if (municipio === municipioActual) opt.selected = true;
        selectMunicipio.appendChild(opt);
    });
}

function inicializarSelectsAdmin(idDepto, idMunicipio, deptoActual = null, municipioActual = null) {
    const selectDepto     = document.getElementById(idDepto);
    const selectMunicipio = document.getElementById(idMunicipio);
    if (!selectDepto || !selectMunicipio) return;

    selectDepto.innerHTML = '<option value="">-- Selecciona un departamento --</option>';
    selectMunicipio.innerHTML = '<option value="">-- Selecciona un municipio --</option>';

    Object.keys(COLOMBIA).sort().forEach(depto => {
        const opt = document.createElement('option');
        opt.value = depto;
        opt.textContent = depto;
        if (depto === deptoActual) opt.selected = true;
        selectDepto.appendChild(opt);
    });

    if (deptoActual && COLOMBIA[deptoActual]) {
        COLOMBIA[deptoActual].forEach(municipio => {
            const opt = document.createElement('option');
            opt.value = municipio;
            opt.textContent = municipio;
            if (municipio === municipioActual) opt.selected = true;
            selectMunicipio.appendChild(opt);
        });
    }

    selectDepto.addEventListener('change', function () {
        selectMunicipio.innerHTML = '<option value="">-- Selecciona un municipio --</option>';
        if (!this.value || !COLOMBIA[this.value]) return;
        COLOMBIA[this.value].forEach(municipio => {
            const opt = document.createElement('option');
            opt.value = municipio;
            opt.textContent = municipio;
            selectMunicipio.appendChild(opt);
        });
    });
}