// Navbar Scroll
window.addEventListener('scroll', function () {
    const navbar = document.getElementById('mainNavbar');
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
});

// Search
const searchInput = document.querySelector('.search-input');
const searchSuggestions = document.getElementById('search-suggestions');
let debounceTimer;

if (searchInput && searchSuggestions) {

    // Ascolta ogni tasto premuto
    searchInput.addEventListener('input', function () {
        const query = this.value.trim();
        // Resetta il timer per evitare di fare una richiesta per ogni tasto premuto
        clearTimeout(debounceTimer);

        // Se la query ha meno di 2 caratteri, nasconde i suggerimenti
        if (query.length < 2) {
            searchSuggestions.style.display = 'none';
            searchSuggestions.innerHTML = '';
            return;
        }

        // Imposta un nuovo timer: esegue codice solo tra 300ms
        debounceTimer = setTimeout(() => {
            fetch('includes/search_handler.php?q=' + encodeURIComponent(query))
                .then(response => response.json()) // trasforma la risposta in JSON
                .then(data => {
                    searchSuggestions.innerHTML = ''; // svuota i vecchi risultati

                    if (data.length > 0) {

                        data.forEach(thread => {
                            const link = document.createElement('a');
                            link.href = 'thread.php?id=' + thread.id;
                            link.textContent = thread.title;
                            link.className = 'suggestions-item';
                            searchSuggestions.appendChild(link);
                        });
                        searchSuggestions.style.display = 'block';
                    } else {
                        // Nessun risultato
                        const div = document.createElement('div');
                        div.textContent = 'No discussions found';
                        div.style.padding = '10px';
                        div.style.color = '#666';
                        searchSuggestions.appendChild(div);
                        searchSuggestions.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error fetching search results:', error);
                });
        }, 300); // 300ms di debounce
    });

    // Gestione tasto invio
    searchInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter' && this.value.trim().length > 0) {
            window.location.href = 'search.php?q=' + encodeURIComponent(this.value.trim());
        }
    });

    // Chiudi suggerimento click esterno
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.search-container')) {
            if (searchSuggestions) searchSuggestions.style.display = 'none';
        }

        // Chiudi dropdown utente
        const avatarBtn = document.getElementById('avatarBtn');
        const userDropdown = document.getElementById('userDropdown');
        if (avatarBtn && userDropdown) {
            if (avatarBtn.contains(e.target)) {
                userDropdown.classList.toggle('active');
            } else if (!userDropdown.contains(e.target)) {
                userDropdown.classList.remove('active');
            }
        }
    });

    // 4. Create Thread Modal Trigger
    const createBtn = document.getElementById('createThreadBtn');
    if (createBtn) {
        createBtn.addEventListener('click', function (e) {
            e.preventDefault();
            const modal = document.getElementById('createThreadModal');
            if (modal) {
                modal.classList.add('active');
            }
        });
    }

    // Generic Modal Close
    document.querySelectorAll('.close-btn, .modal-overlay').forEach(el => {
        el.addEventListener('click', function () {
            const target = this.getAttribute('data-target');
            if (target) {
                document.getElementById(target).classList.remove('active');
            } else {
                this.closest('.modal').classList.remove('active');
            }
        });
    });
}