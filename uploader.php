<?php
include_once("dbconnection.php");

$response = array("Server: ", "");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Prepare response
    $response[0].= "Post request sent.";

    if(!empty($_FILES)) {
        $response[0].= "File detected.";
        $image = $_FILES;

        // Configuration
        $imageLimitHeight = 256;
        $imageLimitWidth = 256;
        $imageLimitSize = 500000;
        $targetDir = "uploads/";
        $targetFileName = uniqid() . "_" . basename($image['file']['name']);
        $targetFile = $targetDir . $targetFileName;
        $query = "INSERT INTO php_image_pictures(id, url) VALUES(NULL, '$targetFileName');";

        // Check if file is an image
        $check = getimagesize($image["file"]["tmp_name"]);
        if ($check !== false) {
            $response[0].= "File is image type: " . $check["mime"] . ".";
            $allowUpload = true;
        } else {
            $response[0].= "ERR: File is not an image.";
            $allowUpload = false;
        }

        // Check height and width
        list($imageWidth, $imageHeight) = getimagesize($image["file"]["tmp_name"]);
        if ($imageHeight > $imageLimitHeight || $imageWidth > $imageLimitWidth) {
            $response[0].= "ERR: File pixel size too large: " . $imageWidth . "x" . $imageHeight;
            $allowUpload = false;
        } else {
            $response[0].= "File pixel size is approved.";
        }

        // Check file size
        if ($image["file"]["size"] > $imageLimitSize) {
            $response[0].= "ERR: File size too large.";
            $allowUpload = false;
        } else {
            $response[0].= "Image size of " . $image["file"]["size"] . " approved.";
        }

        // Check if file already exists
        if (file_exists($targetFile)) {
            $response[0].= "ERR: File already exists.";
            $allowUpload = false;
        }

        // Allow certain file formats
        $imageFileType = exif_imagetype($image['file']['tmp_name']);
        if($imageFileType !== IMAGETYPE_PNG && $imageFileType !== IMAGETYPE_JPEG && $imageFileType !== IMAGETYPE_GIF) {
            $response[0].= "ERR: only JPG, JPEG, PNG & GIF files are allowed.";
            $allowUpload = false;
        }

        // Finally check if we can upload file
        if (!$allowUpload) {
            $response[0].= "ERR: File is not allowed to be uploaded.";
        } else {
            if (move_uploaded_file($image["file"]["tmp_name"], $targetFile)) {
                $response[0].= "Uploaded successfully to: " . $targetFile;

                // Insert into Database
                if(mysqli_query($con, $query)){
                    $response[0].= "Uploaded successfully to database.";

                    // Set target file to final response array
                    $response[1] = $targetFile;
                } else{
                    $response[0].= "ERR: File could not be inserted to database.";
                }
            } else {
                $response[0].= "ERR: Uploading file failed.";
            }

            mysqli_close($con);
        }

    } else {
        $response[0].= "ERR: There is no file.";
    }

} else {
    $response[0].= "ERR: Not a post request.";
}

echo json_encode($response);
?>
