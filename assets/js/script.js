// Seleziona elementi
const loginBtn = document.querySelector('.btn-login'); // Il tuo bottone nella navbar
const loginModal = document.getElementById('loginModal');
const registerModal = document.getElementById('registerModal');
const closeButtons = document.querySelectorAll('.close-btn');
const switchToRegister = document.getElementById('switchToRegister');
const switchToLogin = document.getElementById('switchToLogin');

// Funzione per aprire un modale
function openModal(modal) {
    modal.classList.add('active');
}

// Funzione per chiudere un modale
function closeModal(modal) {
    modal.classList.remove('active');
}

// 1. Apri Login cliccando il bottone della navbar
// IMPORTANTE: Assicurati che il tuo link HTML sia <a href="#" ...> e non vada a un'altra pagina
if (loginBtn) {
    loginBtn.addEventListener('click', (e) => {
        e.preventDefault(); // Evita che il link ricarichi la pagina
        openModal(loginModal);
    });
}

// 2. Chiudi cliccando sulla X
closeButtons.forEach(btn => {
    btn.addEventListener('click', () => {
        const modalId = btn.getAttribute('data-target');
        closeModal(document.getElementById(modalId));
    });
});

// 3. Chiudi cliccando fuori dal box (sullo sfondo scuro)
window.addEventListener('click', (e) => {
    if (e.target.classList.contains('modal-overlay')) {
        closeModal(e.target);
    }
});

// 4. Switch da Login a Register
switchToRegister.addEventListener('click', (e) => {
    e.preventDefault();
    closeModal(loginModal);
    openModal(registerModal);
});

// 5. Switch da Register a Login
switchToLogin.addEventListener('click', (e) => {
    e.preventDefault();
    closeModal(registerModal);
    openModal(loginModal);
});