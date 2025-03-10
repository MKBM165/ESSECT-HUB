const form = document.getElementById("loginForm");
form.addEventListener("submit", function (e) {
  e.preventDefault();
  console.log("clicked");
  const formData = new FormData(this); // Get form data

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

        window.location.href = "user-home.html";
      } else {
        alert("Login Failed: " + data.message);
      }
    })
    .catch((error) => console.error("Error:", error));
});
