<?php 

// set message vars
$msg =
[
 'category' => '',
 'title' => '',
 'author' => '',
 'content' => '',
 'tags' => '',
 'image' => '',
 'status' => ''
];

$msg_class = 
[

    'category' => '',
    'title' => '',
    'author' => '',
    'content' => '',
    'tags' => '',
    'image' => '',
    'status' => ''

];         
?>                     

<?php
// INSERT/CREATE CATEGORY QUERY
if(isset($_POST['submit_add']))
{
   
    $category = trim($_POST['category']);
    $author = trim($_POST['username']);
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $tags = trim($_POST['tags']);
    $image = $_FILES['image']['name'];
    $tmp_image = $_FILES['image']['tmp_name'];
    $status = trim($_POST['status']);
    $time =  date("Y-m-d"); 
    $comment_count = 0;
    $view_count = 0;
   

    $content_length = strlen($content);

      if(empty($category))
      {
            $msg['category'] = 'Please choose a post category';
            $msg_class['category'] = 'text text-danger';
      }

      if(empty($title))
      {
         $msg['title'] = 'Please choose a post title';
         $msg_class['title'] = 'text text-danger';
      }

      if($content_length === 0)
      {
            $msg['content'] = 'Please put some post content';
            $msg_class['content'] = 'text text-danger';
      } 
      
      if(empty($tags))
      {
         $msg['tags'] = 'Please choose some post tags';
         $msg_class['tags'] = 'text text-danger';
      }
      
      if(empty($image))
      {
         $msg['image'] = 'Please choose a image for your post';
         $msg_class['image'] = 'text text-danger';
      }
      
      if(empty($status))
      {
         $msg['status'] = 'Please choose a status for your post';
         $msg_class['status'] = 'text text-danger';
      }    

    
      else if(!empty($category && $title && $content && $tags && $image && $status))
      { //passed
            move_uploaded_file($tmp_image, "../images/posts/$image");
            
            $add_post_query = "INSERT INTO posts(post_category_id, post_title, post_content,post_tags,post_image,post_date,post_status,post_comment_count,post_views,post_author) 
            VALUES(?, ? ,? , ? , ? , ? , ? , ? , ? ,? ) ";
            
            $stmt = mysqli_stmt_init($connection);

            if(!mysqli_stmt_prepare($stmt,$add_post_query))
            {
               die('prepare stmt failed'.mysqli_error($connection));
            }
         
            if(!mysqli_stmt_bind_param($stmt,"issssssiis", $category,$title,$content,$tags,$image,$time,$status,$comment_count,$view_count,$author))
            {
               die('bind stmt failed '.mysqli_error($connection));
            }
            if(!mysqli_stmt_execute($stmt))
            {
               die('execute stmt failed '.mysqli_error($connection));
            }
            else
            {
               mysqli_stmt_close($stmt);
               header("Location: admin_posts.php");
            }

      }

};


