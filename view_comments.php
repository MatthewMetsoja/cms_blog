<?php 
require_once "includes/header.php"; 
require_once 'vendor/autoload.php'; 
require_once "includes/navigation.php";  

 // delete query           
 if(isset($_POST['delete']))
 {
     $delete = R_E_S($_POST['comment_id']);
       
     $stmt_del = mysqli_stmt_init($connection);
 
     $delete_query = "DELETE FROM comments WHERE comment_id = ? ";
 
     if(!mysqli_stmt_prepare($stmt_del,$delete_query))
     {
         die('del stmt prep failed'.mysqli_error($connection));
     }
 
     if(!mysqli_stmt_bind_param($stmt_del,'s',$delete))
     {
         die('del stmt bind failed'.mysqli_error($connection));
     }
 
     if(!mysqli_stmt_execute($stmt_del))
     {
         die('stmt del execute failed'.mysqli_error($connection));
     }

         mysqli_stmt_close($stmt_del);
         header("Location: admin/admin_posts.php");
       
 } 

?>
    

<div class="container-fluid"  id="main_contain">

    <div class="row">
      
        <h4 class="login_head" id="login_head" > Comments </h4>
        
        <div class="col-lg-1 hidden-md" ></div>
            
        <div class="col-md-7" id="main_div">

            <?php
             // validation only do below if logged in as admin if not redirect to admin 
            if(isset($_SESSION['role']) && $_SESSION['role'] !== '')
            {
                    if(isset($_GET['comment_post_id']))
                    {
                        $get_comment_post_id = R_E_S($_GET['comment_post_id']);
                        $approved = R_E_S('approved');

                        $stmt = mysqli_stmt_init($connection);
                        $view_comments_query = "SELECT * FROM comments LEFT JOIN users ON user_name = comment_author WHERE 
                        comment_status = ? AND comment_post_id = ? ";
                        
                        if(!mysqli_stmt_prepare($stmt,$view_comments_query))
                        {
                            die('stmt prep failed'.mysqli_error($connection));
                        }
                    
                        if(!mysqli_stmt_bind_param($stmt,'ss',$approved,$get_comment_post_id))
                        {
                            die('stmt bind failed'.mysqli_error($connection));
                        }

                        if(!mysqli_stmt_execute($stmt))
                        {
                            die('stmt execute failed'.mysqli_error($connection));
                        }

                        $view_comments_result = mysqli_stmt_get_result($stmt);
                        
                        if(!$view_comments_result)
                        {
                            die('stmtget result failed'.mysqli_error($connection));
                        }
            
                        while($row_comments = mysqli_fetch_assoc($view_comments_result))
                        {
                            $db_id = $row_comments['comment_id'];
                            $db_post_id = $row_comments['comment_post_id'];
                            $db_author = $row_comments['comment_author'];
                            $db_email = $row_comments['comment_email'];
                            $db_date = $row_comments['comment_date'];
                            $db_comment_email = $row_comments['comment_email'];
                            $db_comment = $row_comments['comment_content']; 
                            $db_image = $row_comments['user_image']; ?>
                            

                            <div class="media">
                                    
                                    <a id="my_comments" class="pull-left">
                                        <img class="media-object" src="images/users/<?php echo $db_image ?>"  width="100px" height="100px" >
                                    </a>
                                    
                                    <div id="my_comments" class="media-body">
                                        <h4 id="my_comments class="media-heading"> <?php echo $db_author ?> <br>
                                            <small><?php echo $db_date ?></small>
                                        </h4>
                                        <?php echo $db_comment; ?> <br>
                                        
                                        <?php if($_SESSION['role'] == 'admin' )             //only let admin delete comments or see the delete button
                                        { ?>   
                                            <form action="" method="post" onsubmit="return confirm('ARE YOU SURE YOU WANT TO DELETE?')">
                                                <td> 
                                                    <input type="hidden" name="comment_id" value="<?php echo $db_id ?>">
                                                    <button type="submit" name="delete" class="text text-danger" > Delete</button>
                                                </td>  
                                            </form> <?php 
                                        } ?>
                                        
                                    
                                    </div>
                        
                            </div>
            
                            <hr class="hr_big"> <?php   
                        } 
                        mysqli_stmt_close($stmt);
                    } 
            }
            else // if the user does not have admin role then redirect them to homepage
            {  
                header("Location:index.php");
            } ?>        
                          
   
        </div>  <!--    <div class="col-md-7">  -->

    <!-- Blog Sidebar Widgets Column -->
    <?php require_once "includes/sidebar.php" ?> 

    <hr>

    <!-- Footer -->
    <?php require_once "includes/footer.php" ?> 