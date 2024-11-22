document.addEventListener("DOMContentLoaded", function (event) {

    let textarea_username = document.getElementById('profile-edit-username');
    if(textarea_username != null){
        textarea_username.addEventListener('input', autoResize, false);
    }

    let textarea_bio = document.getElementById('profile-edit-bio');
    if(textarea_bio != null){
        textarea_bio.addEventListener('input', autoResize, false);
    }

    document.addEventListener("click", function (event) {

        if (event.target.matches("#editProfile")) {
            event.preventDefault();

            let url = event.target.getAttribute("href");

            sendAjaxRequest("get", url, null, function () {
                showEditProfileFormHandler.call(this, event);
            });
        }
  });

});


function showEditProfileFormHandler(event) {
    if (this.status != 200) window.location = "/";
  
    ajaxContainer = document.createElement("div");
    ajaxContainer.id = "editProfileFormContainer";
    ajaxContainer.innerHTML = this.responseText;
    
    let form = ajaxContainer.querySelector("#profile-form");
    
    let user_id = event.target.dataset.user;

    form.action = `/user/${user_id}/edit`;
    form.addEventListener("submit", editProfileSubmitRequest);
  
    let textareaUserName = form.querySelector("textarea[name=username_content]");
    let textareaBio = form.querySelector("textarea[name=bio_content]");
    textareaUserName.addEventListener("click", function (event) {
      event.stopPropagation();
    });
    textareaBio.addEventListener("click", function (event) {
        event.stopPropagation();
      });
    

  
    ajaxContainer.style.display = "block";
    ajaxContainer.style.animation = "popIn 0.5s";
    document.body.appendChild(ajaxContainer);
  
    let backdrop = document.createElement("div");
    backdrop.id = "backdrop";
    document.body.appendChild(backdrop);
}

function editProfileSubmitRequest(event){
    event.preventDefault();
  let url = this.getAttribute("action");
  
  let contentUsername = this.querySelector("textarea[name=username_content]").value;
  
  let contentBio = this.querySelector("textarea[name=bio_content]").value;
  
  let token = this.querySelector("input[name=_token]").value;

  let data = {
    contentUsername: contentUsername,
    contentBio: contentBio,
    _token: token,
  };

  sendAjaxRequest("put", url, data, function () {
    if (this.status != 200) {
      console.log("Error: " + this.status);
    } else {
      let ajaxContainer = document.querySelector("#editProfileFormContainer");
      ajaxContainer.remove();
      let backdrop = document.querySelector("#backdrop");
      if (backdrop) {
        backdrop.remove();
      }
      document.body.classList.remove("blur");

      let parser = new DOMParser();

      let responseDoc = parser.parseFromString(this.responseText, "text/html");


      // replace the current page content with the new content

      document.open();

      document.write(responseDoc.documentElement.innerHTML);

      document.close();
    }
  });
}
  