<?php 
require_once "admin_includes/header.php";
require_once '../vendor/autoload.php'; 
require_once "functions.php";
require_once "admin_includes/navigation.php"; 
$error_msg = "";

$dotenv = Dotenv\Dotenv::create('../');
$dotenv->load();

$secret = getenv('MASS_PASS'); 

  // <!--delete comment query   -->
   
  if(isset($_POST['delete']))
  {
      $master_password = R_E_S($_POST['master_password']);
      $master_password_confirm = R_E_S($_POST['master_password_confirm']);
    
   
      if($master_password !== $master_password_confirm)
      {
        $error_msg = 'Passwords do not match!';
      }

      if($master_password !== $secret)
      {
        $error_msg = 'Wrong master password!';
      }
      if(empty($master_password_confirm))
      {
        $error_msg = 'Password can not be left empty!';
      }
      if(empty($master_password))
      {
        $error_msg = 'Password can not be left empty!';
      }
      else if(empty($error_msg))
      {
            $delete = R_E_S($_POST['id_to_delete']);
          
            $delete_query = "DELETE FROM comments WHERE comment_id = ? ";
                  
            $stmt1 = mysqli_stmt_init($connection);
      
            if(!mysqli_stmt_prepare($stmt1,$delete_query) )
            {
              die('stmt1 prepare failed'.mysqli_error($connection));
            }
            
            // bind
            if(!mysqli_stmt_bind_param($stmt1,"i",$delete))
            {
              die("stmt1 bind failed".mysqli_error($connection));
            }
                  
            // execute
            if(!mysqli_stmt_execute($stmt1))
            {
              die('stmt1 execute failed '.mysqli_error($connection));
            }
            else
            {
                mysqli_stmt_close($stmt1);
              
                header("Location: user_comments.php");
            }   
      
      }  
  }


  // change comment status via post   
  if(isset($_POST['change_status_submit']))
  {
      $master_password = R_E_S($_POST['master_password']);
      $master_password_confirm = R_E_S($_POST['master_password_confirm']);
    
      if($master_password !== $master_password_confirm)
      {
        $error_msg = 'Passwords do not match!';
      }

      if($master_password !== $secret)
      {
        $error_msg = 'Wrong master password!';
      }

      if(empty($master_password_confirm))
      {
        $error_msg = 'Password can not be left empty!';
      }
      if(empty($master_password))
      {
        $error_msg = 'Password can not be left empty!';
      }
      
      else if(empty($error_msg))
      {
        
          $id = R_E_S($_POST['id']);
          $status = R_E_S($_POST['status']);

          $status_query = "UPDATE comments SET comment_status = ? WHERE comment_id = ? ";
                
          $stmt2 = mysqli_stmt_init($connection);

          if(!mysqli_stmt_prepare($stmt2,$status_query) )
          {
              die('stmt2 prepare failed'.mysqli_error($connection));
          }
        
          if(!mysqli_stmt_bind_param($stmt2,"si",$status,$id))
          {
            die("stmt2 bind failed".mysqli_error($connection));
          }
          
          if(!mysqli_stmt_execute($stmt2))
          {
            die('stmt2 execute failed '.mysqli_error($connection));
          }

          mysqli_stmt_close($stmt2);
          
          // plus one to total comment count 
          if($status == "approved")
          {
                  $stmt3 = mysqli_stmt_init($connection);

                  $comment_count_query = "UPDATE posts SET post_comment_count = post_comment_count + 1 WHERE post_id = ? ";
                  
                  if(!mysqli_stmt_prepare($stmt3,$comment_count_query) )
                  {
                      die('stmt3 prepare failed'.mysqli_error($connection));
                  }
                  
                  if(!mysqli_stmt_bind_param($stmt3,"i",$relating_to_post))
                  {
                      die("stmt2 bind failed".mysqli_error($connection));
                  }
                  
                  if(!mysqli_stmt_execute($stmt3))
                  {
                      die('stmt2 execute failed '.mysqli_error($connection));
                  }

                  mysqli_stmt_close($stmt3);

                  header("Location: admin_comments.php");
          }
          

            // minus one from total comment count 
          if($status == "denied")
          {
                $stmt4 = mysqli_stmt_init($connection);

                $comment_count_query2 = "UPDATE posts SET post_comment_count = post_comment_count - 1 WHERE post_id = ? ";
                
                if(!mysqli_stmt_prepare($stmt4,$comment_count_query2) )
                {
                    die('stmt4 prepare failed'.mysqli_error($connection));
                }
                
                if(!mysqli_stmt_bind_param($stmt4,"i",$relating_to_post))
                {
                    die("stmt4 bind failed".mysqli_error($connection));
                }
                
                if(!mysqli_stmt_execute($stmt4))
                {
                    die('stmt4 execute failed '.mysqli_error($connection));
                }

                mysqli_stmt_close($stmt4);
                
                header("Location: admin_comments.php");
                
          }

      }

  }

