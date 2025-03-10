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
updateUIuser(Flen);
