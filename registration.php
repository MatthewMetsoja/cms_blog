<?php  
require_once "includes/header.php"; 
require_once "includes/navigation.php"; 
require_once 'vendor/autoload.php'; 

//pusher settings 
 $options = array(
    'cluster' => 'eu',
    'useTLS' => true
  ); 
 
  $pusher = new Pusher\Pusher(getenv('APP_KEY'),getenv('APP_SECRET'),getenv('APP_ID'),$options);

 //.env 
$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();


    // language setting
    if(isset($_GET['lang']) && $_GET['lang'] != '') 
    {

        $_SESSION['lang'] = $_GET['lang'];

        // refresh page via js if user changes language
        if(isset( $_SESSION['lang']) &&  $_SESSION['lang'] != $_GET['lang'])
        {
            // header("location: registration.php");
            echo "<script type='text/javascript'>location.reload();</script> ";
        }
            
            // add the file of the language we need by concatenating session
        if(isset($_SESSION['lang']) && $_SESSION['lang'] == 'en')
        {
            include "includes/languages/en.php";
        }
        else if(isset($_SESSION['lang']) && $_SESSION['lang'] == 'es')
        {
            include "includes/languages/spanish.php";
        } 
            
    }
    else
    {
        include "includes/languages/en.php"; 
    }



   // error message vars
   $message = 
   [
        'username' => '', 
        'email' => '' ,
        'password' => '',
        'f_name' => '', 
        'l_name' => '' ,
        'image' => '',
        'success' => ''
    ];     

    $message_class = 
    [
        'username' => '',
        'email' => '',
        'password' => '',
        'firstname' => '',
        'lastname' => '',
        'image' => '',
        'success' => ''
    ];
   
    // CHECK FOR SUBMIT
    if(isset($_POST['submit']))
    {
        // GET FORM DATA
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $password_confirm = trim($_POST['password_confirm']);
        $email = trim($_POST['email']);
        $f_name = trim($_POST['f_name']);
        $l_name = trim($_POST['l_name']);
        $image = trim($_FILES['image']['name']);
        $tmp_image = $_FILES['image']['tmp_name'];
        
        $username = mysqli_real_escape_string($connection,$username);
        $password = mysqli_real_escape_string($connection,$password);
        $email = mysqli_real_escape_string($connection,$email);
        $f_name = mysqli_real_escape_string($connection,$f_name );
        $l_name = mysqli_real_escape_string($connection,$l_name );
        $image =  mysqli_real_escape_string($connection,$image );
        
        // output message if any fields are left blank 
        if(!isset($username) || $username === '')
        {
            $message['username'] = 'username must not be left blank';
            $message_class['username'] = 'text text-danger';
        }
        
        if(!isset($email) || $email === '')
        {
            $message['email'] = 'email must not be left blank';
            $message_class['email'] = 'text text-danger';
        }

        if(!isset($password) || $password === '')
        {
            $message['password'] = 'password must not be left blank';
            $message_class['password'] = 'text text-danger';
        }

        if(!isset($f_name) || $f_name === '')
        {
            $message['firstname'] = 'first name must not be left blank'; 
            $message_class['firstname'] = 'text text-danger';
        }

        if(!isset($l_name) || $l_name === '')
        {
            $message['lastname'] = 'last name must not be left blank';
            $message_class['lastname'] = 'text text-danger';
        }

        if(!isset($image) || $image === '')
        {
            $image  = "default.png"; 
            $tmp_image = $img; 
        }
        
        if(strlen($username) < 4 )
        {
            $message['username'] = 'Username Must be 4 or more characters long'; 
            $message_class['username'] = 'text text-danger';
        }

        if(strlen($password) < 5 )
        {
            $message['password'] = 'Password Must be 5 or more characters long';
            $message_class['password'] = 'text text-danger';  
        }

        if(username_exists($username))
        {
            $message['username'] = 'Sorry that username is already taken please choose another';
            $message_class['username'] = 'text text-danger';
        }

         
        if(email_exists($email))
        {
            $message['email'] = "There is already someone registered with that email please click the <a href =''> link </a> to reset or pick another";
            $message_class['email'] = 'text text-danger';
        }  

        if($password !== $password_confirm)
        {
            $message['password'] = 'password\'s dont match' ; 
            $message_class['password'] = 'text text-danger';
        }
        else if(!empty($username) && !empty($email) && !empty($password) && !empty($f_name) && !empty($l_name))    // passed  
        { 
            //now hash the password 
            $password = password_hash($password,PASSWORD_BCRYPT,["cost" => 12 ]);

            move_uploaded_file($tmp_image, "images/users/$image");  

            $query = "INSERT INTO users(user_name, user_email, user_password, user_first_name, user_last_name ,user_image)
            VALUES(?,?,?,?,?,?) ";
            
            $stmt = mysqli_stmt_init($connection);
            
            if(!mysqli_stmt_prepare($stmt,$query))
            {
                die('prepare stmt failed'.mysqli_error($connection));    
            }

            if(!mysqli_stmt_bind_param($stmt,"ssssss",$username,$email,$password,$f_name,$l_name,$image))
            {
                die('bind stmt failed'.mysqli_error($connection));    
            }

            if(!mysqli_stmt_execute($stmt))
            {
                die('execute stmt failed'.mysqli_error($connection));
            }
                
            $message['success'] = 'Thanks for signing up you will recieve a verification email soon with your login details';
            $message_class['success'] = 'alert alert-success';   
                
          
             $data['message'] = $username; // must be an array or toastr will return
             
             // pusher notification ready for js bind in admin_index
            $pusher->trigger('notifications','new_user',$data);
        
        }

    }

