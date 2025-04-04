<?php
// <!-- // gets file from upload -->
$fileName = $_FILES['file']['name'];


// <!-- concat into with location && $_Files -->
$Location = "medcertsUp/". $fileName;

if ( move_uploaded_file($_FILES['file']['tmp_name'], $Location)) {
    echo "Uploaded successfully.";
} else {
    echo "Failed to upload file.";
}

?>