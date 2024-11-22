document.addEventListener("DOMContentLoaded", function (event) {
  let createFormButton = document.querySelector("a#FormButton");
  if (createFormButton != null) {
    createFormButton.addEventListener("click", showCreateFormRequest);
  }

  // Add event listener to the Edit button
  document.addEventListener("click", function (event) {
    if (event.target.matches("a#editButton")) {
      event.preventDefault();

      if (event.target.textContent === "Edit") {
        makeQuestionEditable(event);
      } else if (event.target.textContent === "Save") {
        saveQuestion(event);
      }
    } else if (event.target.matches(".followButton")){
      event.preventDefault();
      let url = event.target.getAttribute("href");
      let followButton = event.target;
      sendAjaxRequest("post", url, null, function () {
        updateFollowButton.call(this, followButton);
      });
    }
  });

  document.querySelectorAll("#deleteButton").forEach(function (button) {
    button.addEventListener("click", function (event) {
      event.preventDefault();
      let form = document.querySelector("#delete-form-" + this.dataset.id);
      form.submit();
    });
  });

  document.querySelectorAll(".tagButton:not(.tag-button-rem-admin):not(.tagAdminAdd)").forEach(function (button) {
    button.addEventListener("click", handleTagButtonClick);
  });

  
});

function updateFollowButton(followButton) {
  if (this.status == 200) {

    followButton.textContent = followButton.textContent.trim() === "Follow" ? "Unfollow" : "Follow";

    let url = followButton.getAttribute("href");
    if(followButton.textContent === "Follow") url = url.replace("unfollow", "follow");
    else url = url.replace("follow", "unfollow");

    
    followButton.setAttribute("href", url);

  } else if (this.status == 403) {
    let response = JSON.parse(this.responseText);
    alert(response.message);
  } else {
    console.log("Error: " + this.status);
  }
}

function handleDeleteTag(event) {
  event.preventDefault();
  

  
  let questionId = event.target.dataset.questionId;
  let tagId = event.target.dataset.tagId;
  
  
  // Remove the clicked button
  event.target.remove();
  // Get the question id and tag id
  

  // Create the url for the AJAX request
  let url = `/question/${questionId}/edit`;

  // Create the data for the AJAX request
  let data = {
    
    deleteTag: true,
    tagId: tagId,
    _method: "PUT",
  };

  // Send the AJAX request to delete the relation
  sendAjaxRequest("post", url, data, function () {
    if (this.status != 200) {
      console.log("Error: " + this.status);
    }
  });
}

function showCreateFormRequest(event) {
  event.preventDefault();
  let url = this.getAttribute("href");

  sendAjaxRequest("get", url, null, showCreateFormHandler);
  let ajaxContainer = document.querySelector("#createFormContainer");
  ajaxContainer.style.animation = "popIn 0.5s";

  

}

function showCreateFormHandler() {
  if (this.status != 200) window.location = "/";

  let ajaxContainer = document.querySelector("#createFormContainer");
  ajaxContainer.innerHTML = this.responseText;

  
  let form = ajaxContainer.querySelector("#question-form");
  form.addEventListener("submit", formSubmitRequest);

  let title = form.querySelector("input[name=title]");
  let content = form.querySelector("textarea[name=content]");

  title.addEventListener("click", function(event) {
    event.stopPropagation();
  });

  content.addEventListener("click", function(event) {
    event.stopPropagation();
  });

  ajaxContainer.style.display = "block";
  
  let backdrop = document.createElement('div');
  backdrop.id = 'backdrop';
  document.body.appendChild(backdrop);
}

function formSubmitRequest(event) {
  event.preventDefault();

  // Get the form data (title)
  let title = this.querySelector("input[name=title]").value;
  let url = this.getAttribute("action");
  let token = this.querySelector("input[name=_token]").value;
  let content = this.querySelector("textarea[name=content]").value;

  let data = {
    title: title,
    _token: token,
    content: content,
  };

  sendAjaxRequest("post", url, data, QuestionFormSubmissionHandler);
}

function QuestionFormSubmissionHandler() {
  if (this.status != 200) {
  } else {
    let newQuestion = document.createElement("div");
    newQuestion.innerHTML = this.responseText;

    newQuestion.style.animation= "popIn 0.5s";
    // Append the new question to the list of questions
    let QuestionsContainer = document.querySelector("#questionsContainer");
    QuestionsContainer.insertBefore(newQuestion, QuestionsContainer.firstChild);

    let QuestionForm = document.querySelector("#question-form");
    QuestionForm.querySelector("input[name=title]").value = "";
    QuestionForm.querySelector("textarea[name=content]").value = "";

    let deleteButton = newQuestion.querySelector("#deleteButton");
    deleteButton.addEventListener("click", function (event) {
      event.preventDefault();
      let form = document.querySelector("#delete-form-" + this.dataset.id);
      form.submit();
    });

    document.querySelector("#createFormContainer").style.display = "none";
    let backdrop = document.querySelector('#backdrop');
    if (backdrop) {
      backdrop.remove();
    }document.body.classList.remove('blur');
  }
}

