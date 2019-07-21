<?php ob_start() ?> 
<?php session_start() ?> 
<?php

unset($_SESSION);
session_destroy();

header("Location: ../index.php");
          





 



