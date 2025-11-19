document.addEventListener("DOMContentLoaded", function() {// attend que le html soit completement charger/ pres a l'emploie
    const input = document.getElementById('psw');
    const iconSpan = document.getElementById('toggleIcon');
  
    iconSpan.addEventListener('click', function() {
      if (input.type === 'password') {
        input.type = 'text'; // Montre le mot de passe
        // Change l'icône en œil ouvert
        iconSpan.innerHTML = `
          <svg width="24" height="24" viewBox="0 0 64 64">
            <path d="M2,32 C12,12 52,12 62,32 C52,52 12,52 2,32 Z" stroke="black" stroke-width="2" fill="none"/>
            <circle cx="32" cy="32" r="6" fill="black"/>
          </svg>
        `;
      } else {
        input.type = 'password'; // Cache le mot de passe
        // Change l'icône en œil barré
        iconSpan.innerHTML = `
          <svg width="24" height="24" viewBox="0 0 64 64">
            <path d="M2,32 C12,12 52,12 62,32 C52,52 12,52 2,32 Z" stroke="black" stroke-width="2" fill="none"/>
            <circle cx="32" cy="32" r="6" fill="black"/>
            <line x1="8" y1="8" x2="56" y2="56" stroke="red" stroke-width="3"/>
          </svg>
        `;
      }
    });
  });
  