?>


<div class="container-fluid" id="main_contain">

  <!-- Page Heading -->
  <div class="row">

    <?php include_once "admin_includes/sidebar.php" ?>
    <div class="col-sm-12 col-md-7">
      <h1 class="page-header" id="login_head"> Admin Comments </h1>
               
        <!-- show all comments table  -->
        <div class="col-xs-12">
          <h4 class="text-center"> All Comments on my posts  </h4>
            <table class="table table-bordered table-hover">
              <thead>
                  <th>ID</th>
                  <th>Relating to Post</th>
                  <th>Author</th>
                  <th>Email</th>
                  <th>Content</th>
                  <th>Date</th>
                  <th>Status</th>
                  <th>Delete</th>
              </thead>
              
              <tbody>
                <?php
                  $username = $_SESSION['username'];

                  $query = "SELECT * FROM comments LEFT JOIN posts ON post_id = comment_post_id WHERE post_author = '$username' ";
                  $result = mysqli_query($connection,$query);
                  if(!$result)
                  {
                    die('result query failed'.mysqli_error($connection));
                  }
               
                  while($row = mysqli_fetch_assoc($result))
                  {
                      $comment_id = $row['comment_id'];
                      $relating_to_post = $row['comment_post_id'];
                      $comment_author = $row['comment_author'];
                      $comment_email = $row['comment_email'];
                      $comment_content = $row['comment_content'];
                      $comment_date = $row['comment_date'];
                      $comment_status = $row['comment_status'];
                      $comment_date = date('d-m-y');
                    ?>
                    <tr>

                        <td><?php echo $comment_id ?> </td>
              
                        <?php 
                        $post_query = "SELECT * FROM posts WHERE post_id = ? "; 
                          
                        $stmt = mysqli_stmt_init($connection);

                        if(!mysqli_stmt_prepare($stmt,$post_query) )
                        {
                          die('stmt prepare failed'.mysqli_error($connection));
                        }
            
                        if(!mysqli_stmt_bind_param($stmt,"i",$relating_to_post))
                        {
                          die("stmt bind failed".mysqli_error($connection));
                        }
                
                        if(!mysqli_stmt_execute($stmt))
                        {
                          die('stmt execute failed '.mysqli_error($connection));
                        }

                        $post_result = mysqli_stmt_get_result($stmt);
                        if(!$post_result)
                        {
                          die("post stmt result failed". mysqli_error($connection));
                        }
                 
                        while($row = mysqli_fetch_assoc($post_result))
                        {
                            $post_title = $row['post_title'];  
                            $post_image =  $row['post_image'];                
                            $post_status =  $row['post_status']; 
                            
                            ?>

                            <!-- only let users view commented on post if the post has been published  -->
                            <td> 
                               <?php echo $post_title ; 
                                      if($post_status === 'published')
                                      { ?>
                                          <a href="../post.php?post=<?php echo $relating_to_post ?>"> 
                                             <img width="100px" height="90px"src="../images/posts/<?php echo $post_image ?> " > 
                                          </a> <?php 
                                      }    
                                      else
                                      { ?>
                                          <a> 
                                              <img width="100px" height="90px"src="../images/posts/<?php echo $post_image ?> " >  
                                          </a> <?php } ?> 
                            </td> 

                          <?php
                                mysqli_stmt_close($stmt);
                        } ?>
                      
                            <td> <?php echo  $comment_author; ?> </td>
                 
                            <td> <?php echo  $comment_email;  ?> </td>
                
                            <td> <?php echo  $comment_content;  ?> </td>
                
                            <td> <?php echo  $comment_date; ?> </td>
                 
                            <td> 
                                <?php echo  $comment_status; ?> <br> 
                
                                <?php 
                                if($comment_status == 'denied')
                                { ?>
                                      <button type="button" data="approved" value="<?php echo $comment_id ?>" class="text text-success change_status_modal_btn">Approve</button>
                                <?php 
                                } 
                                else
                                { ?>
                                      <button type="button" data="denied" value="<?php echo $comment_id ?>" class="text text-danger change_status_modal_btn">Deny</button>
                                  <?php 
                                } ?>
                                
                            </td> 

                            <td>
                                <button type="button" value="<?php echo $comment_id ?>" class="text text-danger delete_modal_btn">Delete</button>
                            </td> 

                    </tr>
                   <?php 
                  } ?>
              </tbody>
          </table>
          <?php 
          if(!empty($error_msg))
          { ?>
            <h2>  <div class="alert-danger text-danger text-center"> <?php echo $error_msg ?> </div>  </h2><?php
          } ?>   
        </div>


        <div class="col-xs-12">
          <h4 class="text-center"> Comments i have made on other peoples posts </h4>
          
          <table class="table table-bordered table-hover">
            <thead>
                <th>ID</th>
                <th>Relating to Post</th>
                <th>Author</th>
                <th>Email</th>
                <th>Content</th>
                <th>Date</th>
                <th>Status</th>
                <th>Delete</th>
            </thead>
              
            <tbody>
                <?php 
                $query = "SELECT * FROM comments WHERE comment_author = '$username' ";
                $result = mysqli_query($connection,$query);
                if(!$result)
                {
                  die('result query failed'.mysqli_error($connection));
                }
               
                while($row = mysqli_fetch_assoc($result))
                {
                    $comment_id = $row['comment_id'];
                    $relating_to_post = $row['comment_post_id'];
                    $comment_author = $row['comment_author'];
                    $comment_email = $row['comment_email'];
                    $comment_content = $row['comment_content'];
                    $comment_date = $row['comment_date'];
                    $comment_status = $row['comment_status'];
                    $comment_date = date('d-m-y');
                    ?>
                  
                  <tr>

                      <td> <?php echo $comment_id; ?> </td>
                  
                        <?php 
                          $post_query = "SELECT * FROM posts WHERE post_id = ? "; 
                          
                          $stmt = mysqli_stmt_init($connection);

                          if(!mysqli_stmt_prepare($stmt,$post_query) )
                          {
                            die('stmt prepare failed'.mysqli_error($connection));
                          }
              
                          if(!mysqli_stmt_bind_param($stmt,"i",$relating_to_post))
                          {
                            die("stmt bind failed".mysqli_error($connection));
                          }
                  
                          if(!mysqli_stmt_execute($stmt))
                          {
                            die('stmt execute failed '.mysqli_error($connection));
                          }

                          $post_result = mysqli_stmt_get_result($stmt);
                          if(!$post_result)
                          {
                            die("post stmt result failed". mysqli_error($connection));
                          }
                        
                    
                      while($row = mysqli_fetch_assoc($post_result))
                      {
                        $post_title = $row['post_title'];  

                        $post_image =  $row['post_image'];

                        $post_status =  $row['post_status'];  ?>

                              <!-- only let users view commented on post if the post has been published  -->
                        <td>  
                            <?php echo $post_title; 
                            if($post_status === 'published')
                            { ?>
                                <a href="../post.php?post=<?php echo $relating_to_post ?>"> 
                                    <img width="100px" height="90px"src="../images/posts/<?php echo $post_image ?> " > 
                                </a> <?php 
                            }    
                            else
                            { ?> 
                                <a> 
                                    <img width="100px" height="90px"src="../images/posts/<?php echo $post_image ?> " >  
                                </a> <?php 
                            } ?> 
                        </td>

                        <?php mysqli_stmt_close($stmt);
                      } ?>
                      
                        <td> <?php echo  $comment_author; ?> </td>

                        <td> <?php echo  $comment_email;  ?> </td>

                        <td> <?php echo  $comment_content;  ?> </td>

                        <td> <?php echo  $comment_date; ?> </td>
                        
                        <td> 
                            <?php echo  $comment_status; ?> <br> 
                        
                            <?php 
                            if($comment_status == 'denied')
                            { ?>
                                <button type="button" data="approved" value="<?php echo $comment_id ?>" class="text text-success change_status_modal_btn">Approve</button>
                              <?php 
                            } 
                            else
                            { ?>
                                <button type="button" data="denied" value="<?php echo $comment_id ?>" class="text text-danger change_status_modal_btn">Deny</button>
                              <?php 
                            } ?>

                        </td> 
      
                        <td>
                                <button type="button" value="<?php echo $comment_id ?>" class="text text-danger delete_modal_btn">Delete</button>
                        </td> 
                      
                  </tr>
                
                  <?php

                } ?>
            </tbody>
          </table>
          <?php 
          if(!empty($error_msg))
          { ?>
            <h2>  <div class="alert-danger text-danger text-center"> <?php echo $error_msg ?> </div>  </h2><?php
          } ?>  
        </div>
      </div>

      <?php include_once "admin_includes/footer.php"; ?>
      <?php include_once "delete_comment_modal.php"; ?>
      <?php include_once "change_status_modal.php"; ?>
    </div>
  </div>
</div>

                        
        
