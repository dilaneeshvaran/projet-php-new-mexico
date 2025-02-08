document.addEventListener("DOMContentLoaded", () => {
  function openDeleteModal(event) {
    event.preventDefault();
    document.getElementById("deleteModal").style.display = "flex";

    //retrieve groupId
    const groupId = document
      .querySelector(".form__delete")
      .getAttribute("data-group-id");

    //store delete URL
    deleteUrl = `/group/${groupId}/delete`;
  }

  function closeDeleteModal() {
    document.getElementById("deleteModal").style.display = "none";
  }

  function confirmDeletion() {
    if (deleteUrl) {
      window.location.href = deleteUrl;
    }
  }

  //attach event listener to delete button
  document
    .querySelector(".form__delete")
    .addEventListener("click", openDeleteModal);

  //attach event listeners to modal buttons
  document
    .getElementById("cancelDelete")
    .addEventListener("click", closeDeleteModal);
  document
    .getElementById("confirmDelete")
    .addEventListener("click", confirmDeletion);
});
