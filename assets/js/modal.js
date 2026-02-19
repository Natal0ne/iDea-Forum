document.addEventListener("DOMContentLoaded", () => {
  const navSignInBtn = document.getElementById("navSignInBtn");

  const signInBtn = document.getElementById("signInBtn");
  const signInCloseBtn = document.getElementById("signInCloseBtn");
  const signInOverlay = document.getElementById("signInModal");
  const signInErrorMsg = document.getElementById("signInErrorMsg");

  const signUpBtn = document.getElementById("signUpBtn");
  const signUpCloseBtn = document.getElementById("signUpCloseBtn");
  const signUpOverlay = document.getElementById("signUpModal");
  const signUpErrorMsg = document.getElementById("signUpErrorMsg");

  const newThreadBtn = document.getElementById("newThreadBtn");
  const newThreadCloseBtn = document.getElementById("newThreadCloseBtn");
  const newThreadOverlay = document.getElementById("newThreadModal");
  // const newThreadErrorMsg = document.getElementById("newThreadErrorMsg"); // Da vedere se implementare errore nel new thread modal
  const newThreadForm = document.getElementById("newThreadForm");

  const profileSettingsBtn = document.getElementById("profileSettingsBtn");
  const profileSettingsCloseBtn = document.getElementById(
    "profileSettingsCloseBtn",
  );
  const profileSettingsOverlay = document.getElementById(
    "profileSettingsModal",
  );
  const profileSettingsErrorMsg = document.getElementById(
    "profileSettingsErrorMsg",
  );

  const dropZone = document.getElementById("dropZone");
  const fileInput = document.getElementById("fileInput");
  const fileList = document.getElementById("fileList");
  let allFiles = []; // contenitore dei file di drag-and-drop

  const contactUsModal = document.getElementById("contactUsModal");
  const contactUsCloseBtn = document.getElementById("contactUsCloseBtn");
  const contactUsLink = document.getElementById("contactUsLink");
  const contactUsErrorMsg = document.getElementById("contactUsErrorMsg");

  const signinButtons = document.querySelectorAll(".postSignInBtn"); // quelli che ti escono al posto di reply se non sei loggato

  signinButtons.forEach((button) => {
    // CORREZIONE 2: Aggiunto 'e' tra le parentesi tonde
    button.addEventListener("click", function (e) {
      e.preventDefault();

      // Assicurati che signInOverlay sia stato definito prima nel codice!
      // const signInOverlay = document.getElementById('tuo_id_overlay');
      signInOverlay.classList.remove("hidden");
    });
  });

  // Controllo password uguale
  const password = document.getElementById("password");
  const confirmPassword = document.getElementById("confirmPassword");
  const submitBtn = document.getElementById("btnSubmit");

  function validatePassword() {
    if (password.value !== confirmPassword.value) {
      // Imposta l'errore (blocca il form)
      confirmPassword.setCustomValidity("The passwords do not match.");
    } else {
      // Rimuove l'errore (sblocca il form)
      confirmPassword.setCustomValidity("");
    }
  }

  password.addEventListener("input", validatePassword);
  confirmPassword.addEventListener("input", validatePassword);
  submitBtn.addEventListener("click", validatePassword);

  // Apre il modale SIGN IN da navbar
  if (navSignInBtn) {
    navSignInBtn.addEventListener("click", (e) => {
      e.preventDefault();
      signInOverlay.classList.remove("hidden");
    });
  }

  // Apre il modale SIGN IN
  signInBtn.addEventListener("click", (e) => {
    e.preventDefault(); // Impedisce al link di navigare
    signInOverlay.classList.remove("hidden");
    signUpErrorMsg.classList.add("hidden"); // toglie la scritta di errore alla prossima apertura del modale
    signUpOverlay.classList.add("hidden");
  });

  // Chiude il modale SIGN IN
  signInCloseBtn.addEventListener("click", () => {
    signInOverlay.classList.add("hidden");
    signInErrorMsg.classList.add("hidden"); // toglie la scritta di errore alla prossima apertura del modale
  });

  // Apre il modale SIGN UP
  signUpBtn.addEventListener("click", (e) => {
    console.log("cliccato su registrati");
    e.preventDefault();
    signInOverlay.classList.add("hidden");
    signInErrorMsg.classList.add("hidden"); // toglie la scritta di errore alla prossima apertura del modale
    signUpOverlay.classList.remove("hidden");
  });

  // Chiude il modale SIGN UP
  signUpCloseBtn.addEventListener("click", () => {
    signUpOverlay.classList.add("hidden");
    signUpErrorMsg.classList.add("hidden"); // toglie la scritta di errore alla prossima apertura del modale
  });

  // Apre il modale NEW THREAD
  if (newThreadBtn) {
    newThreadBtn.addEventListener("click", (e) => {
      e.preventDefault();
      newThreadOverlay.classList.remove("hidden");
    });
  }

  // Chiude il modale NEW THREAD
  newThreadCloseBtn.addEventListener("click", () => {
    allFiles = [];
    fileList.innerHTML = "";
    fileInput.value = "";
    newThreadOverlay.classList.add("hidden");
    newThreadForm.reset();
  });

  // Cambio css quando draggo sulla dropZone
  dropZone.addEventListener("dragover", (e) => {
    e.preventDefault();
    dropZone.classList.add("drop-zone--over");
  });

  // Quando il file esce dalla dropZone o viene rilasciato
  ["dragleave", "dragend"].forEach((type) => {
    dropZone.addEventListener(type, () => {
      dropZone.classList.remove("drop-zone--over");
    });
  });

  function handleFiles(files) {
    const newFiles = Array.from(files);
    allFiles = [...allFiles, ...newFiles];

    // Bisogna usare dataTransfer perchè i browser non permettono di aggiungere file a input esistente, l'input è in sola lettura.
    const dt = new DataTransfer();
    allFiles.forEach((file) => dt.items.add(file));
    fileInput.files = dt.files;

    updateThumbnailList();
  }

  dropZone.addEventListener("drop", (e) => {
    e.preventDefault();
    dropZone.classList.remove("drop-zone--over");
    handleFiles(e.dataTransfer.files);
  });

  fileInput.addEventListener("change", () => {
    handleFiles(fileInput.files);
  });

  function updateThumbnailList() {
    fileList.innerHTML = ""; // Ora svuotare qui è giusto, perché allFiles contiene TUTTO
    allFiles.forEach((file) => {
      const container = document.createElement("div");
      container.className = "thumbnail-container";
      if (file.type.startsWith("image/")) {
        const img = document.createElement("img");
        img.src = URL.createObjectURL(file);
        img.onload = () => URL.revokeObjectURL(img.src);
        container.appendChild(img);
      } else {
        container.innerHTML = `<div class="thumbnail-placeholder">📄</div>`;
      }
      fileList.appendChild(container);
    });
  }
  // APERTURA MODALE IMMAGINE A SCHERMO INTERO
  const imageModal = document.getElementById("imageModal");
  const modalImg = document.getElementById("fullImage");
  const closeBtn = document.querySelector(".image-close");

  // Trova tutte le immagini nei post
  const images = document.querySelectorAll(".post-image");

  images.forEach((img) => {
    img.onclick = function () {
      imageModal.style.display = "block";
      modalImg.src = this.src; // Copia il percorso dell'immagine cliccata
    };
  });

  // Chiudi quando clicchi sulla X
  if (closeBtn) {
    closeBtn.onclick = function () {
      imageModal.style.display = "none";
    };
  }

  // Chiudi quando clicchi ovunque sullo sfondo scuro
  if (imageModal) {
    imageModal.onclick = function (e) {
      if (e.target !== modalImg) {
        imageModal.style.display = "none";
      }
    };
  }

  // Chiudi premendo il tasto ESC
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
      imageModal.style.display = "none";
    }
  });

  // PROFILE SETTINGS MODAL
  if (profileSettingsBtn) {
    profileSettingsBtn.addEventListener("click", (e) => {
      e.preventDefault();
      profileSettingsOverlay.classList.remove("hidden");
    });
  }
  profileSettingsCloseBtn.addEventListener("click", (e) => {
    profileSettingsOverlay.classList.add("hidden");
    profileSettingsErrorMsg.classList.add("hidden");
  });

  // CONTACT US MODAL
  contactUsLink.addEventListener("click", (e) => {
    e.preventDefault();
    contactUsModal.classList.remove("hidden");
  });
  contactUsCloseBtn.addEventListener("click", (e) => {
    contactUsModal.classList.add("hidden");
    contactUsErrorMsg.classList.add("hidden");
  });
});
