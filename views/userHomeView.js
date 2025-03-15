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
/*
const Flen = {
  username: "FlenFouleni",
  nom: "Flen",
  prenom: "Fouleni",
  clubs: [
    {
      id: 4,
      nom: "ClubFouleni",
    },
    {
      id: 4,
      nom: "ClubFouleniESSECT",
    },
  ],
};
*/
let userID;
const SendRequest = function (clubId) {
  fetch("http://localhost/ESSECT-HUB/controllers/RequestController.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json", // Ensure JSON format
    },
    body: JSON.stringify({
      action: "add_request",
      user_id: userID,
      club_id: clubId,
    }),
  })
    .then((response) => {
      // console.log(response.text());
      return response.json();
    })
    .then((data) => {
      console.log(data);
      if (data.success) {
        console.log("✅ Request sent successfully:", data.message);
      } else {
        console.error("❌ Error:", data.error);
      }
    })
    .catch((error) => console.error("Fetch error:", error));
};
const clubsContainer = document.getElementById("clubs-container");
const ubdateClubsUI = function (clubs) {
  clubs.forEach((club) => {
    const clubCardHtml = `
    <div class="card" style="width: 28rem">
      <img
        src="${club.club_image}"
        class="card-img-top"
        alt="${club.nom}"
      />
      <div
        class="card-body d-flex flex-column justify-content-between gap-4"
      >
        <h5 class="card-title">${club.nom}</h5>
        <p class="card-text">
          ${club.club_desc}
        </p>
        <button onclick=SendRequest(${club.club_id}) class="btn btn-dark">Request To Join</button>
      </div>
    </div>`;
    clubsContainer.insertAdjacentHTML("beforeend", clubCardHtml);
  });
};
const UserName = document.getElementById("userName");
const updateUIuser = function (user) {
  UserName.textContent = user.prenom + " " + user.nom;
  const clubslist = document.getElementById("clubs-list");
  user.clubs.forEach((club) => {
    const clubhtml = `<li class="club-item">
        <a class="club-item-link" href="club-home.html"
        >${club.nom}</a>
      </li>`;
    clubslist.insertAdjacentHTML("afterbegin", clubhtml);
  });
};
document.addEventListener("DOMContentLoaded", function () {
  fetch("http://localhost/ESSECT-HUB/controllers/UserController.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: "action=get_user_profile", // Send the required action
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
        // Update UI with user data
        // console.log(data.user);
        updateUIuser(data.user);
        userID = data.user.user_id;
      } else {
        window.location.href = "index.html";
        console.error("Error:", data.error);
      }
    })
    .catch((error) => {
      window.location.href = "index.html";
      console.error("Error fetching profile:", error);
    });
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
});
