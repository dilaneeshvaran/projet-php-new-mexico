document.addEventListener("DOMContentLoaded", () => {
  const deleteButtons = document.querySelectorAll(".deletePhotoButton");
  const deleteModal = document.getElementById("deletePhotoModal");
  const confirmDeleteButton = document.getElementById("confirmDelete");
  const cancelDeleteButton = document.getElementById("cancelDelete");
  let currentForm = null;

  deleteButtons.forEach((button) => {
    button.addEventListener("click", (event) => {
      event.preventDefault();
      currentForm = button.closest("form");
      deleteModal.style.display = "flex";
    });
  });

  cancelDeleteButton.addEventListener("click", () => {
    deleteModal.style.display = "none";
  });

  confirmDeleteButton.addEventListener("click", () => {
    if (currentForm) {
      currentForm.submit();
    }
  });
});
