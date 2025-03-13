const uploadArea = document.getElementById("upload-area");
const fileInput = document.getElementById("clubimgFile");
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

//RENDER CLUBS
const clubsContainer = document.getElementById("clubs-container");
const addClubCard = function (club) {
  const clubCardHtml = `
    <div class="card" style="width: 28rem">
      <img
        src="${club.club_image}"
        class="card-img-top h-100"
        alt="${club.nom}"
      />
      <div
        class="card-body d-flex flex-column justify-content-between gap-4"
      >
        <h5 class="card-title">${club.nom}</h5>
        <p class="card-text">
          ${club.club_desc}
        </p>
        <a href="sign-in.html" class="btn btn-dark">Visit Club</a>
      </div>
    </div>`;
  clubsContainer.insertAdjacentHTML("beforeend", clubCardHtml);
};
const ubdateClubsUI = function (clubs) {
  clubs.forEach(addClubCard);
};
const getclubs = function () {
  fetch("http://localhost/ESSECT-HUB/controllers/ClubController.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: "action=getclubs", // Send the required action
    credentials: "include", // Ensure session cookies are sent
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Server error, status code: " + response.status);
      }
      // console.log(response.text());
      return response.json();
    })
    .then((data) => {
      if (data.success) {
        // Update UI with clubs data
        console.log(data.clubs);
        ubdateClubsUI(data.clubs);
      } else {
        console.error("Error:", data.error);
      }
    })
    .catch((error) => {
      console.error("Error fetching profile:", error);
    });
};
const adminlogin = function () {
  fetch("http://localhost/ESSECT-HUB/controllers/AdminController.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: "action=login", // Send the required action
    credentials: "include", // Ensure session cookies are sent
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
        console.log("welcome admin");
      } else {
        console.error("Error:", data.error);
      }
    })
    .catch((error) => {
      console.error("Error fetching profile:", error);
    });
};
const createClubForm = document.getElementById("createClub");
createClubForm.addEventListener("submit", function (e) {
  e.preventDefault();
  const formData = new FormData(this);
  formData.append("action", "create_club");

  fetch("http://localhost/ESSECT-HUB/controllers/ClubController.php", {
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
        getclubs();
      } else {
        alert("Creation Failed: " + (data.error || "Error creating account."));
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("An error occurred. Please try again.");
    });
});

document.addEventListener("DOMContentLoaded", function () {
  getclubs();
  adminlogin();
});
