document.addEventListener("DOMContentLoaded", () => {
  const leaveGroupButton = document.getElementById("leaveGroupButton");
  const leaveGroupModal = document.getElementById("leaveGroupModal");
  const confirmLeaveButton = document.getElementById("confirmLeave");
  const cancelLeaveButton = document.getElementById("cancelLeave");
  const leaveGroupForm = document.getElementById("leaveGroupForm");

  leaveGroupButton.addEventListener("click", () => {
    leaveGroupModal.style.display = "flex";
  });

  cancelLeaveButton.addEventListener("click", () => {
    leaveGroupModal.style.display = "none";
  });

  confirmLeaveButton.addEventListener("click", () => {
    leaveGroupForm.submit();
  });
});
