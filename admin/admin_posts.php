<?php 
require_once "admin_includes/header.php" ;
require_once '../vendor/autoload.php'; 
require_once "functions.php";
require_once "admin_includes/navigation.php"; 
?>
  
         

<div class="container-fluid" id="main_contain">

            
    <div class="row">
    <?php include_once "admin_includes/sidebar.php" ?>
            
        <div class="col-sm-12 col-md-7">
            <h1 class="page-header" id="login_head" > Admin Posts </h1>
                    
                                    
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
             case 'add_post';
             include 'admin_includes/add_post.php';   
             break;

             case 'edit_post';
             include 'admin_includes/edit_post.php';   
             break;

             default: include 'admin_includes/view_all_posts.php';
            }           

                 
                if(!empty($message))
                { ?>
                        <h4 class="alert alert-danger"> <?php echo $message ?> </h4>  <?php 
                }  ?>
        </div>
            

    <?php include_once "admin_includes/footer.php"; ?>
    <?php include_once "delete_post_modal.php"; ?>
    <?php include_once "change_status_modal.php"; ?>

