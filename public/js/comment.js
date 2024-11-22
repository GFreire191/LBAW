document.addEventListener("DOMContentLoaded", function (event) {

    let textarea = document.getElementById('comment-create-content');
    if(textarea != null){
      textarea.addEventListener('input', autoResize, false);
    }

    document.addEventListener("click", function (event) {

      if (event.target.matches("#hideComents")) {
          event.preventDefault();

          let parentId = event.target.dataset.parentId;
          let parentType = event.target.dataset.parentType;

          hideComments(parentType, parentId, event);

      } else if (event.target.matches(".commentButton")) {
          event.preventDefault();

          let parentId = event.target.dataset.parentId;
          let parentType = event.target.dataset.parentType;

          loadComments(parentType, parentId, event);

      } else if (event.target.matches(".deleteButtonCom")) {
          event.preventDefault();

          let form = event.target.nextElementSibling;
          form.submit();
      } else if(event.target.matches("#createComment")){
          event.preventDefault();

          let url = event.target.getAttribute("href");

          sendAjaxRequest("get", url, null, function () {
            showCommentFormHandler.call(this, event);
          });
      }else if (event.target.matches(".editButtonCom")) {
        event.preventDefault();

        if (event.target.textContent === "Edit") {
          makeCommentEditable(event);
        } else if (event.target.textContent === "Save") {
          saveComment(event);
        }
      }

  });

});

function makeCommentEditable(event) {
  event.preventDefault();

  let commentElement = event.target.closest('.comment-box');

  // Change the content field into input field
  let commentContent = commentElement.querySelector(".comment-content a");

  commentContent.outerHTML =
    '<textarea id="commentContent" class="editable-field">' +
    commentContent.textContent +
    "</textarea>";

  // Change the "Edit" button to "Save"
  let editButton = event.target;
  editButton.textContent = "Save";
}

function saveComment(event) {
  event.preventDefault();

  let commentElement = event.target.closest('.comment-box');

  // Get the new content
  let newContent = commentElement.querySelector("#commentContent").value;

  // Create a new comment
  let comment = {
    commentContent: newContent,
    _method: "PUT",
  };

  let url = event.target.getAttribute("href");

  sendAjaxRequest("post", url, comment, function () {
    //pass the comment element to the handler
    setTimeout(
      saveCommentHandler.bind(this, commentElement, event.target),
      0
    );
  });
}

function saveCommentHandler(commentElement, saveButton) {
  if (this.status != 200) {
    console.log("Error: " + this.status);
  } else {
    // Get the new content
    let newContent = commentElement.querySelector("#commentContent").value;

    // Change the input field back into text
    commentElement.querySelector("#commentContent").outerHTML =
      "<p>" + newContent + "</p>";

    // Change the "Save" button back to "Edit"
    saveButton.textContent = "Edit";
  }
}

function loadComments(parentType, parentId, event) {

  let url = `/comment/${parentType}/${parentId}/show`;
  let parentElement;

  if(parentType == "answer"){

    parentElement = event.target.closest('.answer-box');

  }else{

    parentElement = event.target.closest('.question-box');

  }

  sendAjaxRequest("get", url, null, function () {
    loadCommentsSenderHandler.call(this, parentElement, parentType);
  });
}

function loadCommentsSenderHandler(parentElement, parentType){

  let comentsContainer = document.createElement("div");

  comentsContainer.classList.add("comentsContainer");

  comentsContainer.innerHTML = this.responseText;

  comentsContainer.style.maxHeight = "350px";
  comentsContainer.style.overflowY = "auto";

  // Get the reference to the comment button
  let hideCommentButton = parentElement.querySelector(".commentButton");

  hideCommentButton.id = "hideComents";
  hideCommentButton.textContent = "Hide Comments";

  if(parentType === "answer"){
    hideCommentButton.style.marginRight = "35px";
  }
  

  parentElement.appendChild(comentsContainer);

}

function hideComments(parentType, parentId, event) {

  let parentElement;

  if(parentType == "answer"){
    parentElement = event.target.closest('.answer-box');
  }else{
    parentElement = event.target.closest('.question-box');
  }

  let comentsContainer = parentElement.querySelector(".comentsContainer");

  if(comentsContainer){
    comentsContainer.remove();
  }

  // Get the reference to the comment button
  let commentButton = parentElement.querySelector("#hideComents");

  commentButton.id = "commentButton";
  commentButton.textContent = "View Comments";

}

function showCommentFormHandler(event) {
  if (this.status != 200) window.location = "/";

  ajaxContainer = document.createElement("div");
  ajaxContainer.id = "commentFormContainer";
  ajaxContainer.innerHTML = this.responseText;

  let form = ajaxContainer.querySelector("#comment-form");

  let parentId = event.target.dataset.parentId;
  let parentType = event.target.dataset.parentType;

  form.action = `/comment/${parentType}/${parentId}`;
  form.addEventListener("submit", commentFormSubmitRequest);

  let textarea = form.querySelector("textarea[name=content]");
  textarea.addEventListener("click", function (event) {
    event.stopPropagation();
  });

  ajaxContainer.style.display = "block";
  ajaxContainer.style.animation = "popIn 0.5s";
  document.body.appendChild(ajaxContainer);

  let backdrop = document.createElement("div");
  backdrop.id = "backdrop";
  document.body.appendChild(backdrop);
}

function commentFormSubmitRequest(event) {
  event.preventDefault();
  let url = this.getAttribute("action");
  
  let content = this.querySelector("textarea[name=content]").value;
  
  let token = this.querySelector("input[name=_token]").value;

  let data = {
    content: content,
    _token: token,
  };

  sendAjaxRequest("post", url, data, function () {
    if (this.status != 200) {
      console.log("Error: " + this.status);
    } else {
      let ajaxContainer = document.querySelector("#commentFormContainer");
      ajaxContainer.remove();
      let backdrop = document.querySelector("#backdrop");
      if (backdrop) {
        backdrop.remove();
      }
      document.body.classList.remove("blur");
    }
  });
}
