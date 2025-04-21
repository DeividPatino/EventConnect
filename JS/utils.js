function isLoggedIn() {
    return !!localStorage.getItem('user');
  }
  
  function getCurrentUser() {
    return JSON.parse(localStorage.getItem('user'));
  }
  
  function logout() {
    localStorage.removeItem('user');
    window.location.href = 'login.html';
  }
  
  document.addEventListener("DOMContentLoaded", () => {
    const isHome = location.pathname.endsWith("index.html") || location.pathname === "/";
  
    if (isHome && isLoggedIn()) {
      const user = getCurrentUser();
  
      
      const headerText = document.querySelector(".landing-header p");
      if (headerText) {
        headerText.textContent = `Bienvenido, ${user.nombre}`;
      }
  
      
      const authButtons = document.getElementById("auth-buttons");
      if (authButtons) {
        authButtons.style.display = "none";
      }
  
      
      const logoutContainer = document.getElementById("logout-button");
      if (logoutContainer) {
        logoutContainer.style.display = "block";
      } else {
   
        const newContainer = document.createElement("div");
        newContainer.className = "landing-buttons";
        const logoutBtn = document.createElement("button");
        logoutBtn.textContent = "Cerrar sesión";
        logoutBtn.classList.add("btn", "btn-logout");
        logoutBtn.onclick = logout;
        newContainer.appendChild(logoutBtn);
        document.body.appendChild(newContainer);
      }
    }
  });
  