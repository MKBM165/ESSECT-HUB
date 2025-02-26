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
