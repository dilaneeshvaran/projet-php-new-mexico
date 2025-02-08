document.addEventListener("DOMContentLoaded", () => {
  const removeMemberButton = document.getElementById("removeMemberButton");
  const removeMemberModal = document.getElementById("removeMemberModal");
  const confirmRemoveButton = document.getElementById("confirmRemove");
  const cancelRemoveButton = document.getElementById("cancelRemove");
  const removeMemberForm = document.getElementById("removeMemberForm");

  removeMemberButton.addEventListener("click", (event) => {
    event.preventDefault(); //prevent form submission default behavior
    removeMemberModal.style.display = "flex";
  });

  cancelRemoveButton.addEventListener("click", () => {
    removeMemberModal.style.display = "none";
  });

  confirmRemoveButton.addEventListener("click", () => {
    removeMemberForm.submit();
  });
});
