
<?php

const MAX_FILE_SIZE = 5000000;
const IMAGE_DIR = 'i';

if (!is_dir(IMAGE_DIR)) {
    mkdir(IMAGE_DIR, 0777, true);
    return;
}

function upload_image() {
    if (!$_FILES['file']) {
        return;
    }
    $target_file = IMAGE_DIR . '/' . basename($_FILES['file']["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES['file']["tmp_name"]);
        if($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES['file']["size"] > MAX_FILE_SIZE) {
        echo "Sorry, your file is too large. (" . $_FILES['file']["size"] . "/" . MAX_FILE_SIZE . ")";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES['file']["tmp_name"], $target_file)) {
            $fileName = basename($_FILES['file']["name"]);
            $fileName = htmlspecialchars($fileName);
            echo 'The file <a href="/' . IMAGE_DIR . '/' . $fileName . '">' . htmlspecialchars($fileName) . '</a> has been uploaded.';
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

upload_image();

?>

<a href="index.php">back</a>
