window.addEventListener("DOMContentLoaded", () => {

    const welcome = document.querySelector('.welcome');
    const content = document.querySelector('.content');
    const hasSeenIntro = localStorage.getItem('hasSeenIntro');

    if (!hasSeenIntro) {

        localStorage.setItem('hasSeenIntro', 'true');

        welcome.classList.add('welcome-in');
        welcome.classList.remove('hidden');

        // setta il body non scrollabile per non rovinare l'animazione
        document.body.style.overflow = 'hidden';

        setTimeout(() => {
            welcome.classList.replace('welcome-in', 'welcome-out');
            content.classList.add('content-in');
        }, 2000);

        setTimeout(() => {
            welcome.classList.add('hidden');
            content.classList.remove('hidden');
        }, 2500);

        // alla fine dell'animazione riabilita lo scrolling verticale
        setTimeout(() => {
            document.body.style.overflow = "";
        }, 4500);

    } else {
        content.classList.remove('hidden');
    }
});
