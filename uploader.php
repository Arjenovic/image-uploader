<?php
include_once("dbconnection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "Post request sent.";

    if(!empty($_FILES)) {
        echo "File detected.";
        $image = $_FILES;

        // Configuration
        $imageLimitHeight = 2560;
        $imageLimitWidth = 2560;
        $imageLimitSize = 500000;
        $targetDir = "uploads/";
        $targetFileName = uniqid() . "_" . basename($image['file']['name']);
        $targetFile = $targetDir . $targetFileName;
        $query = "INSERT INTO php_image_pictures(id, url) VALUES(NULL, '$targetFileName');";

        // Check if file is an image
        $check = getimagesize($image["file"]["tmp_name"]);
        if ($check !== false) {
            echo "File is image type: " . $check["mime"] . ".";
            $allowUpload = true;
        } else {
            echo "ERR: File is not an image.";
            $allowUpload = false;
        }

        // Check height and width
        list($imageWidth, $imageHeight) = getimagesize($image["file"]["tmp_name"]);
        if ($imageHeight > $imageLimitHeight || $imageWidth > $imageLimitWidth) {
            echo "ERR: File pixel size too large: " . $imageWidth . "x" . $imageHeight;
            $allowUpload = false;
        } else {
            echo "File pixel size is approved.";
        }

        // Check file size
        if ($image["file"]["size"] > $imageLimitSize) {
            echo "ERR: File size too large.";
            $allowUpload = false;
        } else {
            echo "Image size of " . $image["file"]["size"] . " approved.";
        }

        // Check if file already exists
        if (file_exists($targetFile)) {
            echo "ERR: File already exists.";
            $allowUpload = false;
        }

        // Allow certain file formats
        $imageFileType = exif_imagetype($image['file']['tmp_name']);
        if($imageFileType !== IMAGETYPE_PNG && $imageFileType !== IMAGETYPE_JPEG && $imageFileType !== IMAGETYPE_GIF) {
            echo "ERR: only JPG, JPEG, PNG & GIF files are allowed.";
            $allowUpload = false;
        }

        // Finally check if we can upload file
        if (!$allowUpload) {
            echo "ERR: File is not allowed to be uploaded.";
        } else {
            if (move_uploaded_file($image["file"]["tmp_name"], $targetFile)) {
                echo "Uploaded successfully to: " . $targetFile;

                // Insert into Database
                if(mysqli_query($con, $query)){
                    echo "Uploaded successfully to database.";
                } else{
                    echo "ERR: File could not be inserted to database.";
                }
            } else {
                echo "ERR: Uploading file failed.";
            }

            mysqli_close($con);
        }

    } else {
        echo "ERR: There is no file.";
    }

} else {
    echo "ERR: Not a post request.";
}
?>