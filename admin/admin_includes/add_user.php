<?php if($_SESSION['role'] === 'user')
{
   header('location: user_index.php'); 
}                    
 
// set message vars
   // array for each field so we can output errors
   $message = [
    'username' => '', 
    'email' => '',
    'password' => '',
    'f_name' => '', 
    'l_name' => '' ,
    'image' => '',
    'role' => ''
];     

$message_class = [
    'username' => '',
    'email' => '',
    'password' => '',
    'firstname' => '',
    'lastname' => '',
    'image' => '',
    'role' => ''
];


         
                  //  <!-- INSERT/CREATE CATEGORY QUERY -->

    if(isset($_POST['submit_add']))
    {
            
            $username = R_E_S($_POST['username']);
            $password = R_E_S($_POST['password']);
            $email = R_E_S($_POST['email']);
            $f_name = R_E_S($_POST['f_name']);
            $l_name = R_E_S($_POST['l_name']);
            $image = $_FILES['image']['name'];
            $tmp_image = $_FILES['image']['tmp_name'];
            $role = R_E_S($_POST['role']);

           

         // output message if any fields are left blank
         
         if(!isset($password) || $password === '')
         {
            $message['password'] = 'Password must not be left blank'; 
            $message_class['password'] = 'text text-danger';
         }
         
         if(!isset($username) || $username === '')
         {
            $message['username'] = 'Username must not be left blank'; 
            $message_class['username'] = 'text text-danger';
         }
         
         if(!isset($email) || $email === '')
         {
            $message['email'] = 'Email must not be left blank'; 
            $message_class['email'] = 'text text-danger';
         }
        
         if(!isset($f_name) || $f_name === '')
         {
           $message['firstname'] = 'First name must not be left blank'; 
           $message_class['firstname'] = 'text text-danger';
         }
         
         if(!isset($l_name) || $l_name === '')
         {
           $message['lastname'] = 'Last name must not be left blank'; 
           $message_class['lastname'] = 'text text-danger';
         }
         
         if(!isset($image) || $image === '')
         {
           $message['image'] = 'Please choose a profile picture'; 
           $message_class['image'] = 'text text-danger';
         }
         
         if(!isset($role) || $role === '' || $role == '** Please Choose a User Role **')
         {
           $message['role'] = 'Please choose a user role '; 
           $message_class['role'] = 'text text-danger';
         }
       
         // if username is 4 characters or less tell the user we need 4 or more  
         if(isset($username) && (strlen($username) < 4 ))
         {
           $message['username'] = 'Username Must be 4 or more characters long'; 
           $message_class['username'] = 'text text-danger';
         }
           
          // if chosen password is 5 characters or less tell the user we need 5 or more    
          if(strlen($password) < 5 )
          {
            $message['password'] = 'Password Must be 5 or more characters long'; 
            $message_class['password'] = 'text text-danger';
          }
              
          //then check if username already exists
          if(username_exists($username))
          {
            $message['username'] = 'Sorry that username is already taken please choose another'; 
            $message_class['username'] = 'text text-danger';
          }

          // then check if email already exists    
          if(email_exists($email))
          {
            $message['email'] = "There is already someone registered with that email please click the <a href =''> link </a> to reset or pick another"; 
            $message_class['email'] = 'text text-danger';
          }  
         
         
          // passed if they are not all empty and pass checks  
          else if(empty($message['username']) && empty($message['email']) && empty($messgae['password'])
           && empty($messgae['f_name']) && empty($messgae['l_name']) && empty($messgae['image']) && empty($message['role']))
          {
          
                move_uploaded_file($tmp_image, "../images/users/$image");  
              
                // now  we can hash the password
                $password = password_hash($password,PASSWORD_BCRYPT,["cost" => 12 ]);

          
                $query = "INSERT INTO users(user_name, user_email, user_password, user_first_name,user_last_name,user_image,user_role)
                VALUES(?,?,?,?,?,?,?) ";

                
                $stmt = mysqli_stmt_init($connection);
                if(!$stmt)
                {
                  die("stmt initialization failed".mysqli_error($connection).mysqli_connect_error($connection));
                }

                if(!mysqli_stmt_prepare($stmt,$query))
                {
                  die('prepare stmt failed '.mysqli_error($connection));
                }

                if(!mysqli_stmt_bind_param($stmt,"sssssss",$username,$email,$password,$f_name,$l_name,$image,$role))
                {
                  {die('prepare stmt failed '.mysqli_error($connection));}
                }
            
                if(!mysqli_stmt_execute($stmt))
                {
                  die('execute stmt failed'.mysqli_error($connection));
                }
                else
                {
                  header("Location: admin_users.php"); 
                }
          }
    }


