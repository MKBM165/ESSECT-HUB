const form = document.getElementById("loginForm");

form.addEventListener("submit", function (e) {
  e.preventDefault();

  const formData = new FormData(this);
  formData.append("action", "login"); // ✅ Ensure "login" action is sent

  fetch("/controllers/UserController.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json()) // Convert response to JSON
    .then((data) => {
      if (data.success) {
        alert("Login Successful! Redirecting...");
        sessionStorage.setItem("user", JSON.stringify(data));
        window.location.href = "user-home.html";
      } else {
        alert("Login Failed: " + (data.error || "Invalid credentials."));
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("An error occurred. Please try again.");
    });
});
