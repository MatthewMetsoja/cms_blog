<?php
require_once "admin_includes/header.php"; 
require_once '../vendor/autoload.php'; 
require_once "functions.php";
require_once "admin_includes/navigation.php";

$dotenv = Dotenv\Dotenv::create('../');
$dotenv->load();

$secret = getenv('MASS_PASS'); 

$error_msg = "";

if($_SESSION['role'] === 'user')
{
   header('location: view_all_posts_user.php'); 
}
?>

    <div class="container-fluid" id="main_contain">

        <div class="row">
            <?php include_once "admin_includes/sidebar.php" ?>
         
            <div class="col-sm-12 col-md-7">
                <h1 class="page-header" id="login_head">  Admin Users  </h1>
                        
                <?php 

                if(isset($_GET['source']))
                {
                    $source = R_E_S($_GET['source']);
                }
                else
                { 
                    $source = '';         
                }            

                switch($source)
                {
                    case 'add_user';
                    include 'admin_includes/add_user.php';   
                    break;

                    case 'edit_user';
                    include 'admin_includes/edit_user.php';   
                    break;

                    default: include 'admin_includes/view_all_users.php';

                }    
                ?>  
            </div>
              
        </div>
       
<?php include_once "admin_includes/footer.php"; ?>
<?php include_once "delete_user_modal.php"; ?>
<?php include_once "change_status_modal.php"; ?>