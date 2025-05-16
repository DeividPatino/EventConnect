
document.addEventListener("DOMContentLoaded", () => {
    const usuario = JSON.parse(localStorage.getItem("usuario"));
    const crearEvento = document.getElementById("crear-evento");

    // Oculta el botón por defecto
    crearEvento.style.display = "none";

    // Si existe sesión y es proveedor, lo muestra
    if (usuario && usuario.tipo === "proveedor") {
        crearEvento.style.display = "inline-block";
    }
});