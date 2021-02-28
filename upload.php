
<?php

const MAX_FILE_SIZE = 5000000;
const IMAGE_DIR = 'i';

if (!is_dir(IMAGE_DIR)) {
    mkdir(IMAGE_DIR, 0777, true);
    return;
}

function upload_image($type) {
    $target_file = IMAGE_DIR . '/' . basename($_FILES[$type]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES[$type]["tmp_name"]);
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
    if ($_FILES[$type]["size"] > MAX_FILE_SIZE) {
        echo "Sorry, your file is too large. (" . $_FILES[$type]["size"] . "/" . MAX_FILE_SIZE . ")";
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
        if (move_uploaded_file($_FILES[$type]["tmp_name"], $target_file)) {
            $fileName = basename($_FILES[$type]["name"]);
            $fileName = htmlspecialchars($fileName);
            echo 'The file <a href="/' . IMAGE_DIR . '/' . $fileName . '">' . htmlspecialchars($fileName) . '</a> has been uploaded.';
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

if ($_FILES['form-file']) {
    echo "form '" . $_FILES['form-file']['name'] . "'";
    upload_image('form-file');
} else if ($_FILES['file']) {
    upload_image('file');
    echo "file '" . $_FILES['file']['name'] . "'";
}

?>

<a href="index.php">back</a>
