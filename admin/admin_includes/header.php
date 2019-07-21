<?php 
session_start();
ob_start();

require_once "db.php";
require_once "functions.php";

if(!isset($_SESSION['id']) || $_SESSION['id'] === '' )
{
    header("Location:../index.php");    
}    
?>
 
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

        <title>CMS Admin</title>
           
            <!-- jQuery -->
            <script src="js/jquery.js"></script>

            <!-- my script -->
            <script src="js/script.js"></script>

            <!-- pusher -->
            <script src="https://js.pusher.com/4.3/pusher.min.js"></script>

            <!-- toastr notifications -->
            <link href=https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script> 
        
            <!-- Bootstrap Core CSS -->
            <link href="css/bootstrap.min.css" rel="stylesheet">

             <!-- Custom CSS -->
            <link href="css/sb-admin.css" rel="stylesheet">

            <!-- Custom Fonts -->
            <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

            <!-- MY CSS -->
            <link href="css/styles.css" rel="stylesheet">

            <!-- CK TEXT EDITOR -->
            <script src="https://cdn.ckeditor.com/ckeditor5/11.2.0/classic/ckeditor.js"></script>
            
           <!-- CHART JS -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>

            <!-- GOOGLE FONTS -->
            <link href="https://fonts.googleapis.com/css?family=Indie+Flower" rel="stylesheet">
            <link href="https://fonts.googleapis.com/css?family=Righteous" rel="stylesheet">
            <link href="https://fonts.googleapis.com/css?family=Bangers" rel="stylesheet">
            <link href="https://fonts.googleapis.com/css?family=Orbitron" rel="stylesheet"> 
            <link href="https://fonts.googleapis.com/css?family=BioRhyme" rel="stylesheet">
            <link href="https://fonts.googleapis.com/css?family=Caveat" rel="stylesheet">

            <!-- Bootstrap Core JavaScript -->
            <script src="js/bootstrap.min.js"></script>

    
</head>


<body>

  