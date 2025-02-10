document.addEventListener("DOMContentLoaded", () => {
  const shareModal = document.getElementById("sharePhotoModal");
  const shareLinkInput = document.getElementById("shareLink");
  const copyButton = document.getElementById("copyShareLink");
  const closeButton = document.getElementById("closeShareModal");

  document.querySelectorAll(".sharePhotoButton").forEach((button) => {
    button.addEventListener("click", async () => {
      const photoId = button.dataset.photoId;
      const csrfToken = button.dataset.csrfToken;

      try {
        const response = await fetch("/photo/generate-share-link", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
          },
          body: JSON.stringify({
            photoId: photoId,
            csrf_token: csrfToken,
          }),
        });

        const data = await response.json();

        if (data.success && data.link) {
          shareLinkInput.value = data.link;
          shareModal.style.display = "flex";
        } else {
          console.error("Server response:", data);
          alert(
            data.error ||
              "Une erreur s'est produite lors de la génération du lien de partage."
          );
        }
      } catch (error) {
        console.error("Error:", error);
        alert(
          "Une erreur s'est produite lors de la génération du lien de partage."
        );
      }
    });
  });

  //copy & close
  copyButton.addEventListener("click", () => {
    shareLinkInput.select();
    document.execCommand("copy");
    copyButton.textContent = "Copié !";
    setTimeout(() => {
      copyButton.textContent = "Copier";
    }, 2000);
  });

  closeButton.addEventListener("click", () => {
    shareModal.style.display = "none";
  });

  window.addEventListener("click", (event) => {
    if (event.target === shareModal) {
      shareModal.style.display = "none";
    }
  });
});
