// Toast initialization
const toastEl = document.getElementById("liveToast");
const bootstrapToast = new bootstrap.Toast(toastEl);

// Function to show toast messages
function showToast(message, type = "info") {
  const toastHeader = toastEl.querySelector(".toast-header");
  const toastBody = toastEl.querySelector(".toast-body");
  const title = toastHeader.querySelector("strong");

  // Update toast content and styling
  title.textContent = "Notification";
  toastBody.textContent = message;
  toastHeader.className = `toast-header bg-${type} text-white`;

  // Show the toast
  bootstrapToast.show();
}
const notifBtn = document.getElementById("notif-btn");
const notifBox = document.getElementById("notif-box");
const notifList = document.getElementById("notif-list");
const notifCount = document.getElementById("notif-count");
let joinRequests = [
  { id: 1, name: "Alice" },
  { id: 2, name: "Bob" },
];
function updateNotifList(joinRequests) {
  notifList.innerHTML = ""; // Clear the list
  notifCount.textContent = joinRequests.length; // Update badge count

  if (joinRequests.length === 0) {
    notifList.innerHTML =
      "<li class='list-group-item text-muted'>No new requests</li>";
    return;
  }

  joinRequests.forEach((user) => {
    const li = document.createElement("li");
    li.classList.add(
      "list-group-item",
      "d-flex",
      "justify-content-between",
      "align-items-center"
    );

    li.innerHTML = `
      <span>${user.name}</span>
      <div>
        <button class="btn btn-success btn-sm accept-btn" data-id="${user.id}">✔</button>
        <button class="btn btn-danger btn-sm reject-btn" data-id="${user.id}">✖</button>
      </div>
    `;

    notifList.appendChild(li);
  });
}
// Show/hide notification box
notifBtn.addEventListener("click", () => {
  notifBox.style.display = notifBox.style.display === "none" ? "block" : "none";
});

// Handle accept/reject clicks
notifList.addEventListener("click", (event) => {
  if (event.target.classList.contains("accept-btn")) {
    const userId = event.target.getAttribute("data-id");
    joinRequests = joinRequests.filter((user) => user.id != userId);
    updateNotifList();
    showToast(`User ${userId} accepted!`, "success");
  }

  if (event.target.classList.contains("reject-btn")) {
    const userId = event.target.getAttribute("data-id");
    joinRequests = joinRequests.filter((user) => user.id != userId);
    updateNotifList();
    showToast(`User ${userId} rejected.`, "danger");
  }
});

// Close notification box when clicking outside
document.addEventListener("click", (event) => {
  if (!notifBtn.contains(event.target) && !notifBox.contains(event.target)) {
    notifBox.style.display = "none";
  }
});

let clubID;

const postForm = document.getElementById("add-post-form");
postForm.addEventListener("submit", (event) => {
  event.preventDefault();
  const formData = new FormData(this);
  formData.append("action", "create_post");

  fetch("http://localhost/ESSECT-HUB/controllers/PostController.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => {
      // console.log(response.text());
      return response.json();
    })
    .then((data) => {
      console.log(data);
      if (data.success) {
        showToast("Post created successfully!", "success");
        getClubPosts(clubID);
      } else {
        alert("Creation Failed: " + (data.error || "Error creating account."));
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("An error occurred. Please try again.");
    });
  postForm.reset();
});
const updateUIclub = function (club) {
  const clubName = document.getElementById("clubName");
  clubName.textContent = club.nom;
};
document.addEventListener("DOMContentLoaded", () => {
  fetch("http://localhost/ESSECT-HUB/controllers/ClubController.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: "action=getclubinfo", // Send the required action
    credentials: "include", // Ensure session cookies are sent
  })
    .then((response) => {
      // console.log(response.text());
      return response.json();
    })
    .then((data) => {
      if (data.success) {
        // console.log(data.user);
        updateUIclub(data.club);
        clubID = data.club.club_id;
      } else {
        // window.location.href = "index.html";
        console.error("Error:", data.error);
      }
    })
    .catch((error) => {
      window.location.href = "index.html";
      console.error("Error fetching profile:", error);
    });
  // Sample users requesting to join (this should be fetched from the backend)

  // Function to update the notification list

  /*
  // Simulate new join requests (for testing)
  setInterval(() => {
    const newUser = {
      id: joinRequests.length + 1,
      name: `User${joinRequests.length + 1}`,
    };
    joinRequests.push(newUser);
    updateNotifList();
    showToast(`${newUser.name} wants to join!`, "info");
  }, 10000); // Every 10 seconds a new request appears*/

  // Initialize notification list
  updateNotifList();
});
const getClubPosts = function (clubId) {
  fetch("http://localhost/ESSECT-HUB/controllers/PostController.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json", // Ensure JSON format
    },
    body: JSON.stringify({
      action: "get_club_posts",
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
        console.log("✅ Posts downloaded successfully:", data.message);
      } else {
        console.error("❌ Error:", data.error);
      }
    })
    .catch((error) => console.error("Fetch error:", error));
};
const addPostCard = function (post) {
  const postsContainer = document.getElementById("posts-container");
  postCardHtml = `
  <div class="card m-3" style="max-width: 540px">
    <div class="row g-0">
      <div class="col-md-4">
        <img
          src=${post.image}
          class="img-fluid rounded-start"
          alt="..."
        />
      </div>
      <div class="col-md-8">
        <div class="card-body">
          <h5 class="card-title">New Event</h5>
          <p class="card-text">
            ${post.caption}
          </p>
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <button onclick=reaction(${post.post_id},'in') class="btn btn-success">In</button>
              <button onclick=reaction(${post.post_id},'out') class="btn btn-danger">Out</button>
            </div>
            <!-- Show only to club Admin -->
            <div class="d-flex gap-3 align-items-center">
              <p class="right"><small>In</small></p>
              <p class="right"><small>Out</small></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  `;
  postsContainer.insertAdjacentHTML("beforeend", postCardHtml);
};
const reaction = function (postId, reactionType) {
  //reaction fetch function
};
const updatePostsUI = function (posts) {
  const postsContainer = document.getElementById("posts-container");
  postsContainersContainer.innerHTML = "";
  posts.forEach(addPostCard);
};
showToast("Post created successfully!", "success");