?>    


                     <!-- create category form -->  
                        <form action="" method="post" enctype="multipart/form-data">            
                        <div class="col-xs-12">
                            <h4 class="text-center"> Add New Post</h4>
                                         <div class="form-group">
                                           <label for=""> Post Category</label>
                                           <?php
                                            if($msg['category'] != '')
                                            {
                                               ?> <div class="<?php echo $msg_class['category'] ?>"> <?php echo $msg['category'] ?> </div> <?php 
                                            }  ?>    
                                           <select class="form-control" name="category"> 
                                            <?php
                                            if(!isset($category) || $category == '')
                                            {   // if category not already choose then show the choose category option and show all the categories  
                                               ?>  
                                               
                                                   <option value=""> ** Please Choose a Category **  </option>   <?php 
                                                    $select_cat_query = "SELECT * FROM category";
                                                    $select_cat_result = mysqli_query($connection,$select_cat_query);
                                                    if(!$select_cat_result)
                                                    {
                                                       die('select cat query failed'.mysqli_error($connection));
                                                    }

                                                    while($row = mysqli_fetch_assoc($select_cat_result))
                                                    {
                                                        $cat_id = $row['cat_id']; 
                                                        $cat_title = $row['cat_title'];
                                                        
                                                        ?> <option value="<?php echo $cat_id ?>"> <?php echo $cat_title ?> </option>
                                                       <?php 
                                                    }
                                            }
                                            else if(isset($category))
                                            {
                                                        // if category already choose then show that category as  default selected option 
                                                        $select_cat_query = "SELECT * FROM category WHERE cat_id = $category";
                                                        $select_cat_result = mysqli_query($connection,$select_cat_query);
                                                        if(!$select_cat_result)
                                                        {
                                                           die('select cat query failed'.mysqli_error($connection));
                                                        }
    
                                                        while($row = mysqli_fetch_assoc($select_cat_result))
                                                        {
                                                            $cat_id = $row['cat_id']; 
                                                            $cat_title = $row['cat_title'];
                                                            
                                                             ?> <option default value="<?php echo $cat_id ?>"> <?php echo $cat_title ?> </option>
                                                           <?php 
                                                        }

                                                           //   but show all the opther options underneath anyway just in case the user wants to changer their mind
                                                             $select_cat_query2 = "SELECT * FROM category WHERE cat_id != $category ";
                                                             $select_cat_result2 = mysqli_query($connection,$select_cat_query2);
                                                             if(!$select_cat_result2)
                                                             {
                                                                die('select cat2 query failed'.mysqli_error($connection));
                                                             }
         
                                                             while($row2 = mysqli_fetch_assoc($select_cat_result2))
                                                             {
                                                                 $cat_id2 = $row2['cat_id']; 
                                                                 $cat_title2 = $row2['cat_title'];
                                                               ?> 
                                                                <option value="<?php echo $cat_id2 ?>"> <?php echo $cat_title2 ?> </option>
                                                               <?php
                                                             }
                                            } ?>
                                                        
                    
                                                      
                                             </select>
                                           </div>
                                           <div class="form-group">
                                           <label for=""> Post Title</label>
                                       <!-- echo error messages only if we need to  -->
                                           <?php if($msg['title'] != ''){?> <div class="<?php echo $msg_class['title'] ?>"> <?php echo $msg['title'] ?> </div> <?php } ?>   
                                           <input type="text" class="form-control" name="title" value="<?php echo isset($title) ? $title : ''?> " >
                                           </div>
                                           <div class="form-group">
                                           <label for=""> Post Author</label>
                                           <select name="username"> 
                                        <option value="<?php echo $_SESSION['username'] ?>"> <?php echo $_SESSION['username'] ?> </option>       
                                        </select>
                                           </div>
                                           <div class="form-group">
                                           <label for=""> Post Content</label>
                                              <!-- echo error messages only if we need to  -->
                                           <?php if($msg['content'] != ''){?> <div class="<?php echo $msg_class['content'] ?>"> <?php echo $msg['content'] ?> </div> <?php }?>   
                                           <textarea name="content" class="form-control" rows="10">  <?php echo isset($content) ? $content : ''?> </textarea>
                                           </div>
                                           <div class="form-group">
                                           <label for=""> Post tags</label>
                                              <!-- echo error messages only if we need to  -->
                                           <?php if($msg['tags'] != ''){?> <div class="<?php echo $msg_class['tags'] ?>"> <?php echo $msg['tags'] ?> </div> <?php } ?>   
                                           <input type="text" class="form-control" name="tags"  value="<?php echo isset($tags) ? $tags : ''?> ">
                                           </div>
                                            <div class="form-group">
                                           <label for=""> Post Image</label>
                                              <!-- echo error messages only if we need to  -->
                                           <?php if($msg['image'] != ''){?> <div class="<?php echo $msg_class['image'] ?>"> <?php echo $msg['image'] ?> </div> <?php } ?>   
                                           <input type="file" class="form-control" name="image" >
                                           </div>
                                           <div class="form-group">
                                           <label for=""> Post Status</label>
                                              <!-- echo error messages only if we need to  -->
                                           <?php if($msg['status'] != ''){?> <div class="<?php echo $msg_class['status'] ?>"> <?php echo $msg['status'] ?> </div> <?php } ?>   
                                          
                                           <select name="status" id="" class="form-control">
                                             <!-- show all options anf choose status if status not already been selected and form submitted   -->
                                              <?php if(!isset($status) || $status == ''){ ?>
                                            <option value="">** Please choose status **</option>
                                            <option value="published">published</option>
                                            <option value="unpublished">unpublished </option> <?php }
                                            else if(isset($status)){?>
                                            <!-- if we have already picked a status and submitted form then keep that staus as the default and show the other unselected status as option in case user wants to change mind -->
                                                <option value default="<?php echo $status ?> "> <?php echo $status ?> </option> <?php
                                              if($status === 'published'){?>  <option value="unpublished">unpublish </option> <?php }
                                               else{ ?>   <option value="published">publish </option> <?php } } ?>
                                              
                                          </select>
                                          </div> 
                                          <div>                        
                                            <button class="btn btn-primary" type="submit" name="submit_add"> Add Post </button>
                                          
                                            </div>
                      
                      </div>
                      </form>

