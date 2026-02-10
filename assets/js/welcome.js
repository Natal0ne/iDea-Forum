window.addEventListener("DOMContentLoaded", () => {

    const welcome = document.querySelector('.welcome');
    const content = document.querySelector('.content');
    const footer = document.querySelector('.footer-container');

    // serve a mostrare il footer in tutte le pagine diverse da index che non hanno il div .welcome
    if (!welcome) {
        footer.classList.remove('hidden');
    }
    else if (welcome) {
        if (!welcome.classList.contains('hidden')) {

            welcome.classList.add('welcome-in');
            content.classList.add('content-in');
            footer.classList.add('footer-in');
            welcome.classList.remove('hidden');

            // setta il body non scrollabile per non rovinare l'animazione
            document.body.style.overflow = 'hidden';

            setTimeout(() => {
                welcome.classList.replace('welcome-in', 'welcome-out');
            }, 2000);

            setTimeout(() => {
                welcome.classList.add('hidden');
                content.classList.remove('hidden');
                footer.classList.remove('hidden');
            }, 2500);

            // alla fine dell'animazione riabilita lo scrolling verticale
            setTimeout(() => {
                document.body.style.overflow = "";
            }, 5000);

        } else {
            content.classList.remove('hidden');
            footer.classList.remove('hidden');
        }
    }

});
