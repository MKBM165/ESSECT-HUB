const form = document.getElementById("loginForm");
const adminUsername = "admin";
const adminPassword = "0000";
form.addEventListener("submit", function (e) {
  e.preventDefault();
  const username = document.getElementById("username");
  const password = document.getElementById("password");
  if (username.value === adminUsername && password.value === adminPassword) {
    window.location.href = "admin.html";
  } else {
    const formData = new FormData(this); // Get form data
    formData.append("action", "login");
    fetch("/ESSECT-HUB/controllers/ClubController.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        console.log(data);
        if (data.success) {
          window.location.href = "club-home.html";
        } else {
          //alert("Login Failed: " + (data.error || "Invalid credentials."));

          fetch("/ESSECT-HUB/controllers/UserController.php", {
            method: "POST",
            body: formData,
          })
            .then((response) => response.json())
            .then((data) => {
              console.log(data);
              if (data.success) {
                window.location.href = "user-home.html";
              } else {
                alert(
                  "Login Failed: " + (data.error || "Invalid credentials.")
                );
              }
            })
            .catch((error) => {
              console.error("Error:", error);
              alert("An error occurred. Please try again.");
            });
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        alert("An error occurred. Please try again.");
      });
  }
});

/*
  else if (username.value.slice(4) === "club") {
    fetch("/ESSECT-HUB/controllers/ClubController.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        console.log(data);
        if (data.success) {
          window.location.href = "club-home.html";
        } else {
          alert("Login Failed: " + (data.error || "Invalid credentials."));
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        alert("An error occurred. Please try again.");
      });
  }
*/
