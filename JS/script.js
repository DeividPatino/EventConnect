 document.querySelectorAll('.category').forEach(cat => {
    cat.addEventListener('click', function () {
      const categoria = this.getAttribute('data-filter');
      const params = new URLSearchParams(window.location.search);
      params.set('categoria', categoria);
      window.location.search = params.toString();
    });
  });

  document.getElementById('city').addEventListener('change', function () {
    const ciudad = this.value;
    window.location.href = "?ciudad=" + ciudad;
  });

  function calcularLevenshtein(a, b) {
  const matriz = Array.from({ length: a.length + 1 }, (_, i) =>
    Array(b.length + 1).fill(0)
  );

  for (let i = 0; i <= a.length; i++) matriz[i][0] = i;
  for (let j = 0; j <= b.length; j++) matriz[0][j] = j;

  for (let i = 1; i <= a.length; i++) {
    for (let j = 1; j <= b.length; j++) {
      const costo = a[i - 1] === b[j - 1] ? 0 : 1;
      matriz[i][j] = Math.min(
        matriz[i - 1][j] + 1,
        matriz[i][j - 1] + 1,
        matriz[i - 1][j - 1] + costo
      );
    }
  }

  return matriz[a.length][b.length];
}

const inputBusqueda = document.getElementById('busqueda-evento');

inputBusqueda.addEventListener('input', function () {
  const busqueda = this.value.toLowerCase().trim();
  const eventos = document.querySelectorAll('.evento-item');

  eventos.forEach(evento => {
    const nombre = evento.querySelector('.card-title').textContent.toLowerCase();
    const distancia = calcularLevenshtein(busqueda, nombre);

    const esSimilar = nombre.includes(busqueda) || distancia <= 2;
    evento.style.display = (busqueda === '' || esSimilar) ? '' : 'none';
  });
});

inputBusqueda.addEventListener('keydown', function (e) {
  if (e.key === 'Enter') {
    e.preventDefault();
    this.dispatchEvent(new Event('input')); // Dispara el filtro
  }
});
