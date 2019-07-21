<?php 
require_once "includes/header.php"; 
require_once "includes/navigation.php";  

  $id = $_SESSION['id'];
  $username = $_SESSION['username']; 
  $password = $_SESSION['password'];
  $email = $_SESSION['email'];
  $f_name = $_SESSION['f_name'];
  $l_name = $_SESSION['l_name'];
  $image = $_SESSION['image']; 
  $role = $_SESSION['role'];

?>

<!-- Page Content -->
<div class="container-fluid"  id="main_contain">

    <div class="row">

  
      <div class="col-lg-1 hidden-md" ></div>
            
      <div class="col-md-7" id="main_div">

        <h1 id="login_head" > View Post </h1>

        <?php
        // get the post info
        if(isset($_GET['post']))
        { 

            $get_post = R_E_S($_GET['post']);
            $published = R_E_S('published');

            $query = "SELECT * FROM posts WHERE post_id = ? AND post_status = ? ";
      
            $stmt = mysqli_stmt_init($connection);

            if(!mysqli_stmt_prepare($stmt,$query) )
            {
              die('stmt prepare failed'.mysqli_error($connection));
            }

            if(!mysqli_stmt_bind_param($stmt,"is",$get_post,$published))
            {
              die("stmt bind failed".mysqli_error($connection));
            }

            if(!mysqli_stmt_execute($stmt))
            {
              die('stmt execute failed '.mysqli_error($connection));
            }

            $result = mysqli_stmt_get_result($stmt);
            if(!$result)
            {
              die('stmt result failed'.mysqli_error($connection));
            }
        
            $all_posts = mysqli_num_rows($result);
          
            // update post page views
            $view_query = "UPDATE posts SET post_views = post_views + 1  WHERE post_id = ? ";
          
            $stmt1 = mysqli_stmt_init($connection);
            if(!mysqli_stmt_prepare($stmt1,$view_query) )
            {
                die('stmt1 prepare failed'.mysqli_error($connection));
            }

            if(!mysqli_stmt_bind_param($stmt1,"i",$get_post))
            {
                die("stmt1 bind failed".mysqli_error($connection));
            }
        
            if(!mysqli_stmt_execute($stmt1))
            {
                die('stmt1 execute failed '.mysqli_error($connection));
            }
            else
            {
                mysqli_stmt_close($stmt1);
            }
              
              while($row = mysqli_fetch_assoc($result))
              {
                  $post_id = $row['post_id'];
                  $post_category = $row['post_category_id'];
                  $post_title = $row['post_title'];
                  $post_author = $row['post_author'];
                  $post_content = $row['post_content'];
                  $post_tags = $row['post_tags']; 
                  $post_image = $row['post_image'];
                  $post_views = $row['post_views'];
                  $post_status = $row['post_status'];
                  $post_comments = $row['post_comment_count']; 
                  $post_tags = $row['post_tags'];
                  $post_date = $row['post_date'];
                  ?>

                    
                        
                    <div>
                        <span class="post_title">  <?php echo $post_title ?>  </span>
                    </div>

                    <p class="lead">
                        <i> by </i>   <a  id="title_link" href="post_author.php?the_author=<?php echo $post_author ?>"> <?php echo $post_author ?></a>
                    </p>
                        
                    <p> 
                        <span class="glyphicon glyphicon-time"></span> Posted on <?php echo $post_date ?> 
                    </p> 
                    <br>
                        
                    <img id="post_image" class="img-responsive" src="images/posts/<?php echo $post_image ?> " alt="">
                    <br>
                        
                    <p class="post_text"> <?php echo $post_content ?></p>
                  
                    <?php 
                    mysqli_stmt_close($stmt); 

                    //////// POST LIKES ///////// find if user has already like post getting ready to show thumbs up or thumbs down 
                    $get_id = $_SESSION['id'];             
                    $has_user_liked_query = "SELECT * FROM likes WHERE user_id = ? AND post_id = ? " ;
                  
                    $stmt_like = mysqli_stmt_init($connection);
              
                    if(!mysqli_stmt_prepare($stmt_like,$has_user_liked_query) )
                    {
                      die('stmt_like prepare failed'.mysqli_error($connection));
                    }

                    if(!mysqli_stmt_bind_param($stmt_like,"ii",$get_id,$get_post))
                    {
                      die("stmt_like bind failed".mysqli_error($connection));
                    }
              
                    if(!mysqli_stmt_execute($stmt_like))
                    {
                      die('stmt_like execute failed '.mysqli_error($connection));
                    }
              
                    $has_liked_result = mysqli_stmt_get_result($stmt_like);
                    if(!$has_liked_result)
                    {
                      die('stmt_liked result failed '.mysqli_error($connection));
                    }

                    if(mysqli_num_rows($has_liked_result) == 0) // then they have NOT liked the post AS they are not in the LIKED db so show thumbs up 
                    {   
                        if($_SESSION['id'] !== '' && isset($_SESSION['id']))
                        { ?> 
                              <div class="row">
                                  <form action="" method="post">
                                      <p class="">
                                        <button class="likes" type="submit" name="liked" > <span class="glyphicon glyphicon-thumbs-up"></span> Like </button> 
                                      </p> 
                                  </form> 
                              </div>
                          <?php  
                        }
                    }
                    else // then they have  already liked the post so only show thumbs down! 
                    {   
                        if($_SESSION['id'] !== '' && isset($_SESSION['id']))
                        { ?> 
                          <div class="row">
                            <form action="" method="post">
                                <p class=""> <button class="likes" type="submit" name="unliked" > <span class="glyphicon glyphicon-thumbs-down"></span> Unlike </button> </p> 
                            </form>  
                          </div>
                          <?php 
                        }
                    } ?>

                            <!-- SHOW POST LIKES -->
                    <div class="row">
                        <?php 
                        $how_many_likes_query = "SELECT * from likes WHERE post_id = ? " ;
                        $stmt_like1 = mysqli_stmt_init($connection);

                        if(!mysqli_stmt_prepare($stmt_like1,$how_many_likes_query) )
                        {
                          die('stmt_like1 prepare failed'.mysqli_error($connection));
                        }
                        
                        if(!mysqli_stmt_bind_param($stmt_like1,"i",$post_id))
                        {
                          die("stmt_like1 bind failed".mysqli_error($connection));
                        }
                          
                        if(!mysqli_stmt_execute($stmt_like1))
                        {
                          die('stmt_like1 execute failed '.mysqli_error($connection));
                        }

                        $how_many_likes_result = mysqli_stmt_get_result($stmt_like1);
                        if(!$has_liked_result)
                        {
                          die('stmt_liked1 result failed '.mysqli_error($connection));
                        }
                                
                        $all_likes = mysqli_num_rows($how_many_likes_result);
                                  
                        mysqli_stmt_close($stmt_like1);
                                
                        ?>

                        <p class="like_text"> 
                          <span class="glyphicon glyphicon-heart"></span> POST LIKES: <?php echo $all_likes ?> 
                        </p>  
                          
                    </div>

                    <?php 
                    mysqli_stmt_close($stmt_like); 
                    
              }
        }



  
 ?>

       
        <!-- Post Comments -->
        <div id="no_hr" class="login_head"> Comments </div> 
          <?php
          $approved = 'approved';
          $view_comments_query = "SELECT * FROM comments WHERE comment_status = ? AND comment_post_id = ? ";
      
          $stmt = mysqli_stmt_init($connection);

          if(!mysqli_stmt_prepare($stmt,$view_comments_query) )
          {
              die('stmt prepare failed'.mysqli_error($connection));
          }

          if(!mysqli_stmt_bind_param($stmt,"si",$approved,$get_post))
          {
              die("stmt bind failed".mysqli_error($connection));
          }

          if(!mysqli_stmt_execute($stmt))
          {
              die('stmt execute failed '.mysqli_error($connection));
          }

          $view_comments_result = mysqli_stmt_get_result($stmt);
          
          if(!$view_comments_result)
          {
            die('view comments query failed'.mysqli_error($connection));
          }
         
          while($row_comments = mysqli_fetch_assoc($view_comments_result))
          {
            
              $db_author = $row_comments['comment_author'];
              $db_email = $row_comments['comment_email'];
              $db_date = $row_comments['comment_date'];
              $db_comment_email = $row_comments['comment_email'];
              $db_comment = $row_comments['comment_content']; ?>
          

              <div class="media">
                  <div class="media-body">
                        <?php
                        $img_query = "SELECT * FROM users WHERE user_name = '$db_author' ";          
                        $img_result = mysqli_query($connection,$img_query);
                        if(!$img_result)
                        {
                          die('img result failed'.mysqli_error($connection));
                        }

                        while($img_row = mysqli_fetch_assoc($img_result))
                        {       
                          $img = $img_row['user_image'];  
                        } ?>

                        <div> 
                            <img src="images/users/<?php echo $img; ?>" width="50px" height="50px" class="comment_pic" > 
                            <span class="comment_author"> <?php echo $db_author; ?>: </span>    
                        </div>
                          
                        <div class="comment_text"> <?php echo $db_comment; ?> </div> 

                        <small class="comment_date"><?php echo $db_date; ?></small>

                  </div> <br>
              </div>
              <?php  
              mysqli_stmt_close($stmt);
          }  ?>      
     
        
          <!-- leave a comment section .... only shows if users are logged in    -->
        <?php 
        if(isset($_SESSION['id']) && $_SESSION['id'] !== '')
        { ?>
          <!-- Comments Form -->
          <div class="well">
              <h4>Leave a Comment:</h4>
              <form method="post" role="form">
              
                <div class="form-group">
                  <label for="">Name</label>
                  <select name="author" id="">
                      <option default value="<?php echo $username ?>"> <?php echo $username ?> </option>
                  </select>
                </div>
              
                  <div class="form-group">
                      <label for=""> Please leave your comment in the box below</label>
                      <textarea name="comment" class="form-control" rows="3"></textarea>
                  </div>
                  
                  <button type="submit"  name="submit_comment" class="btn btn-primary">Submit Comment</button>
              </form>
          </div>
          <?php 
        } ?>
           
           <br>
            <!-- out put message instead of post comment form for un-logged in or non subscribers  -->
             <?php 
             if($_SESSION['id'] === '' || !isset($_SESSION['id']))
             { ?> 
                <div class="row">
                  <small> <h6 class="like_text"> Please login or sign up to like, make comments & create your own posts! </h6> </small> 
                </div>
             <?php 
             }   
    
    // submit comment query 
  if(isset($_POST['submit_comment']))
  {

      $author = htmlspecialchars($_POST['author']);
      $comment = htmlspecialchars( $_POST['comment']);
      $comment_date = date('Y-m-d');

      //valdation .. check for empty fields
        if(empty($author) || empty($comment))
        {
          echo " 
                <h4 class='text text-danger'>
                  <i> Please do not leave any fields blank when commenting on a post ! <i/> 
                </h4> 
                      fill out all sections and try again ";
        }
        else
        {    //passed all fields are filled in 
        
            $comment_query = "INSERT INTO comments(comment_post_id,comment_author,comment_email,comment_content,comment_date,post_author_comments) VALUES(?,?,?,?,?,?) ";
            $stmt1 = mysqli_stmt_init($connection);
            
            if(!mysqli_stmt_prepare($stmt1,$comment_query) )
            {
              die('stmt1 prepare failed'.mysqli_error($connection));
            }
              
            if(!mysqli_stmt_bind_param($stmt1,"isssss",$get_post,$author,$email,$comment,$comment_date,$post_author))
            {
              die("stmt1 bind failed".mysqli_error($connection));
            }
              
            if(!mysqli_stmt_execute($stmt1))
            {
              die('stmt1 execute failed '.mysqli_error($connection));
            }
            else
            {
              mysqli_stmt_close($stmt1);
              echo " 
                    <h4 class='text text-success'>
                        <i> Thank you...  Your comment has been submitted and is awaiting aprroval , </i>
                    </h4>  
                        <b> 
                          We will email you as soon as it has been aprroved/declined 
                        </b> ";
            }
        }

  }

  ?>

      </div>

    <!-- Blog Sidebar Widgets Column -->
  <?php include_once "includes/sidebar.php" ?> 

  <br>

    <!-- Footer -->
  <?php include_once "includes/footer.php"; 



  //unlike like post query
  if(isset($_POST['unliked']))
  {

        $delete_likes_query = "DELETE FROM likes WHERE user_id = ? AND post_id = ? ";

        $user_id = $_SESSION['id'];
    
        $stmt_d = mysqli_stmt_init($connection);

        if(!mysqli_stmt_prepare($stmt_d,$delete_likes_query))
        {
            die("stmt_d prepare failed".mysqli_error($connection));
        }

        if(!mysqli_stmt_bind_param($stmt_d,"ii",$user_id,$post_id))
        {
            die("stmt_d bind failed".mysqli_error($connection));
        }

        if(!mysqli_stmt_execute($stmt_d))
        {
            die("stmt_d execute failed".mysqli_error($connection));
        }
        else
        {    
          mysqli_stmt_close($stmt_d);
          header("Location:/CMS/post.php?post=$post_id");
        }
  }


 
    // like post query
  if(isset($_POST['liked']))
  {

      $insert_likes_query = "INSERT INTO likes(user_id,post_id) VALUES(?,?) ";

      $user_id = $_SESSION['id'];

      $stmt_i = mysqli_stmt_init($connection);

      if(!mysqli_stmt_prepare($stmt_i,$insert_likes_query))
      {
        die("stmt_i prepare failed".mysqli_error($connection));
      }
        
      if(!mysqli_stmt_bind_param($stmt_i,"ii",$user_id,$post_id))
      {
        die("stmt_i bind failed".mysqli_error($connection));
      }
        
      if(!mysqli_stmt_execute($stmt_i))
      {
        die("stmt_i execute failed".mysqli_error($connection));
      }
      else
      {
          mysqli_stmt_close($stmt_i);
          header("Location:/CMS/post.php?post=$post_id");
      }
   
  }  

  
