<!DOCTYPE html>
<html>
    <head>
        <title>image-uploader</title>
        <meta charset="UTF-8">
        <meta name="description" content="image-uploader">
        <meta name="author" content="Arjen van Gaal">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
        <script defer src="uploader.js"></script>
    </head>
    <body>
        <!-- File upload -->
        <img id="file-upload-image" src="http://placehold.it/256x256" alt="placeholder" height="256" width="256">
        <!-- File upload Tools -->
        <div style="visibility: hidden; position: absolute; top: -9999px; left: -9999px;">
            <input type="file" id="file-upload" name="file-upload" onchange="handleFile(this.files)"/>
            <img id="file-img">
            <canvas id="file-canvas"></canvas>
        </div>
    </body>
</html>
