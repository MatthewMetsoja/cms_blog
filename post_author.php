<?php 
require_once "includes/header.php";
require_once "includes/navigation.php";
?> 

<!-- Page Content -->
    <div class="container-fluid"  id="main_contain">

        <div class="row">

            <?php 
            if(isset($_GET['the_author']))
            {
                
                $the_author = R_E_S($_GET['the_author']); ?>

                <!-- Blog Entries Column -->
                <div class="col-lg-1 hidden-md" ></div>
                        
                <div class="col-md-7" id="main_div">

                
                    <h1 class="page-header" id="login_head" > View all Posts by <?php echo  $the_author; ?> </h1>

                    <?php 
                    $query = "SELECT * FROM posts WHERE post_author LIKE '%$the_author%' ";
                    $result = mysqli_query($connection,$query);
                    if(!$result)
                    {
                        die('select all posts from search result failed'.mysqli_error($connection));
                    }
                
                    $all_found = mysqli_num_rows($result);

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
                            <h4>  <i>  by </i> <small> <a class="post_title" > <?php echo $post_author ?> </a>  </small> </h4> 
                        </p>

                        <p> <span class="glyphicon glyphicon-time"></span> Posted on <?php echo $post_date ?> </p>
                        <br>

                        <img class="img-responsive" id="post_image" src="images/posts/<?php echo $post_image ?> " alt="">
                        <br>

                        <p class="post_text">  <?php echo substr($post_content,0,70) ?> ......</p>

                        <a class="btn btn-primary" href="post.php?post=<?php echo $post_id; ?>"> 
                            Read More <span class="glyphicon glyphicon-chevron-right"></span>
                        </a>

                        <?php 
                    }  ?>

                    <hr class="hr_big"> 

                </div>  <!--    <div class="col-md-8">  -->

                <?php
            }  ?>

             

        <!-- Blog Sidebar Widgets Column -->
    <?php include_once "includes/sidebar.php" ?> 

        <hr>

        <!-- Footer -->
    <?php include_once "includes/footer.php" ?> 