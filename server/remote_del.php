<?php
/**************************************************************************

remote_del.php: A server side component used to delete files from server
Not intented to be used standalone.
Programmed by: Konstantinos Chertouras - chertour@gmail.com
************************************************************************/
/*
Most important!!! A header about utf-8 charset is essential!!
*/
header('Content-Type: text/html; charset=utf-8');
require 'parameters.php';
/*
Allow access only if username and password are supplied. 
These values are kept in parameters.php
*/
$username = $password = $userError = $passError = '';
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if ($username === $username_parameters && $password === $password_parameters) {
        $out_charset   = 'ISO-8859-7';
        $hash_filename = $_POST['hash_filename'];
        $jsondata      = file_get_contents($dir . "/server_files.json");
        $server        = json_decode($jsondata, true);
        $value         = $server[$hash_filename];
        $conv_value    = iconv('UTF-8', $out_charset, $value);
        if ($value != NULL) {
            $del_result = unlink($dir . '/' . $conv_value);
            if ($del_result) {
                print_r($value . " succesfully deleted from Server");
                
            } //$del_result
            else {
                print_r($value . " NOT DELETED");
            }
        } //$value != NULL
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