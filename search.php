<?php 
require_once "includes/header.php";
require_once "includes/navigation.php";
?> 

    
<div class="container-fluid "  id="main_contain">

  <div class="row">

    <!-- Blog Entries Column -->
    <div class="col-lg-1 hidden-md" ></div>
           
    <div class="col-md-7" id="main_div">
       
      <h1 class="page-header" id="login_head" > Search results </h1>

      <?php 
      if(isset($_POST['submit_search']))
      {
          
        $search = R_E_S($_POST['search']); 

          if(empty($search))
          {
            header("Location: index.php?search");
          }
              
              $published = "published";
              $the_search = "%$search%";

              $query = "SELECT * FROM posts LEFT JOIN category ON category.cat_id = posts.post_category_id 
              WHERE posts.post_status = ? AND 
              posts.post_author LIKE ? OR posts.post_content LIKE ? OR posts.post_tags LIKE ? OR posts.post_title LIKE ? ";
              
              $stmt = mysqli_stmt_init($connection);
              
              if(!mysqli_stmt_prepare($stmt,$query))
              {
                die('stmt prep failed'.mysqli_error($connection));
              }
              
              if(!mysqli_stmt_bind_param($stmt,'sssss',$published,$the_search,$the_search,$the_search,$the_search))
              {
                die('stmt bind failed'.mysqli_error($connection) );
              }
              
              if(!mysqli_stmt_execute($stmt))
              {
                die('stmt execute failed'.mysqli_error($connection));
              }

              $result = mysqli_stmt_get_result($stmt);
          
              if(!$result)
              {
                die('select all posts from search result failed'.mysqli_error($connection));
              }
        
              $all_found = mysqli_num_rows($result);
        
              if($all_found === 0) // if search returns no posts then output msg to user
              { 
                echo " 
                      <h1 class='text text-danger'>
                          </i>  Sorry we could not find any posts with the tags you searched for.. </i>  
                      </h1> <br>
                                Please try searching something else or try again another time. "; 
              } 
              else
              {
               
              

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

                      <!-- First Blog Post -->
                      <h5>
                        <a class="post_title" id="search_title" href="post.php?post=<?php echo $post_id; ?>">  <?php echo $post_title; ?> </a>
                      </h5>
                      
                      <p class="lead">
                          <i> by </i>  <a id="title_link" href="post_author.php?the_author=<?php echo $post_author ?>">  <?php echo $post_author ?></a>
                      </p>
                      
                      <p> <span class="glyphicon glyphicon-time"></span> Posted on <?php echo $post_date ?> </p>
                      <br>

                      <img id="post_image"  class="img-responsive" src="images/posts/<?php echo $post_image ?> " alt="">
                      <br>

                      <p class="post_text"> <?php echo substr($post_content,0,70) ?> ......</p>
                      <a class="btn btn-primary" href="post.php?post=<?php echo $post_id ?>"> Read More <span class="glyphicon glyphicon-chevron-right"></span></a>

                      <hr class="hr_big"> 

                      <?php 
                    
                  }
              }   
              mysqli_stmt_close($stmt); 

      }  ?>

    </div>  <!--    <div class="col-md-7">  -->

  <!-- Blog Sidebar Widgets Column -->
  <?php include_once "includes/sidebar.php" ?> 

  <hr>

  <!-- Footer -->
  <?php include_once "includes/footer.php" ?> 