function updateQuestionsHandler() {
  if (this.status != 200) {
  } else {
    let questionsContainer = document.querySelector("#questionsContainer");

    questionsContainer.innerHTML = "";

    questionsContainer.innerHTML = this.responseText;
  }
}

document.addEventListener("click", function (event) {
  let ajaxContainer = document.querySelector("#createFormContainer");

  if (ajaxContainer && !ajaxContainer.contains(event.target)) {
    ajaxContainer.style.display = "none"; // Hide the div
    let backdrop = document.querySelector('#backdrop');
    if (backdrop) {
      backdrop.remove();
    }document.body.classList.remove('blur');
  }
});

function makeQuestionEditable(event) {
  event.preventDefault();

  let questionElement = event.target.closest('.question-box');

  // Change the title and content fields into input fields
  let title = questionElement.querySelector(".question-content h2 a");
  let content = questionElement.querySelector(".question-content p");
  title.outerHTML =
    '<input type="text" id="title" class="editable-field" value="' +
    title.textContent +
    '">';
  content.outerHTML =
    '<textarea id="content" class="editable-field">' +
    content.textContent +
    "</textarea>";

  // Change the "Edit" button to "Save"
  let editButton = event.target;
  editButton.textContent = "Save";

  // Change the tag Buttons class
  let tagButtons = questionElement.querySelectorAll(".tagButton:not(.tag-button-rem-admin):not(.tagAdminAdd)");
  tagButtons.forEach(function (tagButton) {
    tagButton.id = "tagButtonEdit";
    tagButton.addEventListener("click", handleDeleteTag);
  });

  //Create new button to add tags
  let addTagButton = document.createElement("button");
  addTagButton.className = "tagButton";
  addTagButton.id = "addTagButton";
  addTagButton.textContent = "+";

  // Add to after the last tag button
  let tagButtonsParent = questionElement.querySelector("#tagButtons");
  tagButtonsParent.appendChild(addTagButton);

  // Add event listener to the new button
  addTagButton.addEventListener("click", tagMenu);
}


function tagMenu(event) {

  event.preventDefault();

  let questionId = event.target.parentElement.parentElement.dataset.questionId;

  let url = `/tag/${questionId}`;

  sendAjaxRequest("get", url, null, tagMenuHandler);

}

function tagMenuHandler() {
  if (this.status != 200) {
    console.log("Error: " + this.status);
  }

  let tagMenu = document.createElement("div");
  tagMenu.id = "tagMenu";
  tagMenu.innerHTML = this.responseText;

  // Add tag menu to the page I dont have a container for it yet. I want to add it in the midle of the screen position absolute

  let body = document.querySelector("body");
  body.appendChild(tagMenu);

}

function saveQuestion(event) {
  event.preventDefault();

  let questionElement = event.target.closest('.question-box');

  // Get the new title and content
  let newTitle = questionElement.querySelector("#title").value;
  let newContent = questionElement.querySelector("#content").value;

  // Create a new question
  let question = {
    title: newTitle,
    content: newContent,
    _method: "PUT",
  };

  let url = event.target.getAttribute("href");

  sendAjaxRequest("post", url, question, function () {
    //pass the question element to the handler
    setTimeout(
      saveQuestionHandler.bind(this, questionElement, event.target),
      0
    );
  });

  
}

function saveQuestionHandler(questionElement, saveButton) {
  if (this.status != 200) {
    console.log("Error: " + this.status);
  } else {
    // Get the new title and content
    let newTitle = questionElement.querySelector("#title").value;
    let newContent = questionElement.querySelector("#content").value;

    // Change the input fields back into text
    questionElement.querySelector("#title").outerHTML =
      "<a class='clickable-title' href='" +
      questionElement.dataset.url +
      "'>" +
      newTitle +
      "</a>";
    questionElement.querySelector("#content").outerHTML =
      "<p>" + newContent + "</p>";

    // Change the "Save" button back to "Edit"
    saveButton.textContent = "Edit";


    let addTagButton = questionElement.querySelector("#addTagButton");
    addTagButton.remove();

    let editTagButtons = questionElement.querySelectorAll("#tagButtonEdit");
    editTagButtons.forEach(function (tagButton) {
      tagButton.id = "";
      tagButton.removeEventListener("click", handleDeleteTag);

    });
  }
}

function handleTagButtonClick(event) {

  if(event.target.id === "tagButtonEdit") return;
  event.preventDefault();

  let url = this.getAttribute("href");
  let tagId = url.split("=")[1];

  document
    .querySelectorAll(".tag-bar-btn.active")
    .forEach(function (button) {
      button.classList.remove("active");
    });

  let sidebarLink = document.querySelector(
    `#sideBarContent a[data-tag-id='${tagId}']`
  );
  if (sidebarLink) {
    sidebarLink.classList.add("active");
  }

  sendAjaxRequest("get", url, null, handleTagButtonResponse);
}

function handleTagButtonResponse() {
  if (this.status != 200) window.location = "/";

  let questionsContainer = document.querySelector("#questionsContainer");
  questionsContainer.innerHTML = this.responseText;
}
