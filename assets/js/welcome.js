window.addEventListener('load', () => {

    const welcome = document.querySelector('.welcome');
    const content = document.querySelector('.content');

    welcome.classList.add('welcome-in');

    setTimeout(() => {
        welcome.classList.replace('welcome-in', 'welcome-out');
        content.classList.add('content-in');
    }, 2000);

    setTimeout(() => {
        welcome.classList.add('hidden');
        content.classList.remove('hidden');
    }, 2500);
});