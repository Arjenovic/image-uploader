// Configuration
var imgWidthLimit = 256;
var imgHeightLimit = 256;
var filePrefType = 'png';

var imageElement;
var inputElement;

window.onload = function () {
     imageElement = document.getElementById('file-upload-image');
     inputElement = document.getElementById('file-upload');

    // Show Finder on image click
    imageElement.onclick = function () {
        inputElement.click();
    }
};

function handleFile(file) {
    var picture = file[0];

    if (picture) {
        // Check if files are actually images
        if (!picture.type.match(/image.*/)) {
            console.log('Client:', 'ERR: This file is not an image.');
        } else {
            console.log('Client:', 'This file is an image.');

            // Setup full original image
            var img = document.getElementById('file-img');
            var reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
            };
            reader.readAsDataURL(picture);

            // Setup Canvas to resize
            var canvas = document.getElementById('file-canvas');
            canvas.width = imgWidthLimit;
            canvas.height = imgHeightLimit;

            // Once full original image has loaded
            img.onload = function() {
                // Draw image onto the canvas within its size limits
                var canvasContext = canvas.getContext('2d');
                canvasContext.drawImage(img, 0, 0, imgWidthLimit, imgHeightLimit);

                // Create blob file from Canvas
                var picture_data = new FormData();
                canvas.toBlob(function(blob) {
                    // Convert blob to File and set File name
                    var fileNameFinal = (picture.name ? picture.name : 'untitled.' + filePrefType);
                    var fileFinal = new File([blob], fileNameFinal);
                    picture_data.append('file', fileFinal);

                    // Send file to backend
                    $.ajax({
                        url: 'uploader.php',
                        type: 'POST',
                        data: picture_data,
                        processData: false,
                        cache: false,
                        contentType: false
                    }).done(function(data){
                        // Parse response to JSON
                        var response = JSON.parse(data);
                        console.log(response);

                        // Change profile picture to -uploaded- file
                        var fileLocation = response[1];
                        if (fileLocation !== '') {
                            imageElement.src = fileLocation;
                        }
                    });
                }, 'image/' + filePrefType);
            };
        }
    } else {
        console.log('Client:', 'ERR: No image selected.');
    }
}
