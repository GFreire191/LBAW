document.addEventListener("click", function (event) {
  let tagRemove = event.target;
  if (tagRemove.classList.contains("tag-button-rem-admin")) {
    event.preventDefault();
    let form = tagRemove.nextElementSibling;
    form.addEventListener("submit", function (event) {
      event.preventDefault();
      let url = form.getAttribute("action");
      let token = form.querySelector("input[name=_token]").value;
      sendAjaxRequest("delete", url, { _token: token }, function () {
        if (this.status != 200) {
          console.log("Error: " + this.status);
        } else {
          let tag = form.previousElementSibling;
          tag.remove();
          form.remove();
        }
      });
    });
    let submitEvent = new Event("submit");
    form.dispatchEvent(submitEvent);
  } else if (event.target.classList.contains("tagAdminAdd")) {
    event.preventDefault();
    let url = event.target.getAttribute("href");
    sendAjaxRequest("get", url, null, function () {
      if (this.status != 200) window.location = "/";

      ajaxContainer = document.createElement("div");
      ajaxContainer.id = "tagFormContainer";
      ajaxContainer.innerHTML = this.responseText;

      let form = ajaxContainer.querySelector("#tag-form");

      form.action = "/tag";
      form.addEventListener("submit", tagFormSubmitRequest);

      let inputArea = form.querySelector("input[name=name]");
      

      ajaxContainer.style.display = "block";
      ajaxContainer.style.animation = "popIn 0.5s";
      document.body.appendChild(ajaxContainer);

      let backdrop = document.createElement("div");
      backdrop.id = "backdrop";
      document.body.appendChild(backdrop);
    });
  }
});


function tagFormSubmitRequest(event) {
  event.preventDefault();

  // Get the form data
  let name = this.querySelector("input[name=name]").value;
  let url = this.getAttribute("action");
  let token = this.querySelector("input[name=_token]").value;

  let data = {
    name: name,
    _token: token,
  };

  sendAjaxRequest("post", url, data, tagFormSubmissionHandler);
}

function tagFormSubmissionHandler() {
  if (this.status == 403) {
    window.location.replace("/login");
  } else if (this.status != 200) {
    console.log("Error: " + this.status);
  } else {

    let responseObject = JSON.parse(this.response);
    //<a href="{{ route('delete.tag', ['tag' => $tag->id]) }}" class="tag-button-rem-admin tagButton" data-tag-id="{{$tag->id}}" >{{$tag->name}}</a>
    let newTag = document.createElement("a");
    newTag.classList.add("tag-button-rem-admin");
    newTag.classList.add("tagButton");
    newTag.textContent = responseObject.tag.name;
    newTag.href = "/tag/" + responseObject.tag.id + "/delete";
    newTag.setAttribute("data-tag-id", responseObject.tag.id);
    newTag.style.marginRight = "6px";
    newTag.style.animation = "popIn 0.5s";
    //insert the new tag before the add tag button
    let addTagButton = document.querySelector(".tagAdminAdd");
    addTagButton.parentElement.insertBefore(newTag, addTagButton);

    let ajaxContainer = document.querySelector("#tagFormContainer");
      ajaxContainer.remove();
      let backdrop = document.querySelector("#backdrop");
      if (backdrop) {
        backdrop.remove();
      }
      document.body.classList.remove("blur");

  }
}
