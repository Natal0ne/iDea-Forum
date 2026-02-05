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

    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const fileList = document.getElementById('fileList');

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
});