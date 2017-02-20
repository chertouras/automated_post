<?php
/**************************************************************************

server_file_list.php: A server side component used to create the server files list.
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
        $array_dots  = array(
            '..',
            '.'
        );
        // $array_exclude is an array contained in parameters.php
        $files       = array_diff(scandir($dir), array_merge($array_dots, $array_exclude));
        $changed_dir = chdir($dir);
        $in_charset  = 'ISO-8859-7';
        if (!empty($files)) {
            /*
            calculate the md5 hash of each file of the directory. 
            Update the files array and use as index the hash value		
            
            */
            foreach ($files as $key => $value) {
                $ctx          = hash_init('md5');
                $result       = hash_update_file($ctx, ($value));
                $value        = iconv($in_charset, 'UTF-8', $value);
                $files[$key]  = $value;
                $result       = hash_update($ctx, $value);
                $hash         = hash_final($ctx);
                $files[$hash] = $files[$key];
                unset($files[$key]);
            } //$files as $key => $value
        } //if (!empty($files))
        /*Json encode the array and send back to python for it to be parsed. Keep the contents in a file (server_files.json)*/
        $files_json_enc = json_encode($files, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        file_put_contents("server_files.json", ($files_json_enc));
        print_r($files_json_enc);
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