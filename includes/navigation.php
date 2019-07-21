<?php     
                   
                   $loop_class = '';
                   $contactPageClass = '';
                   $editPostPageClass = '';
                   $loginPageClass = '';
                   $registrationPageClass = '';
                   $adminPageClass = '';
                   // get the name of the page for the ones who are not in the loop
                   $pageName = basename($_SERVER['PHP_SELF']);

                   $home_page = 'index.php';
                   $admin_page = 'admin/admin_index.php';
                   $contact_page = 'contact.php';
                   $login_page = 'login.php';
                   $registration_page = 'registration.php';
                   $edit_post_page = "./admin/admin_posts.php?source=edit_post&p_id=$get_post_id ?>";
                    
                   if($pageName === $home_page)
                   {
                       $homePageClass = 'active';    
                   }


                   if($pageName === $login_page)
                   {
                       $loginPageClass = 'active';    
                   }

                   else if($pageName === $contact_page)
                   {
                      $contactPageClass = 'active';    
                   }
                   else if($pageName === $registration_page)
                   {
                       $registrationPageClass = 'active';    
                   }

?>


<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container-fluid">
    
            <?php
                if(isset($_SESSION['role']) && $_SESSION['role'] != '')
                {  
                    if($pageName != $home_page)
                    {?>
                        <div class="navbar-brand">
                            <a id="home_link" href="index.php">Home Page <i class="fa fa-home" aria-hidden="true"></i></a>
                        </div> 
                        <?php 
                    }
                    else
                    { ?>
                        <div class="navbar-brand">
                            <a id="home_link" href="./admin/admin_index.php">Admin <i class="fa fa-cog" aria-hidden="true"></i></a>
                        </div>
                    <?php
                    }  ?>

                    <!-- SHOW USERNAME AND PIC/PROFILE & USERS ONLINE -->
                    <?php 
                        if(isset($_SESSION['id']) && $_SESSION['id'] !== '')
                        { ?>
                                <ul class="nav navbar-right top-nav">
                                    <li id="profile_drop_down" class="dropdown">
                                        <a href="#" id="dd_arrows" class="dropdown-toggle" data-toggle="dropdown"> <?php echo $_SESSION['username'] ?> 
                                            <img src="images/users/<?php echo $_SESSION['image'] ?>" width="50px" height="50px" class="top_user_pic" >   <b class="caret"></b>
                                        </a>
                                            
                                        <ul class="dropdown-menu">
                                                <li>
                                                    <a href="./admin/admin_profile.php"><i class="fa fa-fw fa-user"></i> Profile</a>
                                                </li>
                                            <li class="divider"></li>
                                                <li>
                                                    <a href="contact.php">  <span class="glyphicon glyphicon-envelope"></span>  Contact us  </a>
                                                </li>
                                                
                                                <li class="divider"></li>
                                                
                                                <li>
                                                    <a href="includes/logout.php"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                                                </li>
                                            </ul>
                                    </li>
                                </ul>

                            <?php 
                        }  
                
                }
            
                if(!isset($_SESSION['username']) || $_SESSION['username'] == '' )
                { ?> 
                    
                    <div class="nav navbar-left top-nav">
                        
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#loop_links">
                            
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        
                    </div> 
                    <?php 
                } 

                if(!isset($_SESSION['role']) || $_SESSION['role'] == '')
                { ?>  
                        <!-- Collect the nav links, forms, and other content for toggling -->
                        <div class="collapse navbar-collapse" id="loop_links">
                            <ul class="nav navbar-nav">
                        
                                <li class="<?php echo $homePageClass ?>">
                                    <a class=" " href="index.php">Home <i class="fas fa-home    "></i></a>
                                </li>

                                <li class="<?php echo $contactPageClass ?>">
                                        <a href="contact.php"> Contact us  <span class="glyphicon glyphicon-envelope"></span>  </a>
                                </li>
                            
                                <?php
                                if(!isset($_SESSION['role']) || $_SESSION['role'] == '')
                                { ?>  
                                
                                <li class="<?php echo $loginPageClass ?>">
                                        <a href="login.php"> Login  <span class="glyphicon glyphicon-user"></span>  </a>
                                </li>
                                    
                                <li class="<?php echo $registrationPageClass ?>">
                                        <a href="registration.php"> Sign up for an account <span class="glyphicon glyphicon-pencil"></span>  </a>
                                </li>
                                <?php 
                                } 
                            
                                // show edit post if get post is set and logged in as admin
                                if(isset($_GET['post']))
                                {
                                    $get_post_id = mysqli_real_escape_string($connection,$_GET['post']); ?>
                                <li>
                                    <a href="./admin/admin_posts.php?source=edit_post&p_id=<?php echo $get_post_id ?>"> Edit post <span class="glyphicon glyphicon-pencil"></span>   </a>
                                </li>          
                                <?php 
                                }
                    
                                if(isset($_GET['comment_post_id']))
                                { ?>
                                <li>
                                    <a href="./admin/admin_posts.php">  <i class="fa fa-backward" aria-hidden="true"></i> Back to view posts  </a>
                                </li>
                                <?php
                                } ?>
                                        
                            </ul>  
                        
                        </div>  
                        <?php
                } ?>
            <!-- /.navbar-collapse  for non -logged in users only WE HAVE A NEW NAV FOR LOGGED IN THAT IS BETTER NOW -->
        </div>
        
    </nav>