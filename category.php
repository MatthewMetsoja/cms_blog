<?php
require_once "includes/header.php"; 
require_once "includes/navigation.php"; 
?> 


<div class="container-fluid">

  <div class="row">

  
    <div class="col-lg-1 hidden-md" ></div>
        
    <div class="col-md-7" id="main_div">
        
      <h1 class="page-header" id="login_head" >
        <?php 
        if(isset($_GET['category']))
        { ?>
      
            
                <?php
                  $category = R_E_S($_GET['category']); 
                  $published = 'published';

                  $select_query = "SELECT * FROM category WHERE cat_id = ? ";

                  $stmt = mysqli_stmt_init($connection);     
                
                  if(!mysqli_stmt_prepare($stmt,$select_query))
                  {
                    die('select query failed'.mysqli_stmt_error($stmt));   
                  } 
                  else
                  {
                      mysqli_stmt_bind_param($stmt,"i",$category);    
                  
                      mysqli_stmt_execute($stmt);
      
                      $stmt_result = mysqli_stmt_get_result($stmt);
                  
                      while($row = mysqli_fetch_assoc($stmt_result))
                      {
                          $db_cat_title = $row['cat_title']; ?>
                          <h1 class="login_head">
                            All  <?php echo $db_cat_title ?>   posts  
                          </h1>  <?php  
                    
                      }

                      mysqli_stmt_close($stmt);
                  }  

    
            
                $query = "SELECT * FROM posts WHERE post_category_id = ? AND post_status = ? ";
                
                $stmt1 = mysqli_stmt_init($connection);

                if(!mysqli_stmt_prepare($stmt1,$query))
                {
                  die('stmt1 failed'.mysqli_stmt_error($connection));    
                }
                else
                {
                    mysqli_stmt_bind_param($stmt1,"is",$category,$published);
        
                    mysqli_stmt_execute($stmt1);
                
                    // get the result back 
                    $result = mysqli_stmt_get_result($stmt1);
                
                    $all_found = mysqli_num_rows($result);
                  
                    if($all_found === 0)
                    {
                        echo " 
                            <h1 class='text text-danger comment_text'> 
                              </i>  Sorry there are not currently any posts under that category </i>  
                            </h1> <br>
                            
                            Please try a different category or try again tommorow. "; 
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
                        $post_date = $row['post_date'];  ?>

                        <h2>
                            <a id="title_link" href="post.php?post=<?php echo $post_id ?> "> <?php echo $post_title ?> </a>
                        </h2>

                        <p class="lead">
                          <i> by  </i> <a id="title_link"  href="post_author.php?the_author=<?php echo $post_author ?>">  <?php echo $post_author ?></a>
                        </p>

                        <p><span class="glyphicon glyphicon-time"></span> Posted on <?php echo $post_date ?> </p>
                      
                        <br>

                        <img id="post_image" class="img-responsive" src="images/posts/<?php echo $post_image ?> " alt="">
                        <br>

                        <p class="post_text">  <?php echo substr($post_content,0,70) ?> ......</p>

                        <a class="btn btn-primary" href="post.php?post=<?php echo $post_id ?>"> Read More <span class="glyphicon glyphicon-chevron-right"></span></a>

                        <hr class="hr_big"> 
                        <?php 
                    } 
                    mysqli_stmt_close($stmt1);
                }  


        }   
        ?>        
                                        
    </div>  
          
<?php include_once "includes/sidebar.php"; ?> 
<hr>
<?php include_once "includes/footer.php"; ?> 