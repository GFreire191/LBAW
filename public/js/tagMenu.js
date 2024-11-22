
document.addEventListener("DOMContentLoaded", function (event) {
    



    document.addEventListener("click", function (event) {


        if (event.target.matches(".tagButtonAdd")) {
            //Get the questionElement
            
            addTag(event);
        }else{
            document.querySelectorAll("#tagMenu").forEach(function (container) {
                // Check if the clicked target is inside #tagMenu
                if (!container.contains(event.target)) {
                    // Remove the #tagMenu element
                    container.remove();
                }
            });
        }



        


    });


    







});

function addTag(event) {

    event.preventDefault();
    event.stopPropagation();
    
    let questionId = event.target.dataset.questionId;
    let tagId = event.target.dataset.tagId;

    event.target.remove();

    let url = `/question/${questionId}/edit`;
    let data = {
        addTag: true,
        tagId: tagId,
        _method: "PUT",
    };

    sendAjaxRequest("post", url, data, function(){
        if (this.status != 200) {
            console.log("Error: " + this.status);
        }


        let newTag = document.createElement("a");
        newTag.classList.add("tagButton");
        newTag.id = "tagButtonEdit";
        newTag.dataset.tagId = tagId;
        newTag.dataset.questionId = questionId;

        newTag.textContent = event.target.textContent;
        newTag.style.marginRight = "10px";

        let questionElement = document.querySelector(`[data-question-id="${questionId}"]`);

        let tagButtons = questionElement.querySelector("#tagButtons");
        let lastTagButton = tagButtons.lastElementChild;

        tagButtons.insertBefore(newTag, lastTagButton);


    });



}

