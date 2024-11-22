window.onload = function() {
    var uploadButton = document.querySelector('#upLoadButton');
    var fileInput = document.getElementById('profile-upload');

    if(!fileInput || !uploadButton) return;



        uploadButton.addEventListener('click', function(event) {
            event.preventDefault();
            fileInput.click();
        });

        

    // Update the image preview when a file is selected
    fileInput.onchange = function (e) {
        e.preventDefault();
        var reader = new FileReader();
        reader.onload = function (event) {
            document.getElementById('profile-preview').src = event.target.result;
        }
        reader.readAsDataURL(e.target.files[0]);
    };
};