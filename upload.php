<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php

const MAX_FILE_SIZE = 5000000;
const IMAGE_DIR = 'i';

if (!is_dir(IMAGE_DIR)) {
    mkdir(IMAGE_DIR, 0777, true);
    return;
}

function verbose_error() {
    echo '<code style="white-space: pre;">';
    $inipath = php_ini_loaded_file();
    if ($inipath) {
        echo 'Loaded php.ini: ' . $inipath . '<br>';
    } else {
        echo 'A php.ini file is not loaded<br>';
    }
    echo 'upload_max_filesize: ' . ini_get('upload_max_filesize') . '<br>';
    echo 'post_max_size: ' . ini_get('post_max_size') . '<br>';
    print_r($_FILES['file']);
    echo '</code>';
}

function dbg($msg) {
    echo "<div>[DEBUG] $msg</div>";
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
        dbg("tmp name: " . $_FILES['file']["tmp_name"]);
    } else {
        dbg("submit is empty");
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
    dbg("file size: " . $_FILES['file']["size"]);

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }
    dbg("file type: " + $imageFileType);

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo 'Sorry, your file was not uploaded.<br>';
        verbose_error();
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES['file']["tmp_name"], $target_file)) {
            $fileName = basename($_FILES['file']["name"]);
            $fileName = htmlspecialchars($fileName);
            echo 'The file <a href="/' . IMAGE_DIR . '/' . $fileName . '">' . htmlspecialchars($fileName) . '</a> has been uploaded.';
        } else {
            echo "Sorry, there was an error uploading your file.";
            verbose_error();
        }
    }
}

upload_image();

?>
    <a href="index.php">back</a>
</body>
</html>
