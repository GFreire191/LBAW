document.addEventListener("DOMContentLoaded", function (event) {
  document.addEventListener("click", function (event) {
    if (event.target.matches("#notiButton")) {
      event.preventDefault();

      let url = event.target.getAttribute("href");

      sendAjaxRequest("get", url, null, function () {
        showNotificationsHandler.call(this, event);
      });
    } else if (event.target.matches("#clear-button")) {
      console.log("clear button clicked");
      event.preventDefault();
      let notificationContainer = event.target.closest(
        ".notification-container"
      );
      let form = event.target.nextElementSibling;
      
      sendAjaxRequest("delete", form.action, null, function () {
        if (this.status != 200) window.location = "/";
        notificationContainer.remove();
      });
    }

    let ajaxContainer = document.querySelector("#notificationsContainer");

    if (ajaxContainer && !ajaxContainer.contains(event.target)) {
      ajaxContainer.style.display = "none"; // Hide the div
    }
  });
});

function showNotificationsHandler(event) {
  if (this.status != 200) window.location = "/";

  let notificationContainer = document.createElement("div");
  notificationContainer.id = "notificationsContainer";
  notificationContainer.innerHTML = this.responseText;
  //console.log(this.responseText);

  notificationContainer.style.display = "block";
  notificationContainer.style.animation = "popIn 0.5s";
  document.body.appendChild(notificationContainer);

  let backdrop = document.createElement("div");
  backdrop.id = "backdrop";
  document.body.appendChild(backdrop);
}
