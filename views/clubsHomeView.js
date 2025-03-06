document.addEventListener("DOMContentLoaded", () => {
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

  // Post form handling
  const postForm = document.getElementById("add-post-form");
  const postContainer = document.querySelector(".container.border");

  postForm.addEventListener("submit", (event) => {
    event.preventDefault();

    const title = document.getElementById("post-title").value.trim();
    const caption = document.getElementById("post-caption").value.trim();
    const imageInput = document.getElementById("post-image");

    if (!title || !caption) {
      showToast("Please fill in all fields!", "danger");
      return;
    }

    const newPost = document.createElement("div");
    newPost.classList.add("card", "m-3");
    newPost.style.maxWidth = "540px";

    let imageSrc = "/assets/photos/img placeholder.png"; // Default placeholder
    if (imageInput.files.length > 0) {
      const reader = new FileReader();
      reader.onload = function (e) {
        newPost.querySelector("img").src = e.target.result;
      };
      reader.readAsDataURL(imageInput.files[0]);
    }

    newPost.innerHTML = `
      <div class="row g-0">
        <div class="col-md-4">
          <img src="${imageSrc}" class="img-fluid rounded-start" alt="Post Image">
        </div>
        <div class="col-md-8">
          <div class="card-body">
            <h5 class="card-title">${title}</h5>
            <p class="card-text">${caption}</p>
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <button class="btn btn-success">In</button>
                <button class="btn btn-danger">Out</button>
              </div>
              <div class="d-flex gap-3 align-items-center">
                <p class="right"><small>In</small></p>
                <p class="right"><small>Out</small></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    `;

    postContainer.appendChild(newPost);
    postForm.reset();
    showToast("Post created successfully!", "success");
  });

  // Notification handling
  const notifBtn = document.getElementById("notif-btn");
  const notifBox = document.getElementById("notif-box");
  const notifList = document.getElementById("notif-list");
  const notifCount = document.getElementById("notif-count");

  // Sample users requesting to join (this should be fetched from the backend)
  let joinRequests = [
    { id: 1, name: "Alice" },
    { id: 2, name: "Bob" },
  ];

  // Function to update the notification list
  function updateNotifList() {
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
    notifBox.style.display =
      notifBox.style.display === "none" ? "block" : "none";
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
