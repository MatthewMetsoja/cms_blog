<?php  
require_once "includes/header.php"; 
require_once "includes/navigation.php"; 

 // message vars
    $message = 
    [
      'username' => '', 
      'email' => '' ,
      'password' => '',
      'password_confirm' => ''  
    ];     

    $message_class = 
    [
      'username' => '',
      'email' => '',
      'password' => '',
      'password_confirm' => ''
    ];
   
// CHECK FOR SUBMIT
    if(isset($_POST['submit']))
    {
        // GET FORM DATA
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $password_confirm = trim($_POST['password_confirm']);
        $email = trim($_POST['email']);

        $username = mysqli_real_escape_string($connection,$username);
        $password = mysqli_real_escape_string($connection,$password);
        $email = mysqli_real_escape_string($connection,$email);
        $f_name = mysqli_real_escape_string($connection,$f_name );
        $l_name = mysqli_real_escape_string($connection,$l_name );
        $image =  mysqli_real_escape_string($connection,$image );
        
        // output message if any fields are left blank 
        if(!isset($username) || $username === '')
        {
            $message['username'] = 'username must not be left blank'; $message_class['username'] = 'text text-danger';
        }

        if(!isset($email) || $email === '')
        {
            $message['email'] = 'email must not be left blank'; $message_class['email'] = 'text text-danger';
        }

        if(!isset($password) || $password === '')
        {
            $message['password'] = 'password must not be left blank'; $message_class['password'] = 'text text-danger';
        }
        
        if(!isset($password_confirm) || $password_confirm === '')
        {
            $message['password'] = 'password confirm must not be left blank'; $message_class['password_confirm'] = 'text text-danger';
        }
    
        if($password!== $password_confirm)
        {
            $message['password'] = 'password\'s dont match' ; $message_class['password'] = 'text text-danger';
            $message['password_confirm'] = 'password\'s dont match' ; $message_class['password_confirm'] = 'text text-danger';
        }
        else if(!empty($username) && !empty($email) && !empty($password) && !empty($password_confirm))
        {  //passed stage 1 
    
            //stage 2  
            if($username !== $_SESSION['username'])
            {
                $message['username'] = 'sorry thats not your username please try again' ; $message_class['username'] = 'text text-danger';
            }
            
            if($email !== $_SESSION['email'])
            {
                $message['email'] = 'sorry thats not your email please try again' ; $message_class['email'] = 'text text-danger';
            }

            if(!password_verify($password,$_SESSION['password']))
            {
                $message['password'] = 'sorry thats not your password' ; $message_class['password'] = 'text text-danger';
                $message['password_confirm'] = 'sorry thats not your password please try again' ; $message_class['password_confirm'] = 'text text-danger';
            }
            else
            {   // passed !
                
              //grab session id to delete likes
                $id = $_SESSION['id'];
                
                $delete = "DELETE FROM users WHERE user_name = '$username' " ;
                $delete_1 = "DELETE FROM posts WHERE post_author = '$username' ";
                $delete_2 = "DELETE FROM comments WHERE comment_author = '$username' || post_author = '$username' " ;
                $delete_3 = "DELETE FROM likes WHERE user_id = $id ";

                $delete_user = mysqli_query($connection,$delete);
                if(!$delete_user)
                {
                  die('delete failed'.mysqli_error($connection));
                }

                $delete_user_1 = mysqli_query($connection,$delete_1);
                if(!$delete_user_1)
                {
                  die('delete_1 failed'.mysqli_error($connection));
                }

                $delete_user_2 = mysqli_query($connection,$delete_2);
                if(!$delete_user_2)
                {
                  die('delete_2 failed'.mysqli_error($connection));
                }
                
                $delete_user_3 = mysqli_query($connection,$delete_3);
                if(!$delete_user_3)
                {
                  die('delete_3 account failed'.mysqli_error($connection));
                }
                else
                {
                  header("Location: includes/logout.php");
                } 


            }

   
    
        }

    }
?>       

    <!-- Page Content -->
    <div class="container" id="main_contain">
        <section id="login">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 hidden-sm"> </div>
                    
                    <div id="main_div" class="col-xs-12 col-md-6">
                        
                 
                        <h1 class="text text-center">  Are you sure you want to delete  your account <span class="glyphicon glyphicon-trash"></span> </h1>
                        <h5 class="text text-center"> If so then please fill out your details in the form below.. We will be sad to see you go </h5>
                              <form role="form" action="" method="post" id="login-form" enctype="multipart/form-data">
                                  
                                  <div class="form-group">
                                        <label for="" class="login_head"> Username</label> 
                                        <!-- output error message if we need to -->
                                        <?php
                                        if($message['username'] != '')
                                        { ?>
                                              <div class="<?php echo $message_class['username'];?> text text-center" >
                                                  <?php echo $message['username']; ?>  
                                              </div>
                                              <?php 
                                        } ?>
                                    
                                        <input type="text" name="username" id="username" class="form-control"  >
                                  </div>

                                  <div class="form-group">
                                        <label for="" class="login_head"> Email</label>
                                        <!-- output error message if we need to -->
                                        <?php
                                        if($message['email'] != '')
                                        {  ?>
                                              <div class="<?php echo $message_class['email']; ?> text text-center">
                                                  <?php echo $message['email']; ?> 
                                              </div> 
                                              <?php 
                                        } ?>
                                      
                                        <input type="email" name="email" id="email" class="form-control" >
                                  </div>

                                  <div class="form-group">
                                      <label for="password" class="login_head">Password</label>
                                      <!-- output error message if we need to -->
                                      <?php 
                                      if($message['password'] != '')
                                      { ?>
                                            <div class="<?php echo $message_class['password']; ?> text text-center" > 
                                                <?php echo $message['password']; ?> 
                                            </div> 
                                            <?php 
                                      } ?>
                                        <input type="password" name="password" id="key" class="form-control">
                                  </div>

                                  <div class="form-group">
                                      <label for="password" class="login_head">Password Confirm</label>
                                      <!-- output error message if we need to -->
                                      <?php 
                                      if($message['password'] != '')
                                      { ?>
                                          <div class="<?php echo $message_class['password']; ?> text text-center" >
                                              <?php echo $message['password']; ?> 
                                          </div> 
                                          <?php 
                                      } ?>
                                      
                                      <input type="password" name="password_confirm" id="key1" class="form-control">
                                  </div>

                              
                                  <div class="text text-center">
                                      <input type="submit" name="submit" id="btn-login" class="text text-center btn-lg btn-danger" value="Permanantly delete my account">
                                  </div>

                              </form>
                               
                              <br>
                        
                        </div>
                        
                        <div class="col-md-3 hidden-sm"> </div>
                  
                </div> <!-- /.row -->
            </div> <!-- /.container -->
        </section>

        <hr>

<?php require_once "includes/footer.php";  ?>


