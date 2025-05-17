document.addEventListener("DOMContentLoaded", () => {
  const usuario = JSON.parse(localStorage.getItem("usuario"));
  const crearEvento = document.getElementById("crear-evento");

  // Si no hay usuario o no es proveedor, ocultamos el enlace
  if (!usuario || usuario.tipo !== "proveedor") {
    crearEvento.style.display = "none";
  }
});
