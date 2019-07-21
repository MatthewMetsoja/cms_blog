<?php 
    // active link vars & classes
    $page_name = basename($_SERVER['PHP_SELF']);

    $admin_index = 'admin_index.php';
    $user_index = "user_index.php";
    $categories = "admin_categories.php";
    $posts = "admin_posts.php";
    $posts_add = "admin_posts.php?source=add_post";
    $users = "admin_users.php";
    $users_add = "admin_users.php?source=add_user";
    $admin_comments = "admin_comments.php";
    $user_comments ="user_comments.php";
    $admin_profile = "admin_profile.php";

    if($page_name == $admin_index)
    {
        $admin_index_class = 'active_picked';  
    }
    else
    {
        $admin_index_class = '';
    }

    if($page_name == $user_index)
    {
        $user_index_class = 'active_picked';  
    }
    else
    {
        $user_index_class = '';
    }

    if($page_name == $categories)
    {
        $categories_class = 'active_picked';  
    }
    else
    {
        $categories_class= '';
    }

    if($page_name == $posts)
    {
        $posts_class = 'active_picked';  
    }

    else if($page_name == $posts_add)
    {
        $posts_class = 'active_picked';  
    }
    else
    {
        $posts_class = '';
    }
    
    if($page_name == $users)
    {
        $users_class = 'active_picked'; 
    }
    else if($page_name == $users_add)
    {
        $users_class = 'active_picked'; 
    }
    else
    {
        $users_class = '';
    }
    
    if($page_name == $admin_comments)
    {
        $admin_comments_class = 'active_picked';  
    }
    else
    {
        $admin_comments_class = '';
    }
    
    if($page_name == $user_comments)
    {
        $user_comments_class = 'active_picked';  
    }
    else
    {
        $user_comments_class = '';
    }
    
    if($page_name == $admin_profile)
    {
        $admin_profile_class = 'active_picked';  
    }
    else
    {
        $admin_profile_class = '';
    }

?>

<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
           
    <div class="container-fluid">

            <div class="navbar-brand">
                <a id="home_link" href="../index.php">Home Page <i class="fa fa-home" aria-hidden="true"></i></a>
            </div>
           
            <ul class="nav navbar-right top-nav">
                <div class="navbar-header">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <?php echo $_SESSION['username'] ?>  <img src="../images/users/<?php echo $_SESSION['image'] ?>" width="50px" height="50px" class="top_user_pic" > 
                                <b class="caret"></b>
                            </a>
                
                            <ul class="dropdown-menu">
                                    <?php 
                                    // only show all stats to admin
                                    if($_SESSION['role'] === 'admin')
                                    { ?> 
                                        <li>
                                            <a class="<?php echo $admin_index_class ?>" href="admin_index.php"><i class="fa fa-fw fa-dashboard"></i> Admin Dashboard</a>
                                        </li>
                                    <?php 
                                    } ?>

                                        <li>
                                            <a class="<?php echo $user_index_class ?> "  href="user_index.php"><i class="fa fa-fw fa-dashboard"></i> My data</a>
                                        </li>

                                    <?php 
                                    if($_SESSION['role'] === 'admin')
                                    {  ?> 
                                        <li>
                                            <a class="<?php echo $categories_class ?> " href="admin_categories.php"><i class="fa fa-fw fa-table"></i> Admin Categories</a>
                                        </li>

                                        <li>
                                                <a  href="admin_users.php" class="<?php echo $users_class ?> "> <i class="fa fa-users"></i> View Users</a>
                                        </li>

                                        <li>
                                            <a class="<?php echo $admin_comments_class ?> " href="admin_comments.php" > <i class="fa fa-comment" aria-hidden="true"></i>Admin Comments </a>
                                        </li> 

                                    <?php 
                                    } ?>

                                    <li>
                                        <a class="<?php echo $user_comments_class ?> "  href="user_comments.php"> <i class="fa fa-comment" aria-hidden="true"></i>Comments </a>
                                    </li>

                                    <li>
                                        <a class="<?php echo $admin_profile_class ?> "  href="admin_profile.php?id=<?php echo $_SESSION['id'] ?>"> <span class="glyphicon glyphicon-sunglasses"></span> My Account </a>
                                    </li>


                                    <li>
                                        <a href="../includes/logout.php"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                                    </li>

                            </ul>
                        </li>
                </div>
            </ul>
    </div>        
</nav>  <!-- /.navbar-collapse -->