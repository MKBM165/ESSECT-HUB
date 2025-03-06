document.getElementById("login-form").addEventListener("submit", function (e) {
  e.preventDefault();

  const formData = new FormData(this); // Get form data

  fetch("/controllers/auth/login.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json()) // Convert response to JSON
    .then((data) => {
      if (data.success) {
        alert("Login Successful! Redirecting...");
        window.location.href = "user-home.html";
      } else {
        alert("Login Failed: " + data.message);
      }
    })
    .catch((error) => console.error("Error:", error));
});
