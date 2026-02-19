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
              if (userSpan && username) userSpan.innerText = '@' + username;

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
            // Risale al container principale
            const container = this.closest('.reply-form-container');

            if (container) {
              // Nasconde il box
              container.classList.add('hidden');

              // Svuota campi
              const textarea = container.querySelector('.textarea, .reply-textarea');
              if (textarea) textarea.value = "";

              // Svuota allegati (Specifico per questo box)
              const fileInput = container.querySelector('input[type="file"]');
              const fileList = container.querySelector('.reply-file-list');

              if (fileInput) {
                  fileInput.value = "";
                  fileInput._accumulatedFiles = []; // Svuota l'array file relativo al post
              }
              if (fileList) fileList.innerHTML = "";
            }
        });
    });

    // Gestione drag & drop e anteprime
    const replyBoxes = document.querySelectorAll('.reply-box');

    replyBoxes.forEach(box => {
        const dropZone = box.querySelector('.reply-drop-zone');
        const fileInput = box.querySelector('input[type="file"]');
        const fileList = box.querySelector('.reply-file-list');

        if (!dropZone || !fileInput) return;

        // Inizializziamo l'array accumulatore come proprietà dell'input
        fileInput._accumulatedFiles = [];

        // Funzione interna per gestire l'aggiunta
        const handleReplyFiles = (newFiles) => {
            // Accumula i file nell'array temporaneo
            const filesArray = Array.from(newFiles);
            fileInput._accumulatedFiles = fileInput._accumulatedFiles.concat(filesArray);

            // Sincronizza l'input HTML per il PHP usando DataTransfer
            const dt = new DataTransfer();
            fileInput._accumulatedFiles.forEach(file => dt.items.add(file));

            // --- CORREZIONE FONDAMENTALE ---
            // 1. Svuotiamo prima il valore testuale (permette di selezionare lo stesso file due volte)
            fileInput.value = "";
            // 2. Assegniamo ORA la lista completa (questo popola l'input per il PHP)
            fileInput.files = dt.files;
            // -------------------------------

            // Aggiorna le anteprime
            updateReplyThumbnails(fileInput._accumulatedFiles, fileList);
        };

        // Aggiorna css quando si trascina un file sopra la dropzone
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('drop-zone--over');
        });

        ['dragleave', 'dragend'].forEach(type => {
            dropZone.addEventListener(type, () => {
                dropZone.classList.remove('drop-zone--over');
            });
        });

        // Quando file viene rilasciato sulla dropzone
        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('drop-zone--over');
            if (e.dataTransfer.files && e.dataTransfer.files.length) {
                handleReplyFiles(e.dataTransfer.files);
            }
        });

        // Quando i file vengono selezionati normalmente (cliccando)
        fileInput.addEventListener('change', () => {
            if (fileInput.files && fileInput.files.length) {
                handleReplyFiles(fileInput.files);
            }
        });
    });

    // Funzione per generare le anteprime delle immagini selezionate
    function updateReplyThumbnails(filesArray, container) {
        container.innerHTML = ""; // Svuota anteprime precedenti di questo box

        filesArray.forEach((file, index) => {
            const thumbContainer = document.createElement('div');
            thumbContainer.className = 'thumbnail-container';
            thumbContainer.style.position = 'relative';

            if (file.type.startsWith('image/')) {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);

                // Pulizia memoria quando l'immagine viene caricata
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
