
<div class="col-lg-3 col-md-4 hidden-sm">
  
    <div class="well">

            <h2 id="login_head"> Menu </h2>

            <ul class="sidebar">      
                    
                        <li>
                            <a class="<?php echo $user_index_class ?> side_link"  href="user_index.php"><i class="fa fa-fw fa-dashboard"></i> My data</a>
                        </li>
                                
                        <li>
                             
                            <a href="javascript:;" data-toggle="collapse" data-target="#posts" class="<?php echo $posts_class ?> side_link" > 
                                <i class="fa fa-pencil" aria-hidden="true"></i> Posts <i class="fa fa-fw fa-caret-down"></i>
                            </a>
                                    
                            <ul id="posts" class="collapse">

                                 <li>
                                    <a  href="admin_posts.php" class="side_link"> <i class="fa fa-eye" aria-hidden="true"></i> View all posts</a>
                                </li>
                                       
                                <li>
                                    <a class="side_link" href="admin_posts.php?source=add_post"> <i class="fa fa-plus" aria-hidden="true"></i> Add new post</a>
                               </li>
                            </ul>

                        </li>
                        
                    <?php 
                    if($_SESSION['role'] === 'admin')
                    { ?> 
                            
                        <li>
                             <a class="<?php echo $admin_index_class ?> side_link" href="admin_index.php"><i class="fa fa-fw fa-dashboard"></i> Admin Dashboard</a>
                        </li>

                        <li>
                            <a class="<?php echo $categories_class ?> side_link" href="admin_categories.php"><i class="fa fa-fw fa-table"></i> Admin Categories</a>
                        </li>

                       <li>
                            <a class="<?php echo $users_class ?> side_link"  href="javascript:;" data-toggle="collapse" data-target="#users">
                                <i class="fa fa-users" aria-hidden="true"></i>Admin Users <i class="fa fa-fw fa-caret-down"></i>
                            </a>
                                    
                            <ul id="users" class="collapse">
                                        <li>
                                            <a  href="admin_users.php" class="side_link"> <i class="fa fa-eye" aria-hidden="true"></i> View all users</a>
                                        </li>
                                        <li >
                                            <a  class="side_link" href="admin_users.php?source=add_user"> <i class="fa fa-plus" aria-hidden="true"></i> Add new user</a>
                                        </li>
                             </ul>
                        </li>

                        <li>
                            <a class="<?php echo $admin_comments_class ?> side_link" href="admin_comments.php" > <i class="fa fa-comment" aria-hidden="true"></i>Admin Comments </a>
                        </li> 

                      <?php 
                    } 
                       ?>
                         
                    <li>
                        <a class="<?php echo $user_comments_class ?> side_link"  href="user_comments.php"> <i class="fa fa-comment" aria-hidden="true"></i>Comments </a>
                    </li>

                    <li>
                        <a class="<?php echo $admin_profile_class ?> side_link"  href="admin_profile.php?id=<?php echo $_SESSION['id'] ?>"> <span class="glyphicon glyphicon-sunglasses"></span> My Account </a>
                    </li>
             </ul>
    </div>

    
    
</div>
    