<?php
if($_FILES["upload_file"]["name"] != '')
{
    $data = explode(".", $_FILES["upload_file"]["name"]);
    $extension = $data[1];
    $allowed_extension = array("zip", "rar", "txt", "pdf");
    if(in_array($extension, $allowed_extension))
    {
        $new_file_name = $_FILES["upload_file"]["name"];
        $path = $_POST["hidden_folder_name"] . '/' . $new_file_name;
        if(move_uploaded_file($_FILES["upload_file"]["tmp_name"], $path)) {
            echo 'file Uploaded';
        }else{
           echo 'There is some error';
        }
    }else{
        echo 'Invalid File';
    }
}else{
    echo 'Please Select File';
}