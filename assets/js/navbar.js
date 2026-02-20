/* Navbar scrolling animation */
window.addEventListener('scroll', function () {
  const navbar = document.getElementById('navbarWrapper');
  if (window.scrollY > 30) {
    navbar.classList.add('scrolled');
  } else {
    navbar.classList.remove('scrolled');
  }
});

document.addEventListener('DOMContentLoaded', () => {
  const userMenuBtn = document.getElementById('userMenuBtn');
  const dropdown = document.getElementById('userDropdown');
  let dropdownFlag = false;

  if(userMenuBtn) {
      userMenuBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        if(dropdownFlag == false) {
          dropdown.classList.remove('hidden');
          dropdownFlag = true;
        } else {
          dropdown.classList.add('hidden');
          dropdownFlag = false;
        }
      });

    document.addEventListener('click', function(e) {
        if(!dropdown.contains(e.target) && !userMenuBtn.contains(e.target)) {
          dropdown.classList.add('hidden');
        }
    });
  }

  // GESTIONE DI RICERCA DEI THREADS DEL SEARCHBAR
  const searchInput = document.getElementById('searchInput');
  const suggestions = document.getElementById('searchSuggestions');

  // Ogni volta che la barra di ricerca riceve un input (anche se cambi una lettera)
  searchInput.addEventListener('input', function() {
      // prende la stringa digitata eliminando eventuali spazi
      const query = this.value.trim();

      // se la query è troppo corta nasconde il dropdown e non fa nulla
      if(query.length < 2) {
          suggestions.classList.add('hidden');
          return;
      }

      // altrimenti chiama seach_api chiedendo i risultati del backend
      // encodeURIComponent serve ad evitare problemi con caratteri speciali nell'url
      fetch(`includes/search_api.php?q=${encodeURIComponent(query)}`)
      // prima di tutto riceve la risposta e la trasforma in JSON sfruttabile da js
      .then(response => response.json())
      // usa i dati ricevuti
      .then(data => {
          // svuota le suggestions
          suggestions.innerHTML = '';

          // se ci sono risultati
          if(data.length > 0) {
              data.forEach(thread => {
                  // crea un <a> per ogni thread trovato (sempre massimo 5)
                  const a = document.createElement('a');
                  a.href = `view_thread.php?slug=${thread.slug}`;
                  a.textContent = thread.title;
                  // aggiungi il a nel suggestions dropdown
                  suggestions.appendChild(a);
              });
          } else {
              // se data non maggiore di zero (array di risposta vuoto) stampa no threads fonund
              suggestions.innerHTML = '<div class="no-results">No threads found</div>';
          }
      // una volta trovati o non i risultati rimuovi hidden al dropdown menu
      suggestions.classList.remove('hidden');
      });
  });

  // VEDETE SE VOLETE IMPLEMENTARLO
  // chiudi il suggestion dropdown se clicchi fuori

  document.addEventListener('click', function(e) {
      if(!searchInput.contains(e.target) && !suggestions.contains(e.target)) {
          suggestions.classList.add('hidden');
      }
  });

});
