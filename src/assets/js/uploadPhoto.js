document.addEventListener("DOMContentLoaded", () => {
  const fileInput = document.getElementById("photo");
  const fileUpload = document.querySelector(".file-upload");
  const uploadIcon = document.querySelector(".upload-icon");

  if (!fileInput || !fileUpload || !uploadIcon) return;

  fileInput.addEventListener("change", () => {
    const file = fileInput.files[0];
    if (!file) return;

    //update upload icon and add class
    uploadIcon.innerHTML = "✓";
    fileUpload.classList.add("has-file");

    //create/update preview container
    let previewContainer = fileUpload.querySelector(".preview-container");
    if (!previewContainer) {
      previewContainer = document.createElement("div");
      previewContainer.className = "preview-container";
      fileUpload.appendChild(previewContainer);
    }

    //create file preview
    const reader = new FileReader();
    reader.onload = (e) => {
      const fileSize = (file.size / (1024 * 1024)).toFixed(2); //convert size to  Mo
      previewContainer.innerHTML = `
                <img src="${e.target.result}" alt="Preview" class="preview-image">
                <div class="file-details">
                    <strong>Fichier sélectionné:</strong> ${file.name}<br>
                    <small>Taille: ${fileSize} MO</small>
                </div>
            `;
    };

    if (file.type.startsWith("image/")) {
      reader.readAsDataURL(file);
    }
  });
});
