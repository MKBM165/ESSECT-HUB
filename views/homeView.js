/*
clubs{
nom : string
caption: string
urlimg: string
},{}]
*/
const clubsContainer = document.getElementById("clubs-container");
const ubdateClubsUI = function (clubs) {
  clubs.forEach((club) => {
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
        <a href="sign-in.html" class="btn btn-dark">Sign in to join</a>
      </div>
    </div>`;
    clubsContainer.insertAdjacentHTML("beforeend", clubCardHtml);
  });
};
document.addEventListener("DOMContentLoaded", function () {
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
