<?php 

function openConnection(){
    $host = 'localhost';
    $dbUser = "root";
    $password = "mysql";
    $dbname = "company";

    $cn = new mysqli($host, $dbUser, $password, $dbname);
    if($cn->connect_error){
        error_log("Connection error" . $cn->connect_errno);
    }
    return $cn;
}

$cn = openConnection();
?>