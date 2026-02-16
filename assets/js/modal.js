document.addEventListener('DOMContentLoaded', () => {

    const navSignInBtn = document.getElementById('navSignInBtn');

    const signInBtn = document.getElementById('signInBtn');
    const signInCloseBtn = document.getElementById('signInCloseBtn');
    const signInOverlay = document.getElementById('signInModal');
    const signInErrorMsg = document.getElementById('signInErrorMsg');

    const signUpBtn = document.getElementById('signUpBtn');
    const signUpCloseBtn = document.getElementById('signUpCloseBtn');
    const signUpOverlay = document.getElementById('signUpModal');
    const signUpErrorMsg = document.getElementById('signUpErrorMsg');

    const newThreadBtn = document.getElementById('newThreadBtn');
    const newThreadCloseBtn = document.getElementById('newThreadCloseBtn');
    const newThreadOverlay = document.getElementById('newThreadModal');
    const newThreadErrorMsg = document.getElementById('newThreadErrorMsg');

    const profileSettingsBtn = document.getElementById('profileSettingsBtn')
    const profileSettingsCloseBtn = document.getElementById('profileSettingsCloseBtn');
    const profileSettingsOverlay = document.getElementById('profileSettingsModal');
    const profileSettingsErrorMsg = document.getElementById('profileSettingsErrorMsg');

    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const fileList = document.getElementById('fileList');


    const signinButtons = document.querySelectorAll('.postSignInBtn'); // quelli che ti escono al posto di reply se non sei loggato


    signinButtons.forEach(button => {
        // CORREZIONE 2: Aggiunto 'e' tra le parentesi tonde
        button.addEventListener('click', function (e) {
            e.preventDefault();

            // Assicurati che signInOverlay sia stato definito prima nel codice!
            // const signInOverlay = document.getElementById('tuo_id_overlay');
            signInOverlay.classList.remove('hidden');
        });
    });




    // Apre il modale SIGN IN da navbar
    if (navSignInBtn) {
        navSignInBtn.addEventListener('click', (e) => {
            e.preventDefault();
            signInOverlay.classList.remove('hidden');
        });
    }

    // Apre il modale SIGN IN
    signInBtn.addEventListener('click', (e) => {
        e.preventDefault(); // Impedisce al link di navigare
        signInOverlay.classList.remove('hidden');
        signUpErrorMsg.classList.add('hidden'); // toglie la scritta di errore alla prossima apertura del modale
        signUpOverlay.classList.add('hidden');
    });

    // Chiude il modale SIGN IN
    signInCloseBtn.addEventListener('click', () => {
        signInOverlay.classList.add('hidden');
        signInErrorMsg.classList.add('hidden'); // toglie la scritta di errore alla prossima apertura del modale
    });

    // Apre il modale SIGN UP
    signUpBtn.addEventListener('click', (e) => {
        console.log('cliccato su registrati');
        e.preventDefault();
        signInOverlay.classList.add('hidden');
        signInErrorMsg.classList.add('hidden'); // toglie la scritta di errore alla prossima apertura del modale
        signUpOverlay.classList.remove('hidden');
    });

    // Chiude il modale SIGN UP
    signUpCloseBtn.addEventListener('click', () => {
        signUpOverlay.classList.add('hidden');
        signUpErrorMsg.classList.add('hidden'); // toglie la scritta di errore alla prossima apertura del modale
    });

    // Apre il modale NEW THREAD
    if (newThreadBtn) {
        newThreadBtn.addEventListener('click', (e) => {
            e.preventDefault();
            newThreadOverlay.classList.remove('hidden');
        });
    }

    // Chiude il modale NEW THREAD
    newThreadCloseBtn.addEventListener('click', () => {
        newThreadOverlay.classList.add('hidden');
        newThreadErrorMsg.classList.add('hidden');
        fileList.innerHTML = '';
        fileInput.value = '';
    });

    // Cambio css quando draggo sulla dropZone
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('drop-zone--over');
    });

    // Quando il file esce dalla dropZone o viene rilasciato
    ['dragleave', 'dragend'].forEach(type => {
        dropZone.addEventListener(type, () => {
            dropZone.classList.remove('drop-zone--over');
        });
    });


    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('drop-zone--over');

        if (e.dataTransfer.files.length) {
            fileInput.files = e.dataTransfer.files;
            updateThumbnailList(e.dataTransfer.files);
        }
    });

    fileInput.addEventListener('change', () => {
        if (fileInput.files.length) {
            updateThumbnailList(fileInput.files);
        }
    });

    function updateThumbnailList(files) {
        fileList.innerHTML = ""; // Pulisce la lista precedente

        Array.from(files).forEach(file => {
            // Controllo che il file sia un'immagine
            if (file.type.startsWith('image/')) {
                const container = document.createElement('div');
                container.className = 'thumbnail-container';

                const img = document.createElement('img');

                // Creo un url temporaneo
                // crea una stringa tipo "blob:https://sito/..."
                img.src = URL.createObjectURL(file);

                // Per ottimizzare la memoria libero l'url appena viene caricata l'immagine
                img.onload = () => {
                    URL.revokeObjectURL(img.src);
                };

                container.appendChild(img);
                fileList.appendChild(container);
            } else {
                // Se non è un'immagine, mostra un'icona di file generica
                const placeholder = document.createElement('div');
                placeholder.className = 'thumbnail-container';
                placeholder.innerHTML = `<div style="display:flex; align-items:center; justify-content:center; height:100%; background:#eee; font-size:20px;">📄</div>`;
                fileList.appendChild(placeholder);
            }
        });
    }

    // APERTURA MODALE IMMAGINE A SCHERMO INTERO
    const imageModal = document.getElementById("imageModal");
    const modalImg = document.getElementById("fullImage");
    const closeBtn = document.querySelector(".image-close");

    // Trova tutte le immagini nei post
    const images = document.querySelectorAll('.post-image');

    images.forEach(img => {
        img.onclick = function () {
            imageModal.style.display = "block";
            modalImg.src = this.src; // Copia il percorso dell'immagine cliccata
        }
    });

    // Chiudi quando clicchi sulla X
    if (closeBtn) {
        closeBtn.onclick = function () {
            imageModal.style.display = "none";
        }
    }

    // Chiudi quando clicchi ovunque sullo sfondo scuro
    if (imageModal) {
        imageModal.onclick = function (e) {
            if (e.target !== modalImg) {
                imageModal.style.display = "none";
            }
        }
    }

    // Chiudi premendo il tasto ESC
    document.addEventListener('keydown', function (e) {
        if (e.key === "Escape") {
            imageModal.style.display = "none";
        }
    });

    
    // PROFILE SETTINGS MODAL 
    if(profileSettingsBtn){
        profileSettingsBtn.addEventListener('click', (e) => {
            e.preventDefault();
            profileSettingsOverlay.classList.remove('hidden');
        })
    }
        profileSettingsCloseBtn.addEventListener('click', (e) =>{
            profileSettingsOverlay.classList.add('hidden');
            profileSettingsErrorMsg.classList.add('hidden');
        })
     
});