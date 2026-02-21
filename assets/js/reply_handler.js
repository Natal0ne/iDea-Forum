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
    // Seleziona tutti i bottoni delete dei post
    document.querySelectorAll('.deletePostBtn').forEach(btn => {
      let confirmClick = false;
      let timeout;

      btn.addEventListener('click', function(e) {
          e.preventDefault();
          const postId = this.getAttribute('data-post-id');
          const attachmentsContainer = document.getElementById('attachments-' + postId);

          if (!confirmClick) {
              // Primo click
              this.textContent = 'Confirm?';
              this.style.backgroundColor = '#DC2626';
              this.style.border = '2px solid #DC2626';
              confirmClick = true;

              // Reset dopo 3 secondi
              timeout = setTimeout(() => {
                  this.innerHTML = '<span style="margin-right: 5px;">&#10006;</span>Delete';
                  this.style.backgroundColor = '#334E68';
                  this.style.border = '2px solid #334E68';
                  confirmClick = false;
              }, 3000);
          } else {
              // Secondo click
              clearTimeout(timeout);

              const formData = new FormData();
              formData.append('post_id', postId);

              fetch('includes/delete_post_process.php', {
                  method: 'POST',
                  body: formData
              })
              .then(response => response.json())
              .then(data => {
                if (data.success) {
                  // Nascondo contenuto e allegati senza ricaricare
                  document.getElementById('post-text-' + postId).innerHTML = '<p style="color: gray; font-style: italic;">[This post has been deleted by an administrator]</p>';
                  if (attachmentsContainer) attachmentsContainer.remove();

                  // Rimuovo i bottoni quando elimino il post
                  const buttonsDiv = this.closest('.post-buttons-div');
                  if(buttonsDiv) buttonsDiv.remove();

                  // Rimuovo un eventuale reply box aperta quando elimino il post
                  const replyBox = document.getElementById('reply-box-' + postId);
                  if(replyBox) replyBox.remove();

                  // Se era il post principale, modifico il titolo del thread (solo temporaneamtente)
                  if (data.is_op) {
                      const titleElement = document.getElementById('threadTitle');
                      if (titleElement) {
                          titleElement.style.color = 'gray';
                          // Aggiungiamo il tag se non è già presente
                          if (!titleElement.textContent.includes('[Deleted]')) {
                              titleElement.textContent += ' [Deleted by Admin]';
                          }
                      }
                  }
                } else {
                    alert('Error: ' + data.message);
                  }
              })
          }
      });
  });

  // Permette scroll orizzontale su attachments di view thread
  document.querySelectorAll('.attachments').forEach(container => {
      container.addEventListener('wheel', (e) => {

          // CONTROLLO TOUCHPAD:
          // Se l'evento ha una componente orizzontale (deltaX), significa che
          // l'utente sta usando un touchpad per scorrere lateralmente in modo naturale.
          // In questo caso, NON faccio nulla e lascio che si comporti nativamente.
          if (Math.abs(e.deltaX) > Math.abs(e.deltaY)) {
              return;
          }

          // GESTIONE MOUSE:
          // Se lo scroll è prevalentemente verticale (tipico della rotellina del mouse),
          // lo convertiamo in orizzontale.
          if (e.deltaY !== 0) {
              e.preventDefault();

              container.scrollLeft += e.deltaY;
          }
      }, { passive: false });
  });
});

// Funzione per i voti (upvote/downvote)
function handleVote(postId, voteValue) {
  fetch('includes/vote_process.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `post_id=${postId}&vote_value=${voteValue}`
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Aggiorna il testo del punteggio
        document.getElementById(`score-${postId}`).innerText = data.new_score;

        const container = document.querySelector(`.vote-controls-div[data-post-id="${postId}"]`);
        container.querySelector('.up').classList.toggle('active', data.user_vote === 1);
        container.querySelector('.down').classList.toggle('active', data.user_vote === -1);
      } else {
        alert(data.message);
      }
    });
}
