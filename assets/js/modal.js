document.addEventListener('DOMContentLoaded', () => {
    const loginBtn = document.getElementById('loginBtn');
    const closeBtn = document.getElementById('closeBtn');
    const loginOverlay = document.getElementById('loginModal');

    // Apre il div
    loginBtn.addEventListener('click', (e) => {
        e.preventDefault(); // Impedisce al link di navigare
        loginOverlay.classList.remove('hidden');
    });

    // Chiude il div
    closeBtn.addEventListener('click', () => {
        loginOverlay.classList.add('hidden');
    });
});