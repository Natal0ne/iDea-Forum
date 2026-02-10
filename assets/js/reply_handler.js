document.addEventListener('DOMContentLoaded', function () {

    // Gestione tasti Reply (quelli sotto i post)
    const replyLinks = document.querySelectorAll('.reply-link');
    replyLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('data-target');
            const targetBox = document.getElementById(targetId);
            const username = this.getAttribute('data-username');

            if (targetBox) {
                // Mostra il box rimuovendo la classe hidden
                targetBox.classList.remove('hidden');

                // Aggiorna il nome dell'utente a cui si risponde
                const userSpan = targetBox.querySelector('.reply-target-user');
                if (userSpan && username) {
                    userSpan.innerText = '@' + username;
                }

                // Focus automatico sulla textarea
                const textarea = targetBox.querySelector('.reply-textarea');
                if (textarea) textarea.focus();

                // Scroll fluido verso il box aperto
                targetBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        });
    });

    // Gestione tasti Close (dentro il box di risposta)
    const closeButtons = document.querySelectorAll('.close-reply-btn');
    closeButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            // Risale al contenitore principale (.reply-form-container)
            const container = this.closest('.reply-form-container');

            if (container) {
                // Nasconde il box
                container.classList.add('hidden');

                // Svuota il buffer dei file (input file)
                const fileInput = container.querySelector('input[type="file"]');
                if (fileInput) {
                    fileInput.value = ""; // Questo cancella i file selezionati
                }

                // Svuota la lista delle anteprime (thumbnails)
                const fileList = container.querySelector('.reply-file-list');
                if (fileList) {
                    fileList.innerHTML = ""; // Cancella i quadratini delle immagini
                }

                // Svuota il testo scritto nella textarea
                const textarea = container.querySelector('.reply-textarea');
                if (textarea) {
                    textarea.value = "";
                }
            }
        });
    });

    //  GESTIONE DRAG & DROP E ANTEPRIME ---
    const replyBoxes = document.querySelectorAll('.reply-box');

    replyBoxes.forEach(box => {
        const dropZone = box.querySelector('.reply-drop-zone');
        const fileInput = box.querySelector('input[type="file"]');
        const fileList = box.querySelector('.reply-file-list');

        if (!dropZone || !fileInput) return;

        // Aggiorna css quando si trascina un file sopra
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('drop-zone--over');
        });

        ['dragleave', 'dragend'].forEach(type => {
            dropZone.addEventListener(type, () => {
                dropZone.classList.remove('drop-zone--over');
            });
        });

        // Quando il file viene rilasciato
        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('drop-zone--over');

            if (e.dataTransfer.files.length) {
                // Collega i file trascinati all'input file nascosto
                fileInput.files = e.dataTransfer.files;
                updateReplyThumbnails(fileInput.files, fileList);
            }
        });

        // Quando i file vengono selezionati normalmente (cliccando)
        fileInput.addEventListener('change', () => {
            if (fileInput.files.length) {
                updateReplyThumbnails(fileInput.files, fileList);
            }
        });
    });

    /* Funzione per generare le anteprime delle immagini selezionate, files (file scelti) e container (div dove mettere le anteprime) */
    function updateReplyThumbnails(files, container) {
        container.innerHTML = ""; // Svuota anteprime precedenti di questo specifico box

        Array.from(files).forEach(file => {
            const thumbContainer = document.createElement('div');
            thumbContainer.className = 'thumbnail-container';

            if (file.type.startsWith('image/')) {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);

                // Pulizia memoria quando l'immagine è caricata
                img.onload = () => URL.revokeObjectURL(img.src);

                thumbContainer.appendChild(img);
            } else {
                // Placeholder per file non immagine
                const icon = document.createElement('div');
                icon.className = 'file-icon-placeholder';
                icon.innerHTML = '📄';
                thumbContainer.appendChild(icon);
            }

            container.appendChild(thumbContainer);
        });
    }
});