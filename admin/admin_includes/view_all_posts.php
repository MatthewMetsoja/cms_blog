 <?php
session_start();
include '../vendor/autoload.php'; 
$dotenv = Dotenv\Dotenv::create('../');
$dotenv->load();

$secret = getenv('MASS_PASS'); 

$username = $_SESSION['username'];

if($_SESSION['role'] === 'user')
{
   header('location: view_all_posts_user.php'); 
} 

$error_msg = "";


// querys 

  // delete post in modal query  
  if(isset($_POST['delete_post_submit']))
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
         // get post_id via js in the value of hidden input when we click delete button 
          $delete = R_E_S($_POST['id_to_delete']) ;

          // delete from posts
          $delete_query = "DELETE FROM posts WHERE post_id = ? ";
                        
          $stmt7 = mysqli_stmt_init($connection);
          if(!mysqli_stmt_prepare($stmt7,$delete_query))
          {
            die('prepare stmt7 failed'.mysqli_error($connection));    
          }
          if(!mysqli_stmt_bind_param($stmt7,"i",$delete))
          {
            die('bind stmt7 failed'.mysqli_error($connection));    
          }
          if(!mysqli_stmt_execute($stmt7))
          {
            die('execute stmt 7 failed'.mysqli_error($connection));
          }
          else
          {
             mysqli_stmt_close($stmt7);
          }
              
          // delete from comments
             $delete_comments_query = "DELETE FROM comments WHERE comment_post_id = ? ";
             $stmt8 = mysqli_stmt_init($connection);
             
             if(!mysqli_stmt_prepare($stmt8,$delete_comments_query))
             {
              die('prepare stmt8 failed'.mysqli_error($connection));    
             }
             if(!mysqli_stmt_bind_param($stmt8,"i",$delete))
             {
              die('bind stmt8 failed'.mysqli_error($connection));    
             }
             if(!mysqli_stmt_execute($stmt8))
             {
               die('execute stmt 8 failed'.mysqli_error($connection));
             }
             else
             {
               mysqli_stmt_close($stmt8);
             }
             
             // delete from likes also 
               $delete_likes_query = "DELETE FROM likes WHERE post_id = ? ";
               $stmt9 = mysqli_stmt_init($connection);
              if(!mysqli_stmt_prepare($stmt9,$delete_likes_query))
              {
                die('prepare stmt9 failed'.mysqli_error($connection));    
              }
              if(!mysqli_stmt_bind_param($stmt9,"i",$delete))
              {
                die('bind stmt9 failed'.mysqli_error($connection));    
              }
              if(!mysqli_stmt_execute($stmt9))
              {
                die('execute stmt 9 failed'.mysqli_error($connection));
              }
              else
              {
                mysqli_stmt_close($stmt9);
                header("Location: admin_posts.php");
              }
      }
  }




      
    // change post status on click 
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

            $status_query = "UPDATE posts SET post_status = ? WHERE post_id = ? ";
                            
            $stmt8 = mysqli_stmt_init($connection);
            if(!mysqli_stmt_prepare($stmt8,$status_query))
            {
              die('prepare stmt8 failed'.mysqli_error($connection));    
            }
                
            if(!mysqli_stmt_bind_param($stmt8,"si",$status,$id))
            {
              die('bind stmt8 failed'.mysqli_error($connection));    
            }
                
            if(!mysqli_stmt_execute($stmt8))
            {
              die('execute stmt8 failed'.mysqli_error($connection));
            }
            else
            { 
                mysqli_stmt_close($stmt8);
                header("Location: admin_posts.php");
            }

        }
    }
    
    if(isset($_POST['view_submit']))
    {

        $reset_views = $_POST['views_id'];

        $zero = 0;

        $reset_view_query = "UPDATE posts SET post_views = ? WHERE post_id = ? ";
        
         $stmt9 = mysqli_stmt_init($connection);
            if(!mysqli_stmt_prepare($stmt9,$reset_view_query))
            {
              die('prepare stmt9 failed'.mysqli_error($connection));    
            }

            if(!mysqli_stmt_bind_param($stmt9,"ii",$zero,$reset_views))
            {
              die('bind stmt9 failed'.mysqli_error($connection));    
            }

            if(!mysqli_stmt_execute($stmt9))
            {
              die('execute stmt9 failed'.mysqli_error($connection));
            }
            else
            { 
              mysqli_stmt_close($stmt9);
              header("Location: admin_posts.php");
            }

    }


   

