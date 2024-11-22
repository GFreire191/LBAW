document.addEventListener("DOMContentLoaded", function (event) {
  // Add event listener to the answer form
  let answerForm = document.querySelector("#answer-form");
  if (answerForm != null) {
    answerForm.addEventListener("submit", answerFormSubmitRequest);
  }

  // Add event listener to the Edit button
  document.addEventListener("click", function (event) {
    if (event.target.matches("a#editButtonAn")) {
      event.preventDefault();

      if (event.target.textContent === "Edit") {
        makeAnswerEditable(event);
      } else if (event.target.textContent === "Save") {
        saveAnswer(event);
      }
    }
  });

  document.querySelectorAll("#deleteButtonAn").forEach(function (button) {
    button.addEventListener("click", function (event) {
      event.preventDefault();
      let form = document.querySelector("#delete-form-" + this.dataset.id);
      form.submit();
    });
  });

  document.addEventListener("click", function (event) {
    if (event.target.matches(".answer-correct .correct-button")) {
      event.preventDefault();
      if (event.target.dataset.correct == "0") {
        markAnswerCorrect(event);
      } else if (event.target.dataset.correct == "1") {
        removeAnswerCorrect(event);
      }
    }
  });


  let textarea = document.getElementById('answer-create-content');
  if(textarea != null){
    textarea.addEventListener('input', autoResize, false);
  }
});

function removeAnswerCorrect(event) {
  event.preventDefault();
  let answerElement = event.target.closest(".answer-box");
  let answerId = answerElement.dataset.answerId;
  let url = "/answer/" + answerId + "/edit";

  let data = {
    correct: false,
    _method: "PUT",
  };

  event.target.dataset.correct = "0";
  
  event.target.classList.remove('remove-correct');
  event.target.classList.add('mark-correct');

  sendAjaxRequest("post", url, data, function () {});
}

function markAnswerCorrect(event) {
  event.preventDefault();
  let answerElement = event.target.closest(".answer-box");
  let answerId = answerElement.dataset.answerId;
  let url = "/answer/" + answerId + "/edit";

  let data = {
    correct: true,
    _method: "PUT",
  };

  event.target.dataset.correct = "1";


  event.target.classList.remove('mark-correct');
  event.target.classList.add('remove-correct');  
  sendAjaxRequest("post", url, data, function () {});
}

function answerFormSubmitRequest(event) {
  event.preventDefault();

  // Get the form data
  let content = this.querySelector("textarea[name=content]").value;
  let url = this.getAttribute("action");
  let token = this.querySelector("input[name=_token]").value;

  let data = {
    content: content,
    _token: token,
  };

  sendAjaxRequest("post", url, data, answerFormSubmissionHandler);
}

function answerFormSubmissionHandler() {
  if (this.status == 403) {
    window.location.replace("/login");
  } else if (this.status != 200) {
    console.log("Error: " + this.status);
  } else {
    let newAnswer = document.createElement("div");
    newAnswer.innerHTML = this.responseText;

    newAnswer.style.animation = "popIn 0.5s";

    let answersContainer = document.querySelector("#answersContainer");
    answersContainer.insertBefore(newAnswer, answersContainer.firstChild);

    let answerForm = document.querySelector("#answer-form");
    answerForm.querySelector("textarea[name=content]").value = "";

    let deleteButton = newAnswer.querySelector("#deleteButtonAn");
    deleteButton.addEventListener("click", function (event) {
      event.preventDefault();
      let form = document.querySelector("#delete-form-" + this.dataset.id);
      form.submit();
    });
  }
}

function makeAnswerEditable(event) {
  event.preventDefault();

  let answerElement = event.target.closest(".single-answer-container");
  // Change the title and content fields into input fields

  let content = answerElement.querySelector(".answer-content h3 a");

  content.outerHTML =
    '<textarea id="content" class="editable-field">' +
    content.textContent +
    "</textarea>";

  // Change the "Edit" button to "Save"
  let editButton = event.target;
  editButton.textContent = "Save";
}

function saveAnswer(event) {
  event.preventDefault();

  let answerElement = event.target.closest(".single-answer-container");

  // Get the new content
  let newContent = answerElement.querySelector("#content").value;

  // Create a new answer
  let answer = {
    content: newContent,
    _method: "PUT",
  };

  let url = event.target.getAttribute("href");

  sendAjaxRequest("post", url, answer, function () {
    //pass the question element to the handler
    setTimeout(saveAnswerHandler.bind(this, answerElement, event.target), 0);
  });
}

function saveAnswerHandler(answerElement, saveButton) {
  if (this.status != 200) {
    console.log("Error: " + this.status);
  } else {
    // Get the new content
    let newContent = answerElement.querySelector("#content").value;
    let url = answerElement.dataset.url;

    // Change the input field back into text
    answerElement.querySelector("#content").outerHTML =
      '<h3><a class="clickable-title" href="' +
      url +
      '">' +
      newContent +
      "</a></h3>";

    // Change the "Save" button back to "Edit"
    saveButton.textContent = "Edit";
  }
}




function autoResize() {
    this.style.height = 'auto';
    this.style.height = this.scrollHeight + 'px';
}