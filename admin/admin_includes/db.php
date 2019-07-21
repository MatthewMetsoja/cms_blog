<?php   
ob_start();

define("HOST","localhost");
define("USER","root");
define("PW",'root');
define("DB","cms");

$connection = mysqli_connect(HOST,USER,PW,DB);
if(!$connection)
{
    die('connection failed'.mysqli_error($connection));
}

mysqli_set_charset($connection,"utf8");

