window.onload = function () {
    var image = document.getElementById('file-upload-image');
    var button = document.getElementById('file-upload');

    // Show Finder on image click
    image.onclick = function () {
        button.click();
    }
};

function handleFile(file) {
    var picture = file[0];
    var picture_data = new FormData();
    picture_data.append('file', picture);

    // TODO: Resize image

    // TODO: Client side validation

    // TODO: Send file to backend
    $.ajax({
        url: "uploader.php",
        type: "POST",
        data: picture_data,
        processData: false,
        cache: false,
        contentType: false
    }).done(function(data){
        alert(JSON.stringify(data));
    });
}