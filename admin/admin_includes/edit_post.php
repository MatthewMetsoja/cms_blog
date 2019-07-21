  <!-- edit post form -->
  <?php
  if(isset($_GET['p_id']))
  {

      $p_id = $_GET['p_id'];

      $p_id = filter_var($p_id,FILTER_SANITIZE_NUMBER_INT); 
    
    // create template
      $query = "SELECT * FROM posts WHERE post_id = ? ";
      
      // create a prepared statement
      $stmt = mysqli_stmt_init($connection);
      
      //prepare the stmt
      if(!mysqli_stmt_prepare($stmt,$query))
      {
        {die('prepare stmt failed'.mysqli_error($connection));}
      }
      else
      {

          //bind and check for errors
          if(!mysqli_stmt_bind_param($stmt,"i",$p_id))
          {
              die("bind stmt error".mysqli_error($connection));
          }
          
          else // if no errors then execute check for errors
          { 
                if(!mysqli_stmt_execute($stmt))
                {
                  die("execute stmt error ".mysqli_error($connection));
                }
                  
                // get back the result
                $result = mysqli_stmt_get_result($stmt);
                if(!$result)
                {
                  die('result query failed'.mysqli_error($connection));
                }
            
                while($row = mysqli_fetch_assoc($result))
                {
                    $db_post_id = $row['post_id'];
                    $db_post_category = $row['post_category_id'];
                    $db_post_title = $row['post_title'];
                    $db_post_author = $row['post_author'];
                    $db_post_content = $row['post_content'];
                    $db_post_tags = $row['post_tags']; 
                    $db_post_image = $row['post_image'];
                    $db_post_views = $row['post_views'];
                    $db_post_status = $row['post_status'];
                    $db_post_comments = $row['post_comment_count'];
                    ?> 
                       
                       <form action="" method="post" enctype="multipart/form-data">            
                          <div class="col-xs-12">
                            <h4 class="text-center"> Edit/Update Post</h4>
                                         <div class="form-group">
                                                <label for=""> Post Category</label>
                                                <select class="form-control" name="category"> 
                                                    <?php 
                                                      
                                                            $select_cat_query1 = "SELECT * FROM category ";
                                                            $select_cat_result1 = mysqli_query($connection,$select_cat_query1);
                                                            if(!$select_cat_result1)
                                                            {
                                                              die('select cat query 1 failed'.mysqli_error($connection));
                                                            }

                                                            while($row1 = mysqli_fetch_assoc($select_cat_result1))
                                                            {
                                                                $cat_id = $row1['cat_id']; 
                                                                $cat_title = $row1['cat_title']; ?>
                                                              
                                                                <?php
                                                                if($cat_id === $db_post_category)  // GET CURRENTLY SELECTED CATEGORY AS DEFAULT SELECT 
                                                                {  ?>
                                                                        <option selected value="<?php echo $cat_id ?>"> <?php echo $cat_title ?> </option>      
                                                                    <?php 
                                                                }
                                                                else // GET THE OTHER CATEGORYS 
                                                                {  ?>
                                                                        <option value="<?php echo $cat_id ?>"> <?php echo $cat_title ?> </option>
                                                                    <?php 
                                                                }
                                                      
                                                            }
                                                          
                                                        ?>

                                                </select>
                                           </div>

                                           <div class="form-group">
                                              <label for=""> Post Title</label>
                                              <input type="text" class="form-control" name="title" value="<?php echo $db_post_title ?> " >
                                           </div>
                                           
                                           <div class="form-group">
                                                <label for=""> Post Author</label>
                                                <select name="post_author" class="form-control">
                                                     
                                                     <?php 
                                                      // get the  original post authors username 
                                                        $paun_query = "SELECT post_author FROM posts WHERE post_id = $p_id ";
                                                        $paun_result = mysqli_query($connection,$paun_query);
                                                        if(!$paun_result)
                                                        {
                                                          die('get post_author query failed'.mysqli_error($connection));
                                                        }
                                                        $row = mysqli_fetch_assoc($paun_result);
                                                        $username = $row['post_author']; ?>

                                                      <option value='<?php echo $username ?>'> <?php echo $username ?> </option>

                                                      <?php
                                                      // if its not the same as session username then echo session username as option  
                                                      if($username !== $_SESSION['username'])
                                                      { ?>
                                                          <option value="<?php echo $_SESSION['username'] ?> "> <?php echo $_SESSION['username'] ?> </option> 
                                                        <?php 
                                                      } ?>  
                                                  
                                                </select>
                                           </div>
                                           

                                           <div class="form-group">
                                              <label for=""> Post Content</label>
                                              <textarea name="content" rows="10" class="form-control"> <?php echo $db_post_content ?>  </textarea>
                                           </div>

                                           <div class="form-group">
                                              <label for=""> Post tags</label>
                                              <input type="text" class="form-control" name="tags" value="<?php echo $db_post_tags ?> " >
                                           </div>

                                            <div class="form-group">
                                              <label for=""> Post Image</label> <br>
                                              <img src="../images/posts/<?php echo $db_post_image ?>" width="150px"> <br>
                                              <input type="file" class="form-control" name="image">
                                           </div>

                                           <div class="form-group">
                                           <label for=""> Post Status</label>
                                           <select name="status" id="" class="form-control">
                                              <?php
                                               echo "<option value='$db_post_status'> $db_post_status </option>";
                                                    if($db_post_status == 'published')
                                                    {
                                                      echo "<option value='unpublished'>unpublish</option>" ;
                                                    }
                                                    else
                                                    {
                                                      echo "<option value='published'>publish</option>";
                                                    }
                                              ?>
                                          </select>
                                          </div> 
                                          <div>                        
                                            <button class="btn btn-primary" type="submit" name="submit_update"> Update Post </button>
                                            </div>
                      
                          </div>
                      </form>


                          
                         

                   <?php  
                }  
          }
      } 
              
        mysqli_stmt_close($stmt);  
  } 
   
 

                      
                     
                      // <!-- UPDATE/EDIT POST QUERY -->

        
  if(isset($_POST['submit_update']))
  {
            
            $category = mysqli_real_escape_string($connection,trim($_POST['category']));
            $title = mysqli_real_escape_string($connection,trim($_POST['title']));
            $content = mysqli_real_escape_string($connection,trim($_POST['content']));
            $tags = mysqli_real_escape_string($connection,trim($_POST['tags']));
            $image = $_FILES['image']['name'];
            $tmp_image = $_FILES['image']['tmp_name'];
            $status = mysqli_real_escape_string($connection,trim($_POST['status']));
            $date = date("Y-m-d"); 
          
            $post_author = mysqli_real_escape_string($connection,trim($_POST['post_author']));

            if( empty($image) ) // keep old pic if its not being updated
            { 
                $image_query = "SELECT post_image FROM posts WHERE post_id = ? ";
                $stmt_2 = mysqli_stmt_init($connection);
                
                if(!mysqli_stmt_prepare($stmt_2,$image_query))
                {
                  die('prepare stmt 2 failed'.mysqli_error($connection));
                } 
                
                if(!mysqli_stmt_bind_param($stmt_2,"i",$p_id))
                {
                  die('stmt 2 bind failed'.mysqli_error($connection));
                }
                
                if(!mysqli_stmt_execute($stmt_2))
                {
                  die('stmt 2 execute failed'.mysqli_error($connection));
                }

                $image_result = mysqli_stmt_get_result($stmt_2);
                
                if(!$image_result)
                {
                  die('get former image result query failed'.mysqli_error($connection));
                }

                $row = mysqli_fetch_assoc($image_result);
                $image = $row['post_image'];
                mysqli_stmt_close($stmt_2);
                
            }     
         
            move_uploaded_file($tmp_image, "../images/posts/$image");    
            
            $update_query = "UPDATE posts SET post_category_id = ?, post_title = ?, post_content = ?, post_tags = ?, post_image =  ?, post_date = ? , post_status = ?,
             post_comment_count = ?, post_views = ?, post_author = ?
            WHERE post_id = ? ";
            
            $stmt_3 = mysqli_stmt_init($connection);

            if(!mysqli_stmt_prepare($stmt_3,$update_query))
            {
                die('stmt_3 prepare failed'.mysqli_error($connection));  
            }

            if(!mysqli_stmt_bind_param($stmt_3,"issssssiisi",$category,$title,$content,$tags,$image,$date,$status,$db_post_comments,$db_post_views,$post_author,$p_id))
            {
              die('stmt_3 bind failed'.mysqli_error($connection));  
            }
            
            if(!mysqli_stmt_execute($stmt_3))
            {
              die('insert query failed '.mysqli_error($connection));
            }
   
            else
            {
              mysqli_stmt_close($stmt_3);
              header("Location: admin_posts.php");
            } 
  }
      
?>