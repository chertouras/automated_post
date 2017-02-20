<?php
/*************************************************************************
upload.php: A server side component used to upload files to server through the HTTP Post.
Not intented to be used standalone.
Programmed by: Konstantinos Chertouras - chertour@gmail.com
************************************************************************/
header('Content-Type: text/html; charset=ISO-8859-7');
require 'parameters.php';
$username = $password = $userError = $passError = '';
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    if ($username === $username_parameters && $password === $password_parameters) {
        if (isset($_POST['size']) && !empty($_POST['size'])) {
            if ($_POST['size'] > intval($maxsize)) {
                print_r('Exceeded filesize limit.');
                exit;
            }
        }
        if (isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])) {
            $name       = (urldecode($_FILES['file']['name']));
            $enc_name   = $_FILES['file']['name'];
            $file_array = explode(".", $name);
            if (count($file_array) !== 2) {
                echo "No double extensions or unsafe filenames with dots allowed for security reasons.";
                exit;
            }
            if (count($file_array) === 2) {
                if (!in_array(pathinfo($name, PATHINFO_EXTENSION), $valid_exts)) {
                    echo "This type of files is not allowed to upload.";
                    exit;
                }
            }
            $size = $_FILES['file']['size'];
            if ($_FILES['file']['size'] > intval($maxsize)) {
                print_r('Exceeded filesize limit.');
                exit;
            }
            $type      = $_FILES['file']['type'];
            $tmp_name  = ($_FILES['file']['tmp_name']);
            $error     = $_FILES['file']['error'];
            $timestamp = $_POST['timestamp'];
            $uploaddir = $dir . '/'; //entering into the directory ./files_to_transfer/ WE NEED THE TRAILING SLASH (/)
            /***************************************************************/
            setlocale(LC_ALL, 'el_GR'); //NECESSARY IN MY TEST CASES TO PRINT CORRECTLY THE GREEK LETTERS
            /***************************************************************/
            $uploadfile = (($uploaddir . basename($name)));
            
            if (move_uploaded_file($tmp_name, $uploadfile)) {
                echo "\nThe file has been uploaded successfully\n";
            } else {
                echo "There was an error uploading the file";
            }
            if (!touch($uploadfile, $timestamp)) {
                echo 'No date updated...';
            } else {
                echo 'Touched file with success';               
            }
        } //if(isset($_FILES['file']['name']) && !empty($_FILES['file']['name']))
    } // if($username === $username_parameters && $password === $password_parameters)
    else {
        print_r('Unauthorized access');
        exit();
    }
} //if(isset($_POST['username']) && isset($_POST['password'])  )
else {
    print("You have not  supplied any credentials... ");
    print_r('  Exiting...');
    exit();
}
?>