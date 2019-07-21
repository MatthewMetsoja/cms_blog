<?php 
// sign in messages
$message = '';
$class = '';

    if(isset($_GET['msg']))
    {

        $msg = mysqli_real_escape_string($connection,$_GET['msg']);

        switch($msg)
        {

            case 'email_not_found'; 
                $message = 'Email not found in system..';
                $class = 'alert alert-warning';
            break;    
    
    
            case 'login_failed'; 
                $message = 'Login failed please try again';
                $class = 'alert alert-danger';
            break;

    
            case 'login_empty';
                $message = 'Fields must not be left blank';
                $class = 'alert alert-warning';
            break;

       
            default: 
                $message = '';
                $class = '';
            break;

        }

    }
?>

    <div class="col-lg-3 col-md-4 hidden-sm">

        <?php 
        // show login form if user is not logged in but hide it if they are logged in 
        if(!isset($_SESSION['id']) || $_SESSION['id'] === '')
        {  ?>

            <!-- Blog Search Well -->
            <div class="well">
                <form action="" method="post"> 
                    <h2 class="login_head">Log in </h2>
                        
                        <div class="form-group">
                            <label for="username" class="login_head">  <h4> <i class="fas fa-user"></i>  Email </h4></label>
                            <input type="email" class="form-control" name="email" autocomplete="on"> 
                        </div> <br>
                
                        <div class="form-group">
                            <label for="password" class="login_head"> <h4>  <i class="fas fa-lock"></i>   Password </h4> </label>
                            <input type="password" class="form-control" name="password" >
                        </div> <br>
            
                        <div>
                            <button type="submit" name="login" class="btn btn-primary"> Login </button> 
                            <a href="registration.php" class="btn btn-success"> Sign up </a>
                        </div> <br>
            
            
                        <i><h4 class="text text-center"> <div class="<?php echo $class ?> "> <?php echo $message ?> </div> </h4> </i> 
                </form>
                
                <div> 
                    <a href="forgot.php?forgot=<?php echo uniqid() ?>"   id="login_head" class="forgot_btn"> 
                        Forgotten password ? 
                    </a>  
                </div>
            
            </div>  <?php 
        }
        ?>
     
            <div class="well">
                <h4 class="login_head">Blog Search</h4>
                    <form action="search.php" method="post">
                        <div class="input-group">
                            <input type="text" class="form-control" name="search">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="submit" name="submit_search">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </button>
                                </span>
                        </div>
                    </form>

                <?php  // error message if search if posted empty
                if(isset($_GET['search']))
                { ?>
                    <i> 
                        <h6 class="text text-center">
                            <div class="alert alert-warning"> Please enter something you would like to search for! </div> 
                        </h6> 
                    </i>  <?php 
                } ?>
            </div>

            
            <div class="well" >
                <h4 class="login_head">Blog Categories</h4>
            
                <div class="row">
                    <ul id="category_list" class="list-unstyled">
                        <?php     
                            $select_query = "SELECT * FROM category";
                            $select_result = mysqli_query($connection,$select_query);
                            if(!$select_result)
                            {
                                die('select query failed'.mysqli_error($connection));
                            }
                        
                            while($row = mysqli_fetch_assoc($select_result))
                            {
                                $cat_title = $row['cat_title']; 
                                $cat_id = $row['cat_id']; 
                                $loop_class = ''; 
                            
                                if(isset($_GET['category']) && $_GET['category'] == $cat_id)
                                {
                                    $loop_class = 'active_category'; 
                                } ?>
                            
                                <li class="li_category"> 
                                    <a href="category.php?category=<?php echo $cat_id ?>" class="<?php echo $loop_class ?>">
                                        <?php echo $cat_title ?> 
                                    </a> 
                                </li>  <?php 
                            } ?>
                    </ul>
                </div>
            </div>
        
            <div class="well text text-center">
                    <h4 class="login_head">Side Widget Well</h4>
                    <iframe src="https://api.humanclock.com/iframe.php?mode=amd&f=h%3D24" style="width:265px;height:285px;border:0px;margin:0px;overflow:hidden" frameborder="0" scrolling="no"></iframe>
            </div>

    </div>

</div>


<?php
    // login function
    if(isset($_POST['login']))
    {
        log_in(trim($_POST['email']),trim($_POST['password']));
    }
?>  