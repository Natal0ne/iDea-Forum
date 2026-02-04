window.addEventListener('load', () => {

    const welcome = document.querySelector('.welcome');
    const content = document.querySelector('.content');

    welcome.classList.add('welcome-in');

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
});