?>       

               <!-- create category form -->  

<form action="" method="post" enctype="multipart/form-data">            
<div class="col-xs-12">
<h4 class="text-center"> Add New User</h4>

<div class="form-group">
      <label for=""> Username</label>
       <!-- output error message if we need to -->
       <?php if($message['username'] != ''){?> <div class="<?php echo $message_class['username']?> text text-center" > <?php echo $message['username'] ?>  </div> <?php } ?>
     <input type="text" class="form-control" name="username" value=" <?php echo isset($_POST['username']) ? $username : '' ?> ">
      </div>
      <div class="form-group">
     <label for=""> Email</label>
       <!-- output error message if we need to -->
       <?php if($message['email'] != ''){?> <div class="<?php echo $message_class['email']?> text text-center" > <?php echo $message['email'] ?> </div> <?php } ?>
      <input type="email" class="form-control" name="email" value=" <?php echo isset($_POST['email']) ? $email : '' ?> ">
      </div>
      <div class="form-group">
      <label for=""> Password</label>
       <!-- output error message if we need to -->
       <?php if($message['password'] != ''){?> <div class="<?php echo $message_class['password']?> text text-center" > <?php echo $message['password'] ?> </div> <?php } ?>
        <input type="password" class="form-control" name="password">
      </div>
      <div class="form-group">
      <label for=""> First name</label>
      <!-- output error message if we need to -->
      <?php if($message['firstname'] != ''){?> <div class="<?php echo $message_class['firstname']?> text text-center" > <?php echo $message['firstname'] ?>  </div> <?php } ?>
       <input type="text" class="form-control" name="f_name" value=" <?php echo isset($_POST['f_name']) ? $f_name : '' ?> " >
      </div>
      <div class="form-group">
      <label for=""> Last name</label>
       <!-- output error message if we need to -->
       <?php if($message['lastname'] != ''){?> <div class="<?php echo $message_class['lastname']?> text text-center" > <?php echo $message['lastname'] ?>  </div> <?php } ?>
      <input type="text" class="form-control" name="l_name"  value=" <?php echo isset($_POST['l_name']) ? $l_name : '' ?> " >
      </div>
      <div class="form-group">
      <label for=""> Image</label>
        <!-- output error message if we need to -->
      <?php if($message['image'] != ''){?> <div class="<?php echo $message_class['image']?> text text-center" > <?php echo $message['image'] ?>  </div> <?php } ?>
      <input type="file" class="form-control" name="image"  value=" <?php echo isset($_POST['image']) ? $image : ''  ?> ">
      </div>
      <div class="form-group">
      <label for=""> Role </label>
        <!-- output error message if we need to -->
        <?php if($message['role'] != ''){?> <div class="<?php echo $message_class['role']?> text text-center" > <?php echo $message['role'] ?>  </div> <?php } ?>
        <select class="form-control" name="role"> 
          
        <option value=" <?php echo isset($_POST['role']) ? $role : ''  ?> ">** Please Choose a User Role **</option>
        <option value="user"> USER  </option>
        <option value="admin"> ADMIN </option>
        </select>
      </div>
    
    <div>                        
      <button class="btn btn-primary" type="submit" name="submit_add"> Add User </button>
      </div>
     </div>  

</div>
</form>

