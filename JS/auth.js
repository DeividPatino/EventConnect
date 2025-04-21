
document.addEventListener('DOMContentLoaded', () => {
    const registerForm = document.querySelector('.form-registro');
    if (registerForm) {
      registerForm.addEventListener('submit', function (e) {
        e.preventDefault();
  
        const nombre = this.ur.value;
        const apellido = this.ar.value;
        const correo = this.cor.value;
        const contraseña = this.cont.value;
  
        
        localStorage.setItem('user', JSON.stringify({
          nombre,
          apellido,
          correo,
          contraseña
        }));
  
        alert('¡Registro exitoso! Ahora puedes iniciar sesión.');
  
        
        window.location.href = "login.html";
      });
    }
  });

document.addEventListener('DOMContentLoaded', () => {
  const loginForm = document.querySelector('.form-login');
  if (loginForm) {
    loginForm.addEventListener('submit', function (e) {
      e.preventDefault();

      const correoIngresado = this.u.value;
      const contraseñaIngresada = this.c.value;

      const usuarioRegistrado = JSON.parse(localStorage.getItem('user'));

      if (
        usuarioRegistrado &&
        usuarioRegistrado.correo === correoIngresado &&
        usuarioRegistrado.contraseña === contraseñaIngresada
      ) {
        alert(`¡Bienvenido ${usuarioRegistrado.nombre}!`);
        window.location.href = "index.html"; 
      } else {
        alert('Correo o contraseña incorrectos.');
      }
    });
  }
});
  