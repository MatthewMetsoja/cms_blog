<?php
require_once "includes/header.php";
require_once "includes/navigation.php"; 

// <!-- Setting For Pagination  -->   
  if(isset($_GET['page']))
  {
    $page = R_E_S($_GET['page']);
  }
  else
  {
    $page = 1;
  }

  if($page === '' || $page === 0)
  {
    $page1 = 0;
  }else
  { 
    $page1 = ($page * 5) -5 ; 
  }

?>

<div class="container-fluid" id="main_contain">

  <div class="row">
     
    <div class="col-lg-1 hidden-md" ></div>

    <div class="col-md-7" id="main_div">
      <h1 class="page-header" id="login_head"> Welcome Home <span class="glyphicon glyphicon-home"></span> </h1>
      <h4 id="login_head"> All Current Posts </h4>
    
          <?php 
          $published = R_E_S('published');
          $query = "SELECT * FROM posts WHERE post_status = '$published' ORDER BY post_id DESC LIMIT $page1,5  ";
          $result = mysqli_query($connection,$query);
          if(!$result)
          {
            die('select all post result failed'.mysqli_error($connection));
          }
          
          $all_posts = mysqli_num_rows($result);
          if($all_posts === 0)
          {
            echo " <h4 class='login_head text text-danger'> Sorry there are not currently any posts to view </h4>";
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
                    <h2>
                        <a id="title_link" href="post.php?post=<?php echo $post_id ?> "> <?php echo $post_title ?> </a>
                    </h2>

                    <p class="lead">
                      <i> by </i>   <a  id="title_link" href="post_author.php?the_author=<?php echo $post_author ?>">  <?php echo $post_author ?></a>
                    </p>

                    <p><span class="glyphicon glyphicon-time"></span> Posted on <?php echo $post_date ?> </p>
                    <br>

                    <img id="post_image" class="img-responsive" src="images/posts/<?php echo $post_image ?> " alt="">
                    <br>

                    <p class="post_text">  <?php echo substr($post_content,0,70) ?> ......</p>

                    <a class="btn btn-primary" href="post.php?post=<?php echo $post_id ?>">
                      Read More <span class="glyphicon glyphicon-chevron-right"></span>
                    </a>

                    <hr class="hr_big">   

                    <?php 
                }
          }

      ?>
            
           <br>     

                  <!-- Pager --> 
      <?php
      // dont let page go below 0 as nothing wil be there causing error
      if($all_posts !== 0)
      {
          // back a page setting  
          $previous = $page -1 ;
        
          if($previous <= 0)
          {
            $previous = 1;
          } ?>

            <ul class="pager">
                        
                              <li class="previous">
                                  <a href="index.php?page=<?php echo $previous ?>" class='previous'>&larr; Previous </a>
                              </li> 
                
                <?php
                $query1 = "SELECT * FROM posts WHERE post_status = 'published' ";
                $result1 = mysqli_query($connection,$query1);
                if(!$result1)
                {
                  die('select all post result failed'.mysqli_error($connection));
                }
                
                $count = ceil(mysqli_num_rows($result1)/5);
                
                for($i = 1; $i <= $count; $i++ )
                {
                    if($page == $i)
                    {
                          echo " <li> <a class='active' href='index.php?page=$i'> $i </a> </li> ";
                    }
                    else
                    {
                          echo " <li> <a href='index.php?page=$i'> $i </a> </li> ";
                    }
                }
                      
          // forward a page setting  
          $next = $page +1;
          
          // dont let pages go above how many there are
          if($next >= $count)
          {
              $next = $count;
          }
          // if page is unset eg/ first page skip one so it doesnt view page one twice   
          if($page === '')
          {
            $next = $page + 2;
          }     ?>
                    
                          <li class="next">
                              <a href="index.php?page=<?php echo $next ?>" class="next">Next &rarr;</a>
                          </li>
            </ul>
                  <?php 
      } ?>  
        
      </div>

  <!-- Blog Sidebar Widgets Column -->
  <?php include "includes/sidebar.php" ?> 

  <hr>

  <!-- Footer -->
  <?php include_once "includes/footer.php" ?> 