?>       


<!-- Page Content -->
<div class="container">

    <form class="navbar-form navbar-right" action=""  id="lang_form">
        <div class="form-group"> 
            <select name="lang" method="get" class="form-control" onchange="changeLang()"> 
            <!-- // set default  with php if we have set a session language  -->
            <option value="en" <?php  if(isset($_SESSION['lang']) && $_SESSION['lang'] == 'en'){echo "selected";} ?>  >English</option>
                <option value="es" <?php  if(isset($_SESSION['lang']) && $_SESSION['lang'] == 'es'){echo "selected";} ?>  >Spanish</option>
            </select>
        </div>
    </form> 


    <section id="login">
        <div class="container" id="main_contain">
            <div class="row">
                <div class="col-md-3 hidden-sm"> </div>

                <div id="main_div" class="col-xs-12 col-md-6">
                    
                  

                        <h1 class="login_head"> <?php echo _REGISTER; ?> </h1>
                        
                        <form role="form" action="" method="post" id="login-form" enctype="multipart/form-data">
                                <div class="form-group">
                                        <label for="" class="login_head"> <?php echo _USERNAME; ?> </label> 
                                        <!-- output error message if we need to -->
                                    <?php 
                                    if($message['username'] != '')
                                    { ?> 
                                        <div class="<?php echo $message_class['username']; ?> text text-center" > 
                                            <?php echo $message['username']; ?>  
                                        </div> <?php 
                                    } ?>
                                        
                                        <input type="text" name="username" id="username" class="form-control"  autocomplete="on" value="<?php echo isset($username) ? trim($username) : '' ; ?> " >

                                </div>

                                <div class="form-group">
                                        <label for="" class="login_head"> <?php echo _EMAIL; ?> </label>
                                        <!-- output error message if we need to -->
                                    <?php 
                                    if($message['email'] != '')
                                    { ?> 
                                        <div class="<?php echo $message_class['email']; ?> text text-center"> 
                                            <?php echo $message['email']; ?> 
                                        </div> <?php 
                                    } ?>
                                    
                                        <input type="email" name="email" id="email" class="form-control"  autocomplete="on" value="<?php echo isset($email) ? trim($email) : ' '  ; ?> " >
                                </div>
                                
                                <div class="form-group">
                                        <label for="password" class="login_head">  <?php echo _PASSWORD; ?> </label>
                                    <!-- output error message if we need to -->
                                    <?php if($message['password'] != '')
                                    { ?>
                                        <div class="<?php echo $message_class['password']; ?> text text-center">
                                            <?php echo $message['password']; ?> 
                                        </div> <?php 
                                    } ?>
                                    
                                        <input type="password" name="password" id="key" class="form-control">
                                </div>

                                <div class="form-group">
                                        <label for="password" class="login_head"><?php echo _PASSWORD_CONFIRM; ?> </label>
                                        <!-- output error message if we need to -->
                                    <?php 
                                    if($message['password'] != '')
                                    { ?> 
                                        <div class="<?php echo $message_class['password']; ?> text text-center">
                                            <?php echo $message['password']; ?> 
                                        </div> <?php 
                                    } ?>
                                        
                                        <input type="password" name="password_confirm" id="key1" class="form-control">
                                </div>

                                <div class="form-group">
                                        <label for="" class="login_head"> <?php echo _FIRST_NAME; ?> </label>
                                        <!-- output error message if we need to -->
                                    <?php 
                                    if($message['firstname'] != '')
                                    { ?> 
                                        <div class="<?php echo $message_class['firstname'];?> text text-center">
                                            <?php echo $message['firstname']; ?>  
                                        </div> <?php 
                                    } ?>
                                        
                                        <input type="text" class="form-control" name="f_name" autocomplete="on" value="<?php echo isset($f_name) ? trim($f_name) : '' ; ?> " >
                                </div>

                                <div class="form-group">
                                        <label for="" class="login_head"> <?php echo _LAST_NAME; ?> </label>
                                        <!-- output error message if we need to -->
                                    <?php 
                                    if($message['lastname'] != '')
                                    { ?> 
                                        <div class="<?php echo $message_class['lastname']; ?> text text-center"> 
                                            <?php echo $message['lastname']; ?>  
                                        </div> <?php 
                                    } ?>
                                        
                                        <input type="text" class="form-control" name="l_name"  autocomplete="on" value="<?php echo isset($l_name) ? trim($l_name) : '' ; ?> ">
                                </div>
                            
                                <div class="text text-center">
                                        <input type="submit" name="submit" id="btn-login" class="text text-center btn-lg btn-primary" value=" <?php echo _REGISTER; ?>">
                                </div>
                        </form>
                        
                        <br>
                    
                </div>
               
                <div class="col-md-3 hidden-sm"> </div>
                
            </div> <!-- /.col-main -->
        
        </div> <!-- /.container -->
    </section>

    <div class="col-xs-12">
        
        <?php
        // ECHO  BIG GREEN MESSAGE HERE IF THE FORM HAS BEEN SUCCESSFULLY FILLED OUT..
        if($message['success'] != '')
        { ?>
                <h4 class="text text-center"> 
                    <div class=" <?php echo $message_class['success']; ?>">
                        <?php echo $message['success']; ?>
                    </div>  
                </h4> <?php 
        } ?>  
    </div>  

    <hr>

<script>
    
    function changeLang()
    {
        $("#lang_form").submit();
    }

</script>


<?php   require_once "includes/footer.php";  ?>


