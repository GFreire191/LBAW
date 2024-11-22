document.addEventListener("DOMContentLoaded", function (event) {
    
    
        document.addEventListener("click", function (event) {               
            if (event.target.matches(".voteButton_question")) {
            event.preventDefault();
            
            var vote_status = event.target.dataset.vote;
            var questionId = event.target.dataset.questionId;
            let url = '/question/' + questionId + '/vote';
            
            let number_votes = event.target.closest(".vote-btn").querySelector(".voteCount");
            let oposite_number_votes;
            if(vote_status==='up'){
                oposite_number_votes = event.target.closest(".vote-btn").nextElementSibling.querySelector(".voteCount");
            }
            else if(vote_status==='down'){
                oposite_number_votes = event.target.closest(".vote-btn").previousElementSibling.querySelector(".voteCount");
            }
            
            let data = {
                vote_status: vote_status
            };
            sendAjaxRequest("post", url, data, function () {
                voteFunction.call(this,number_votes,oposite_number_votes) });
            }
        });

        document.addEventListener("click", function (event) {               
            if (event.target.matches(".voteButton_answer")) {
            event.preventDefault();
            
            var vote_status = event.target.dataset.vote;
            var answerId = event.target.dataset.answerId;
            let url = '/answer/' + answerId + '/vote';
            
            let number_votes = event.target.closest(".vote-btn").querySelector(".voteCount");
            let oposite_number_votes;
            if(vote_status==='up'){
                oposite_number_votes = event.target.closest(".vote-btn").nextElementSibling.querySelector(".voteCount");
            }
            else if(vote_status==='down'){
                oposite_number_votes = event.target.closest(".vote-btn").previousElementSibling.querySelector(".voteCount");
            }
            
            let data = {
                vote_status: vote_status
            };
            sendAjaxRequest("post", url, data, function () {
                voteFunction.call(this,number_votes,oposite_number_votes) });
            }
        });

});   


function voteFunction(number_votes,oposite_number_votes) {

    let response = JSON.parse(this.responseText);
    
    if(response.updateFlag === '1'){
        oposite_number_votes.textContent = parseInt(oposite_number_votes.textContent) -1;
    }
    number_votes.textContent = parseInt(number_votes.textContent) + parseInt(response.numberVotes);

}


