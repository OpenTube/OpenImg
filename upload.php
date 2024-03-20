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

// returns 1 on ok
// returns 0 on error
function check_if_valid_image() {
    if(isset($_POST["submit"])) {
        $tmp_img_name = $_FILES['file']["tmp_name"];
        if (!$tmp_img_name) {
            dbg('$_FILES:');
            print_r($_FILES);
            dbg("warning file tmp_name not found!");
            dbg("using 'name' instead ...");
            $tmp_img_name = $_FILES['file']["name"];
            if(!$tmp_img_name) {
                dbg("Error: name not found either");
                return 0;
            }
        }
        $check = getimagesize($tmp_img_name);
        if($check !== false) {
            dbg("File is an image - " . $check["mime"] . ".");
            return 1;
        } else {
            dbg("File is not an image.");
            return 0;
        }
        dbg("tmp name: " . $tmp_img_name);
    } else {
        dbg("submit is empty");
    }
    return 1;
}

function upload_image() {
    if (!$_FILES['file']) {
        return;
    }
    $target_file = IMAGE_DIR . '/' . basename($_FILES['file']["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    // Check if image file is a actual image or fake image
    $uploadOk = check_if_valid_image();

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
    dbg("file type: " . $imageFileType);

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
