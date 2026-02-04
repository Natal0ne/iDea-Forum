document.addEventListener('DOMContentLoaded', () => {

    const navSignInBtn = document.getElementById('navSignInBtn');

    const signInBtn = document.getElementById('signInBtn');
    const signInCloseBtn = document.getElementById('signInCloseBtn');
    const signInOverlay = document.getElementById('signInModal');
    const signInErrorMsg = document.getElementById('signInErrorMsg');

    const signUpBtn = document.getElementById('signUpBtn');
    const signUpCloseBtn = document.getElementById('signUpCloseBtn');
    const signUpOverlay = document.getElementById('signUpModal');

    // Apre il modale SIGN IN da navbar
    navSignInBtn.addEventListener('click', (e) => {
        e.preventDefault();
        signInOverlay.classList.remove('hidden');
    });

    // Apre il modale SIGN IN
    signInBtn.addEventListener('click', (e) => {
        e.preventDefault(); // Impedisce al link di navigare
        signInOverlay.classList.remove('hidden');
        signUpOverlay.classList.add('hidden');
    });

    // Chiude il modale SIGN IN
    signInCloseBtn.addEventListener('click', () => {
        signInOverlay.classList.add('hidden');
        signInErrorMsg.classList.add('hidden'); // toglie la scritta di errore alla prossima apertura del modale
    });


    // Apre il modale SIGN UP
    signUpBtn.addEventListener('click', (e) => {
        e.preventDefault();
        signInOverlay.classList.add('hidden');
        signInErrorMsg.classList.add('hidden'); // toglie la scritta di errore alla prossima apertura del modale
        signUpOverlay.classList.remove('hidden');
    });

    // Chiude il modale SIGN UP
    signUpCloseBtn.addEventListener('click', () => {
        signUpOverlay.classList.add('hidden');
    });
});