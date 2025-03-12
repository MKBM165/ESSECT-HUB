const uploadArea = document.getElementById("upload-area");
const fileInput = document.getElementById("cvFile");
const uploadText = document.getElementById("upload-text");

fileInput.addEventListener("change", function () {
  const fileName = fileInput.files[0]?.name || "No file chosen";
  uploadText.textContent = `📄 ${fileName}`;
});

uploadArea.addEventListener("dragover", (e) => {
  e.preventDefault();
  uploadArea.style.borderColor = "#0d6efd";
});

uploadArea.addEventListener("dragleave", () => {
  uploadArea.style.borderColor = "rgba(255, 255, 255, 0.4)";
});

uploadArea.addEventListener("drop", (e) => {
  e.preventDefault();
  fileInput.files = e.dataTransfer.files;
  const fileName = fileInput.files[0]?.name || "No file chosen";
  uploadText.textContent = `📄 ${fileName}`;
  uploadArea.style.borderColor = "rgba(255, 255, 255, 0.4)";
});
const createUserForm = document.getElementById("createUser");
createUserForm.addEventListener("submit", function (e) {
  e.preventDefault();
  const formData = new FormData(this);
  formData.append("action", "create_user");

  fetch("http://localhost/ESSECT-HUB/controllers/UserController.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Server error, status code: " + response.status);
      }
      // console.log(response.text());
      return response.json();
    })
    .then((data) => {
      console.log(data);
      if (data.success) {
        window.location.href = "user-home.html";
      } else {
        alert("Creation Failed: " + (data.error || "Error creating account."));
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("An error occurred. Please try again.");
    });
});
