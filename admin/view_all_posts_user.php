<?php
require_once "admin_includes/header.php"; 
require_once '../vendor/autoload.php'; 
require_once "functions.php";
require_once "admin_includes/navigation.php"; 

$username = $_SESSION['username']; 
?>

<div class="container-fluid" id="main_contain">

    <div class="row">
        <?php require_once "admin_includes/sidebar.php"; ?>
        
        <div class="col-sm-12 col-md-7">
            <h1 class="page-header" id="login_head"> Admin <i class="fa fa-cog"></i> </h1>
    
            <h4 class="text-center"> All my Posts </h4> 
         
            <?php 
            if(isset($_POST['checkbox_array']))
            {
              
                foreach($_POST['checkbox_array'] as $check_box_id )
                {
                
                     $bulk_options = ($_POST['bulk_options']);

                        switch($bulk_options)
                        {
                        
                            case 'published';
                                
                                $published = R_E_S('published');
                                    
                                $publish_query = "UPDATE posts SET post_status = ? WHERE post_id = ? ";

                                $stmt = mysqli_stmt_init($connection);
                                if(!mysqli_stmt_prepare($stmt,$publish_query))
                                {
                                    die('prepare stmt publish all failed'.mysqli_error($connection));    
                                }

                                if(!mysqli_stmt_bind_param($stmt,"si",$published,$check_box_id))
                                {
                                    die('bind stmt publish all failed'.mysqli_error($connection));    
                                }
                                
                                if(!mysqli_stmt_execute($stmt))
                                {
                                    die('publish checkbox query failed'.mysqli_error($connection));
                                }
                                else
                                {
                                    mysqli_stmt_close($stmt); 
                                    header("Location: admin_posts.php");
                                }
                                
                            break;

                            
                            case 'unpublished';

                                $unpublished = R_E_S('unpublished');

                                $unpublish_query = "UPDATE posts SET post_status = ? WHERE post_id = ? ";
                                
                                $stmt1 = mysqli_stmt_init($connection);
                                if(!mysqli_stmt_prepare($stmt1,$unpublish_query))
                                {
                                    die('prepare stmt1 unpublish all failed'.mysqli_error($connection));    
                                }

                                if(!mysqli_stmt_bind_param($stmt1,"si",$unpublished,$check_box_id))
                                {
                                    die('bind stmt1 unpublish all failed'.mysqli_error($connection));    
                                }

                                if(!mysqli_stmt_execute($stmt1))
                                {
                                    die('stmt1 unpublish checkbox query failed'.mysqli_error($connection));
                                }
                                else
                                {
                                    header("Location: admin_posts.php");
                                    mysqli_stmt_close($stmt1); 
                                }
                                
                            break;

                            
                            case 'delete';
                                $delete1_query = "DELETE FROM posts WHERE post_id = ? ";
                                
                                $stmt2 = mysqli_stmt_init($connection);
                                if(!mysqli_stmt_prepare($stmt2,$delete1_query))
                                {
                                    die('prepare stmt2 delete all failed'.mysqli_error($connection));    
                                }

                                if(!mysqli_stmt_bind_param($stmt2,"i",$check_box_id))
                                {
                                    die('bind stmt2 delete all failed'.mysqli_error($connection));    
                                }

                                if(!mysqli_stmt_execute($stmt2))
                                {
                                    die('delete checkbox query failed'.mysqli_error($connection));
                                }
                                else
                                {
                                    header("Location: admin_posts.php");
                                    mysqli_stmt_close($stmt2);
                                }

                            break;
                                
                                
                            case 'clone';
                                
                                $query1 = "SELECT * FROM posts WHERE post_id = ? ";
                                $stmt3 = mysqli_stmt_init($connection);
                            
                                if(!mysqli_stmt_prepare($stmt3,$query1))
                                {
                                    die('prepare stmt3 select all for clone failed'.mysqli_error($connection));    
                                }

                                if(!mysqli_stmt_bind_param($stmt3,"i",$check_box_id))
                                {
                                    die('bind stmt3 select all for clone failed'.mysqli_error($connection));    
                                }

                                if(!mysqli_stmt_execute($stmt3))
                                {
                                    die('execute stmt 3 select all query failed'.mysqli_error($connection));
                                }
                        
                                $result1 = mysqli_stmt_get_result($stmt3);
                                if(!$result1)
                                {
                                    die('result 1 clone select query failed'.mysqli_error($connection));
                                }
                                while($row = mysqli_fetch_assoc($result1))
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
                                    $date = date("Y-m-d");
                                }
                            
                                $clone_query = "INSERT INTO posts(post_category_id, post_title, post_author, post_content,post_tags,post_image,post_date,post_status,post_comment_count,post_views)
                                VALUES(?,?,?,?,?,?,?,?,?,?) ";
                            
                                $stmt4 = mysqli_stmt_init($connection);
                                if(!mysqli_stmt_prepare($stmt4,$clone_query))
                                {
                                    die('prepare clone stmt4 all failed'.mysqli_error($connection));    
                                }
                                    
                                if(!mysqli_stmt_bind_param($stmt4,"isssssssii",$post_category,$post_title,$post_author,$post_content,$post_tags,$post_image,$date,$post_status,$post_comments,$post_views))
                                {
                                    die('bind stmt4 clone all failed'.mysqli_error($connection));    
                                }
                                    
                                if(!mysqli_stmt_execute($stmt4))
                                {
                                    die('clone checkbox query failed'.mysqli_error($connection));
                                }
                                else
                                {
                                    mysqli_stmt_close($stmt3);    
                                    mysqli_stmt_close($stmt4);    
                                    header("Location: admin_posts.php"); 
                                }
                                
                            break;

                        }

                }
                
            }
          ?>
        
            <form action="" method="post">
        
            
                <div id="bulk_options_container" class="col-xs-4">
                    <select class="form-control" name="bulk_options" id="">
                        <option value=""> *** Select *** </option>
                        <option value="published">Publish</option>
                        <option value="unpublished">Unpublish</option>
                        <option value="clone">Clone</option>
                        <option value="delete">Delete</option>
                    </select>
                </div>
            
                <div class="col-xs-4">
                    <button type="submit" onclick="confirm('Are you sure you would like to do that?')" name="apply" class="btn btn-primary"> apply </button> 
                    <?php  
                        echo "<a class='btn btn-success' href='admin_posts.php?source=add_post'> Add new Post</a>" ; ?>
                </div>
                
                <table class="table table-bordered table-hover">
                    <thead>
                        <th><input type="checkbox" id="select_all_boxes"></th>
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
                        $query = "SELECT * FROM posts  LEFT JOIN category ON posts.post_category_id = category.cat_id  WHERE 
                                    post_author = '$username' ORDER BY posts.post_id DESC ";

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
                            // new data we can pull out here now as tables are joined
                            $cat_id = $row['cat_id']; 
                            $cat_title = $row['cat_title']; 
                
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
                            }    ?> 
                    
                            <tr>
                                    <td> <input type="checkbox"  class="checkBoxes" name="checkbox_array[]" id="" value="<?php echo $post_id ?>">  </td>
                                                
                                    <td> <?php echo $post_id; ?> </td>
                                        
                                    <td> <?php echo $cat_title; ?> </td> 
                                        
                                    <td> <?php echo $post_title; ?> </td>
                                                
                                    <td> 
                                            <?php echo $post_author; ?>  <br>
                                                    
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
                                            mysqli_stmt_close($stmt5); ?>


                                            <a href="../post_author.php?the_author=<?php echo $post_author ?> "> 
                                                <img src="../images/users/<?php echo $ui?>"
                                                width="50px" height="50px" class="top_user_pic" > 
                                            </a>  
                                    </td>
                                                
                                    <td> <?php echo substr($post_content,0,30);  ?> </td>
                                            
                                    <td> <?php echo $post_tags; ?> </td>

                                    <td> <?php echo "<img src='../images/posts/$post_image' width='80px' height='120px' >"; ?> </td> 
                                            
                                    <td> 
                                        <?php echo $post_views; ?> <br>
                                        <form action="" method="post">
                                            <input type="hidden" value="<?php echo $post_id ?>" name="views_id" >
                                            <button type="submit" name="view_submit" class="text text-dark"> Reset</button> 
                                        </form>
                                    </td> 
                                            
                                    <td>
                                        <?php echo $post_status; ?> <br> 
                                            <?php 
                                            if($post_status == 'published')
                                            { ?>
                                                <form action="" method="post"> 
                                                    <input type="hidden" value="<?php echo $post_id ?>" name="status_id"> 
                                                    <input type="hidden" value="unpublished" name="status"> 
                                                    <button type="submit" name="change_status" class="text text-danger"> unpublish </button>
                                                </form> <?php 
                                            }
                                            else
                                            { ?> 
                                                <form action="" method="post"> 
                                                    <input type="hidden" value="<?php echo $post_id ?>" name="status_id"> 
                                                    <input type="hidden" value="published" name="status"> 
                                                    <button type="submit" name="change_status" class="text text-success"> publish </button>  
                                                </form> <?php 
                                            } ?>
                                    </td>
                                        
                                            <?php  // select all approved comments and a link for if we would like to view comments on that post 
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

                                    <!-- only show link to view comments if they are not 0  -->
                                    <td> <?php echo $num_of_comments ?> <br> <?php if($num_of_comments !== 0){echo "<a href='../view_comments.php?comment_post_id=$comment_post_id'> view comments  </a>" ;}  ?> </td>  
                                        
                                        
                                            <?php  //  view post likes from likes tables you should join all tables in sql 
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
                                                mysqli_stmt_close($stmt10) ; ?>
                                        
                                        
                                    <td> <?php echo $num_of_likes; ?>  </td>
                                    
                                    
                                            
                                    <!-- view post link via get -->
                                    <td> <a href="../post.php?post=<?php echo $post_id ?>"> View Post </a> </td>
                                            
                                    <!-- edit post link via get -->
                                    <td> <a href="admin_posts.php?source=edit_post&p_id=<?php echo $post_id ?>"> Edit </a> </td>
                                                
                                    <!-- Delete via post on click of button with a js confirm pop up just to make sure -->
                                    <td> 
                                        <form action="" method="post" >  
                                            <input type="hidden" name="post_id" value="<?php echo $post_id ?>" >
                                            <input type="submit" name="delete" value="Delete" rel="<?php echo $post_id ?>"  class="text text-danger">
                                        </form>
                                    </td>

                            </tr>  <?php
                        } ?>
                    </tbody>
                </table>
        
            </form>
       
        </div>    

 <?php 
require_once "admin_includes/footer.php"; 
    

    // delete post query  
    if(isset($_POST['delete']))
    {

        $delete = R_E_S($_POST['post_id']);
         
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
            header("Location: admin_posts.php");
        }

    }

      
    // change post status on click 
    if(isset($_POST['change_status']))
    {
        $id = R_E_S($_POST['status_id']);
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

