let ajaxContainer;

document.addEventListener("DOMContentLoaded", function (event) {
  document.addEventListener("click", function (event) {
    if (event.target.matches("#reportButton")) {
      event.preventDefault();

      let url = event.target.getAttribute("href");

      sendAjaxRequest("get", url, null, function () {
        showReportFormHandler.call(this, event);
      });
    }
  });

  document.addEventListener("click", function (event) {
    if (ajaxContainer && !ajaxContainer.contains(event.target)) {
      ajaxContainer.remove();
      let backdrop = document.querySelector("#backdrop");
      if (backdrop) {
        backdrop.remove();
      }
      document.body.classList.remove("blur");
    } else if(event.target.matches(".expand-arrow")){
      let arrow = event.target;
      let details = arrow.parentElement.parentElement.querySelector(".report-details");
      arrow.classList.toggle("flipped");
      details.style.display = details.style.display !== "block" ? "block" : "none";
    }
  });


  let deleteReportForm = document.getElementById('delete-report-form');
  if (deleteReportForm) {
    deleteReportForm.addEventListener('submit', function (event) {
      event.preventDefault();

      let url = this.getAttribute("action");
      let token = this.querySelector("input[name=_token]").value;

      let report = this.closest(".report-box");
      report.remove();
      sendAjaxRequest('delete', url, { _token: token }, function () {
        if (this.status != 200) {
          console.log("Error: " + this.status);
        }
      });
    });
  }

  let deleteQuestionForm = document.getElementById('delete-question-form');
  if(deleteQuestionForm){
    deleteQuestionForm.addEventListener('submit', function (event) {
      event.preventDefault();

      let url = this.getAttribute("action");
      let token = this.querySelector("input[name=_token]").value;

      let report = this.closest(".report-box");
      report.remove();
      sendAjaxRequest('delete', url, { _token: token }, function () {
        if (this.status != 200) {
          console.log("Error: " + this.status);
        }
      });
    });
  }

});

function showReportFormHandler(event) {
  if (this.status != 200) window.location = "/";

  ajaxContainer = document.createElement("div");
  ajaxContainer.id = "reportFormContainer";
  ajaxContainer.innerHTML = this.responseText;

  let form = ajaxContainer.querySelector("#report-form");

  let parentId = event.target.dataset.parentId;
  let parentType = event.target.dataset.parentType;
  form.action = `/report/${parentType}/${parentId}`;
  form.addEventListener("submit", reportFormSubmitRequest);

  let textarea = form.querySelector("textarea[name=motive]");
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

function reportFormSubmitRequest(event) {
  event.preventDefault();
  let url = this.getAttribute("action");
  
  let motive = this.querySelector("textarea[name=motive]").value;
  
  let token = this.querySelector("input[name=_token]").value;

  let data = {
    motive: motive,
    _token: token,
  };

  sendAjaxRequest("post", url, data, function () {
    if (this.status != 200) {
      console.log("Error: " + this.status);
    } else {
      let ajaxContainer = document.querySelector("#reportFormContainer");
      ajaxContainer.remove();
      let backdrop = document.querySelector("#backdrop");
      if (backdrop) {
        backdrop.remove();
      }
      document.body.classList.remove("blur");
    }
  });
}