?>

          <!-- /////////////////// ALL POSTS BY OTHER USERS TABLE 1/////////////////// -->    
  <div class="col-7">
      <?php 
        if(!empty($error_msg))
        { ?>
          <h2>  <div class="alert-danger text-danger text-center"> <?php echo $error_msg ?> </div>  </h2><?php
        } 
      ?>   
      <h4> Posts by other users </h4>
        <table class="table table-bordered table-hover">
        
          <thead>

              <th>Id</th>
              <th>Category</th>
              <th>Title</th>
              <th>Author/Username</th>
              <th>Content</th>
              <th>Tags</th>
              <th>Image</th>
              <th>Page views</th>
              <th>Status</th>
              <th>Comment count</th>
              <th>Post likes</th>
              <th>View Post</th>
              <th>Edit</th>
              <th>Delete</th>

          </thead>
              
          <tbody>
                <?php 
                $query = "SELECT * FROM posts  LEFT JOIN category ON posts.post_category_id = category.cat_id  WHERE post_author != '$username' ORDER BY posts.post_id DESC ";

                $result = mysqli_query($connection,$query);
                if(!$result)
                {
                  die('result query failed'.mysqli_error($connection));
                }

                while($row = mysqli_fetch_assoc($result))
                {
                    $post_id = $row['post_id'];
                    $post_id = R_E_S($post_id);
                    $post_category = $row['post_category_id'];
                    $post_category = R_E_S($post_category);
                    $post_title = $row['post_title'];
                    $post_author = $row['post_author'];
                    $post_author = R_E_S($post_author);
                    $post_content = $row['post_content'];
                    $post_tags = $row['post_tags']; 
                    $post_image = $row['post_image'];
                    $post_views = $row['post_views'];
                    $post_status = $row['post_status'];
                    $post_comments = $row['post_comment_count'];
                    $cat_id = $row['cat_id']; 
                    $cat_title = $row['cat_title']; 


                    // setting boolean value for likes in database
                    $has_been_liked_query = "SELECT * FROM likes WHERE post_id = $post_id ";
                    $has_been_liked = mysqli_query($connection,$has_been_liked_query);
                    
                    if(!$has_been_liked)
                    {
                      die('has been liked failed'.mysqli_error($connection)); 
                    }
                   
                    $likes = mysqli_num_rows($has_been_liked);
                    if($likes !== 0)
                    {
                      $set_likes = "UPDATE posts SET post_been_liked = 1 WHERE post_id = $post_id ";
                      mysqli_query($connection,$set_likes);
                    }
                    else
                    {
                      $set_likes = "UPDATE posts SET post_been_liked = 0 WHERE post_id = $post_id ";
                      mysqli_query($connection,$set_likes);
                    }
                    ?> 

                    <tr> 

                          <td><?php echo $post_id ?> </td>

                          <td><?php echo $cat_title ?> </td> 

                          <td><?php echo $post_title ?> </td>

                          <td>
                            <?php echo $post_author ?>  <br>
                                <!-- CLICKABLE PIC TO TAKE US TO ALL POSTS FROM THE AUTHOR   -->
                                <?php
                                  $query_ui = "SELECT user_image FROM users WHERE user_name = ? ";

                                  $stmt5 = mysqli_stmt_init($connection);
                                        
                                  if(!mysqli_stmt_prepare($stmt5,$query_ui))
                                  {
                                    die('prepare stmt5 failed'.mysqli_error($connection));    
                                  }
                                  
                                  if(!mysqli_stmt_bind_param($stmt5,"s",$post_author))
                                  {
                                    die('bind stmt5 failed'.mysqli_error($connection));    
                                  }
                                  
                                  if(!mysqli_stmt_execute($stmt5))
                                  {
                                      die('execute stmt 5 failed'.mysqli_error($connection));
                                  }

                                  $result5 = mysqli_stmt_get_result($stmt5);
                                  
                                  if(!$result5)
                                  {
                                    die('result 5 select query failed'.mysqli_error($connection));
                                  }


                                  $row_ui = mysqli_fetch_assoc($result5);

                                  $ui = $row_ui['user_image'];
                                  
                                  mysqli_stmt_close($stmt5);
                                
                                ?>

                                <a href="../post_author.php?the_author=<?php echo $post_author ?> ">
                                  <img src="../images/users/<?php echo $ui?>"
                                width="50px" height="50px" class="top_user_pic" > 
                              </a>  
                        </td>

                          <td><?php echo substr($post_content,0,30)  ?> </td>

                          <td><?php echo $post_tags ?> </td>
                          
                          <td><?php echo "<img src='../images/posts/$post_image' width='80px' height='120px' >" ?> </td> 

                          <td>
                            <?php echo $post_views ?> <br>

                              <!-- reset post page views back to 0 via post with js confirm  -->
                              <form action="" method="post" onsubmit="return confirm('Are you sure you want to reset the page views on post <?php echo $post_id ?> back to 0 ?')">
                                  <input type="hidden" value="<?php echo $post_id ?>" name="views_id" >

                                  <button type="submit" name="view_submit" class="text text-dark"> Reset</button> 
                              </form> 

                        </td> 

                        <td>
                            <?php echo $post_status ?> <br> 
                            <?php

                              // change status via post now not get
                            if($post_status == 'published')
                            { ?>
                                  <button type="button" data="unpublished" value="<?php echo $post_id ?>" class="text text-danger change_status_modal_btn">unpublish</button>
                              <?php 
                            }
                            else
                            { ?>  
                                <button type="button" data="published" value="<?php echo $post_id ?>" class="text text-success change_status_modal_btn">publish</button>                      
                              <?php
                            } 


                              // select all approved comments and a link for if we would like to view comments on that post 
                              $approved = R_E_S('approved');
                              $view_comments_query = "SELECT * FROM comments WHERE comment_status = ? AND comment_post_id = ? ";
                              $stmt6 = mysqli_stmt_init($connection);
                              
                              if(!mysqli_stmt_prepare($stmt6,$view_comments_query))
                              {
                                die('prepare stmt6 failed'.mysqli_error($connection));    
                              }

                              if(!mysqli_stmt_bind_param($stmt6,"si",$approved,$post_id))
                              {
                                die('bind stmt6 failed'.mysqli_error($connection));    
                              }

                              if(!mysqli_stmt_execute($stmt6))
                              {
                                die('execute stmt 6 failed'.mysqli_error($connection));
                              }

                              $view_comments_result = mysqli_stmt_get_result($stmt6);
                              
                              if(!$view_comments_result)
                              {
                                die('view_comments_result failed'.mysqli_error($connection));
                              }
                              
                              $num_of_comments = mysqli_num_rows($view_comments_result);
                              $row_comments = mysqli_fetch_assoc($view_comments_result);
                              $comment_id = $row_comments['comment_id'];
                              $comment_post_id = $row_comments['comment_post_id']; 

                              $comment_post_id = R_E_S($comment_post_id);
                              mysqli_stmt_close($stmt6);
                              
                              ?>
                           
                        </td>    

                        <!-- only show link to view comments if they are not 0  -->
                        <td>
                            <?php echo $num_of_comments ?> <br> <?php if($num_of_comments !== 0){echo "<a href='../view_comments.php?comment_post_id=$comment_post_id'> view comments  </a>" ;}  ?>
                        </td>  

                          <!-- view post likes from likes tables you should join all tables in sql  -->
                          <?php  
                          $view_likes_query = "SELECT * FROM likes WHERE post_id = ?  ";
                          $stmt10 = mysqli_stmt_init($connection);
                          
                          if(!mysqli_stmt_prepare($stmt10,$view_likes_query))
                          {
                            die('prepare stmt10 failed'.mysqli_error($connection));    
                          }
                          if(!mysqli_stmt_bind_param($stmt10,"i",$post_id))
                          {
                            die('bind stmt10 failed'.mysqli_error($connection));    
                          }
                          if(!mysqli_stmt_execute($stmt10))
                          {
                            die('execute stmt 10 failed'.mysqli_error($connection));
                          }

                          $view_likes_result = mysqli_stmt_get_result($stmt10);
                          if(!$view_likes_result)
                          {
                            die('view_comments_result failed'.mysqli_error($connection));
                          }
                          
                          $num_of_likes = mysqli_num_rows($view_likes_result);
                          
                          ?>

                        <td> <?php echo $num_of_likes ?>  </td>

                          <?php mysqli_stmt_close($stmt10) ; ?>

                        <!-- view post link via get -->
                        <td> <a href="../post.php?post=<?php echo $post_id ?>"> View Post </a> </td>

                        <!-- edit post link via get -->
                        <td> <a href="admin_posts.php?source=edit_post&p_id=<?php echo $post_id ?>"> Edit </a> </td>


                        <!-- Delete Button trigger modal -->
                        <td> <button type="button" class="text text-danger delete_button" value="<?php echo $post_id?>" rel="<?php echo $post_title ?>" > Delete </button> </td>

                   </tr>
                  <?php 
                } ?>
          </tbody>
          </table>




          <!-- /////////////////// ALL LOGGED IN ADMINS POSTS  TABLE 2/////////////////// -->
      

    <h4> Posts by me </h4>
    <?php 
        if(!empty($error_msg))
        { ?>
          <h2>  <div class="alert-danger text-danger text-center"> <?php echo $error_msg ?> </div>  </h2><?php
        } 
      ?>   
    <table class="table table-bordered table-hover">
           
        <thead>

            <th>Id</th>
            <th>Category</th>
            <th>Title</th>
            <th>Author/Username</th>
            <th>Content</th>
            <th>Tags</th>
            <th>Image</th>
            <th>Page views</th>
            <th>Status</th>
            <th>Comment count</th>
            <th>Post likes</th>
            <th>View Post</th>
            <th>Edit</th>
            <th>Delete</th>

        </thead>

        <tbody>
            <?php 
            $query = "SELECT * FROM posts  LEFT JOIN category ON posts.post_category_id = category.cat_id  WHERE post_author = '$username' ORDER BY posts.post_id DESC ";
            
            $result = mysqli_query($connection,$query);
            if(!$result)
            {
              die('result query failed'.mysqli_error($connection));
            }
            
            while($row = mysqli_fetch_assoc($result))
            {
                $post_id = $row['post_id'];
                $post_id = R_E_S($post_id);
                $post_category = $row['post_category_id'];
                $post_category = R_E_S($post_category);
                $post_title = $row['post_title'];
                $post_author = $row['post_author'];
                $post_author = R_E_S($post_author);
                $post_content = $row['post_content'];
                $post_tags = $row['post_tags']; 
                $post_image = $row['post_image'];
                $post_views = $row['post_views'];
                $post_status = $row['post_status'];
                $post_comments = $row['post_comment_count'];
                  
                  
                  // had to do this for the likes widget in users/  admin so we can show users how many of their posts have been liked in the chart
                  $has_been_liked_query = "SELECT * FROM likes WHERE post_id = $post_id ";
                  $has_been_liked = mysqli_query($connection,$has_been_liked_query);
                  if(!$has_been_liked)
                  {
                      die('has been liked failed'.mysqli_error($connection)); 
                  }

                  $likes = mysqli_num_rows($has_been_liked);
                  if($likes !== 0)
                  {
                    $set_likes = "UPDATE posts SET post_been_liked = 1 WHERE post_id = $post_id ";
                    mysqli_query($connection,$set_likes);
                  }
                  else
                  {
                    $set_likes = "UPDATE posts SET post_been_liked = 0 WHERE post_id = $post_id ";
                    mysqli_query($connection,$set_likes);
                  }
                  

                  // new data we can pull out here now as tables are joined
                  $cat_id = $row['cat_id']; 
                  $cat_title = $row['cat_title']; 
                  
                  ?> 
                 
                 <tr> 

                      <td><?php echo $post_id ?> </td>

                      <td><?php echo $cat_title ?> </td> 

                      <td><?php echo $post_title ?> </td>
                      
                      <td>
                        <?php echo $post_author ?>  <br>
                            <!-- CLICKABLE PIC TO TAKE US TO ALL POSTS FROM THE AUTHOR   -->
                            <?php
                              $query_ui = "SELECT user_image FROM users WHERE user_name = ? ";
                            
                              $stmt5 = mysqli_stmt_init($connection);
                                    
                              if(!mysqli_stmt_prepare($stmt5,$query_ui))
                              {
                                die('prepare stmt5 failed'.mysqli_error($connection));    
                              }
                              if(!mysqli_stmt_bind_param($stmt5,"s",$post_author))
                              {
                                die('bind stmt5 failed'.mysqli_error($connection));    
                              }
                              if(!mysqli_stmt_execute($stmt5))
                              {
                                die('execute stmt 5 failed'.mysqli_error($connection));
                              }

                              $result5 = mysqli_stmt_get_result($stmt5);
                              if(!$result5)
                              {
                                die('result 5 select query failed'.mysqli_error($connection));
                              }

                            $row_ui = mysqli_fetch_assoc($result5);

                            $ui = $row_ui['user_image'];
                            mysqli_stmt_close($stmt5);
                            
                            ?>

                            <a href="../post_author.php?the_author=<?php echo $post_author ?> ">  
                              <img src="../images/users/<?php echo $ui?>" width="50px" height="50px" class="top_user_pic" > 
                            </a>  

                      </td>
                      
                      <td><?php echo substr($post_content,0,30)  ?> </td>

                      <td><?php echo $post_tags ?> </td>

                      <td><?php echo "<img src='../images/posts/$post_image' width='80px' height='120px' >" ?> </td> 
                      
                      <td>
                          <?php echo $post_views ?> <br>
                      
                          <!-- reset post page views back to 0 via post with js confirm  -->
                          <form action="" method="post" onsubmit="return confirm('Are you sure you want to reset the page views on post <?php echo $post_id ?> back to 0 ?')">
                              <input type="hidden" value="<?php echo $post_id ?>" name="views_id" >
                              <button type="submit" name="view_submit" class="text text-dark"> Reset</button> 
                          </form>
                    </td> 
                      
                      <td>
                        <?php echo $post_status ?> <br> 

                          <?php 
                          // change status via post now not get
                          if($post_status == 'published')
                          { ?>
                                <button type="button" data="unpublished" value="<?php echo $post_id ?>" class="text text-danger change_status_modal_btn">unpublish</button>
                            <?php 
                          }
                          else
                          { ?>  
                              <button type="button" data="published" value="<?php echo $post_id ?>" class="text text-success change_status_modal_btn">publish</button>                      
                            <?php
                          } 

                          // select all approved comments and a link for if we would like to view comments on that post 
                          $approved = R_E_S('approved');
                          $view_comments_query = "SELECT * FROM comments WHERE comment_status = ? AND comment_post_id = ? ";
                          $stmt6 = mysqli_stmt_init($connection);
                          if(!mysqli_stmt_prepare($stmt6,$view_comments_query))
                          {
                            die('prepare stmt6 failed'.mysqli_error($connection));    
                          }

                          if(!mysqli_stmt_bind_param($stmt6,"si",$approved,$post_id))
                          {
                            die('bind stmt6 failed'.mysqli_error($connection));    
                          }

                          if(!mysqli_stmt_execute($stmt6))
                          {
                            die('execute stmt 6 failed'.mysqli_error($connection));
                          }

                          $view_comments_result = mysqli_stmt_get_result($stmt6);
                          if(!$view_comments_result)
                          {
                            die('view_comments_result failed'.mysqli_error($connection));
                          }

                          $num_of_comments = mysqli_num_rows($view_comments_result);
                          $row_comments = mysqli_fetch_assoc($view_comments_result);
                          $comment_id = $row_comments['comment_id'];
                          $comment_post_id = $row_comments['comment_post_id']; 

                          $comment_post_id = R_E_S($comment_post_id);
                          mysqli_stmt_close($stmt6);
                          ?>
                  </td> 
                      <!-- only show link to view comments if they are not 0  -->
                    <td> <?php echo $num_of_comments ?> <br> <?php if($num_of_comments !== 0)
                            {
                              echo "<a href='../view_comments.php?comment_post_id=$comment_post_id'> view comments  </a>" ;
                            }  ?>
                    </td>  

                        <!-- view post likes from likes table -->
                      <?php  
                      $view_likes_query = "SELECT * FROM likes WHERE post_id = ?  ";
                      $stmt10 = mysqli_stmt_init($connection);
                      if(!mysqli_stmt_prepare($stmt10,$view_likes_query))
                      {
                        die('prepare stmt10 failed'.mysqli_error($connection));    
                      }

                      if(!mysqli_stmt_bind_param($stmt10,"i",$post_id))
                      {
                        die('bind stmt10 failed'.mysqli_error($connection));    
                      }

                      if(!mysqli_stmt_execute($stmt10))
                      {
                        die('execute stmt 10 failed'.mysqli_error($connection));
                      }

                      $view_likes_result = mysqli_stmt_get_result($stmt10);
                      if(!$view_likes_result)
                      {
                        die('view_comments_result failed'.mysqli_error($connection));
                      }

                      $num_of_likes = mysqli_num_rows($view_likes_result);
                      
                    ?>

                    <td> <?php echo $num_of_likes ?>  </td>

                    <?php mysqli_stmt_close($stmt10) ; ?>
                      
                    <!-- view post link via get -->
                    <td> <a href="../post.php?post=<?php echo $post_id ?>"> View Post </a> </td>
                        
                    <!-- edit post link via get -->
                    <td> <a href="admin_posts.php?source=edit_post&p_id=<?php echo $post_id ?>"> Edit </a> </td>

                    <!-- Delete Button trigger modal -->
                    <td> <button type="button" class="text text-danger delete_button" href="javascript:void(0)"  value="<?php echo $post_id?>" rel="<?php echo $post_title ?>" > Delete </button> </td>
               </tr>
             <?php 
            }  
             ?>
        </tbody>
    </table>
    <?php 
        if(!empty($error_msg))
        { ?>
          <h2>  <div class="alert-danger text-danger text-center"> <?php echo $error_msg ?> </div>  </h2><?php
        } 
    ?>            
</div>     






