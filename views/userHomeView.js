const aside = document.getElementById("userAside");
const toggleBtn = document.getElementById("toggleAside");

toggleBtn.addEventListener("click", (e) => {
  e.stopPropagation(); // Prevent click from propagating to document
  aside.classList.toggle("show");
});

// Close aside when clicking outside
document.addEventListener("click", (e) => {
  if (
    aside.classList.contains("show") &&
    !aside.contains(e.target) &&
    e.target !== toggleBtn
  ) {
    aside.classList.remove("show");
  }
});

// Prevent clicks inside the aside from closing it
aside.addEventListener("click", (e) => {
  e.stopPropagation();
});
