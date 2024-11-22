window.addEventListener("DOMContentLoaded", (event) => {
  // Attach click event listener to tag links
  let tagLinks = document.querySelectorAll(".tag-bar-btn:not(#moreTagButton)");
  tagLinks.forEach((tagLink) => {
    tagLink.addEventListener("click", function (event) {
      event.preventDefault();

      // Remove active class from all tag links
      tagLinks.forEach((link) => {
        link.classList.remove("active");
      });

      this.classList.add("active");

      // Get tag ID from data-tag-id attribute
      let tagId = this.dataset.tagId;

      sendAjaxRequest("get", `/home?tag=${tagId}`, null, function () {
        if (this.status != 200) window.location = "/";
        let questionsContainer = document.querySelector("#questionsContainer");
        questionsContainer.innerHTML = this.responseText;
      });

      history.pushState(null, "", `?tag=${tagId}`);
    });
  });
  let urlParams = new URLSearchParams(window.location.search);
  let tag = urlParams.get("tag");

  if (tag) {
    // Find the tag link with the matching data-tag-id attribute and trigger its click event
    let tagLink = Array.from(tagLinks).find(
      (link) => link.dataset.tagId === tag
    );
    if (tagLink) tagLink.click();
  }
});

if (window.location.pathname.startsWith("/home")) {
  let page = 1;
  let noMoreQuestions = false;

  window.addEventListener("scroll", function () {
    if (window.innerHeight + window.scrollY >= document.body.offsetHeight && !noMoreQuestions) {
      // User has scrolled to the bottom of the page
      page++;

      let activeTagLink = document.querySelector(".tag-bar-btn.active");
      let tagId = activeTagLink ? activeTagLink.dataset.tagId : null;

      let url = `/home?page=${page}`;
      if (tagId) url += `&tag=${tagId}`;

      sendAjaxRequest("get", url, null, function () {
        if (this.status != 200) window.location = "/";
        let questionsContainer = document.querySelector("#questionsContainer");
        if(this.responseText.trim() == "") {
          noMoreQuestions = true;
          
        } else {
          questionsContainer.innerHTML += this.responseText;

        }
      });
    }
  });
}
