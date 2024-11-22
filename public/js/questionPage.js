if (window.location.pathname.startsWith("/question")) {
    let page = 1;
    let noMoreAnswers = false;

    window.addEventListener("scroll", function () {
      if (window.innerHeight + window.scrollY >= document.body.offsetHeight && !noMoreAnswers) {
        // User has scrolled to the bottom of the page
        page++;
        // Get the question ID from the URL
        let questionId = window.location.pathname.split("/")[2];
        
        let url = `/question/${questionId}/show?page=${page}`;
  
        sendAjaxRequest("get", url, null, function () {
          if (this.status != 200) window.location = "/";
          let answersContainer = document.querySelector("#answersContainer");
          if(this.responseText.trim() == "") {
            noMoreAnswers = true;
            
          } else {
            answersContainer.innerHTML += this.responseText;

          }
        });
      }
    });
  }