<?php 
require_once "admin_includes/header.php";
require_once '../vendor/autoload.php'; 
require_once "functions.php";
require_once "admin_includes/navigation.php"; 
?>


                                             
<!-- UPDATE/EDIT POST QUERY  -->                   
<?php 
$old_username = $_SESSION['username'] ;

  if(isset($_POST['submit']))
  {
    
      $username = trim($_POST['username']);
      $password = trim($_POST['password']);
      
      $username = mysqli_real_escape_string($connection,$username);
      $password = mysqli_real_escape_string($connection,$password);

      if($password !== $_SESSION['password'] )
      { 
          $password = password_hash("$password", PASSWORD_BCRYPT,["cost" => 12]);
      }

      $email = trim($_POST['email']);
      $f_name = trim($_POST['f_name']);
      $l_name = trim($_POST['l_name']);
      $image = $_FILES['image']['name'];
      $tmp_image = $_FILES['image']['tmp_name'];
    
      // set the post role as the same if we are hiding it from users whithout admin status 
      $role = $_POST['role'];
      
      if(empty($role))
      {
        $role = $_SESSION['role'];
      };

      $email = mysqli_real_escape_string($connection,$email);
      $f_name = mysqli_real_escape_string($connection,$f_name);
      $l_name = mysqli_real_escape_string($connection,$l_name);
      $image = mysqli_real_escape_string($connection,$image);

      if( empty($image))
      { 
        $image = $_SESSION['image'];
      }

    
        if(empty($username) || empty($email) ||empty($f_name) ||empty($l_name) || empty($role))
        {
          echo "<script> alert('please do not leave any fields blank') </script>";
        }
        else
        {
            move_uploaded_file($tmp_image, "../images/users/$image");    
          
            $id = R_E_S($_SESSION['id']); 

            $_SESSION['username'] = $username;  
            $_SESSION['password'] = $password; 
            $_SESSION['email'] = $email;  
            $_SESSION['f_name'] = $f_name;  
            $_SESSION['l_name'] = $l_name; 
            $_SESSION['image'] = $image;  
            $_SESSION['role'] = $role;  

            // UPDATE PROFILE QUERY    
            $update_query = "UPDATE users SET user_name = ? , user_password = ?, user_email = ?,
            user_first_name = ? , user_last_name = ? , user_image = ? , user_role = ?
            WHERE user_id = ? ";
          
            $stmt = mysqli_stmt_init($connection);
            if(!$stmt)
            {
              die("stmt initialization failed".mysqli_error($connection).mysqli_connect_error($connection));
            }

            if(!mysqli_stmt_prepare($stmt,$update_query))
            {
              die('prepare stmt failed '.mysqli_error($connection));
            }

            if(!mysqli_stmt_bind_param($stmt,"sssssssi",$username,$password,$email,$f_name,$l_name,$image,$role,$id))
            {
              die('prepare stmt failed '.mysqli_error($connection));
            }

            if(!mysqli_stmt_execute($stmt))
            {
              die('execute stmt failed'.mysqli_error($connection));
            }
            else
            {
              mysqli_stmt_close($stmt);
            }

          
            // UPDATE POSTS (post author) ->  Db POST AUTHORS SO THAT WE ARE NOT SHOWING FORMER AUTHOR/USERNAME WHO NO LONGER EXISTS 
            $new_username = R_E_S($_SESSION['username']);
           
            $update_db_query = "UPDATE posts SET post_author = ? WHERE post_author = ? ";
            $stmt1 = mysqli_stmt_init($connection);
      
            if(!$stmt1)
            {
              die("stmt1 initialization failed".mysqli_error($connection).mysqli_connect_error($connection));
            }

            if(!mysqli_stmt_prepare($stmt1,$update_db_query))
            {
              die('prepare stmt1 failed '.mysqli_error($connection));
            }

            if(!mysqli_stmt_bind_param($stmt1,"ss",$new_username,$old_username))
            {
              die('prepare stmt1 failed '.mysqli_error($connection));
            }
        
            if(!mysqli_stmt_execute($stmt1))
            {
              die('execute stmt1 failed'.mysqli_error($connection));
            }
            else
            {
                mysqli_stmt_close($stmt1);
                header("Location: admin_index.php");
            }

        }
  }
?>






         
<div class="container-fluid" id="main_contain">

    <div class="row">
    <?php require_once "admin_includes/sidebar.php"; ?>
                   
      <div class="col-sm-12 col-md-7">
        <h1 class="page-header" id="login_head"> Admin Profile </h1>
                      
          <!-- edit user form -->  
              <div class="col-xs-12">
                <h4 class="text-center"> Update/Edit User</h4>
                 
                <form action="" method="post" enctype="multipart/form-data">        
                  
                      <div class="form-group">
                        <label for=""> Username</label>
                        <input type="text" class="form-control" name="username" value="<?php echo $_SESSION['username'] ?>">
                      </div>

                      <div class="form-group">
                        <label for=""> Email</label>
                        <input type="email" class="form-control" name="email" value="<?php echo $_SESSION['email'] ?>">
                      </div>

                      <div class="form-group">
                        <label for=""> Password</label>
                        <input type="password" class="form-control" name="password" value="<?php echo $_SESSION['password'] ?>">
                      </div>
                      
                      <div class="form-group">
                        <label for=""> First name</label>
                        <input type="text" class="form-control" name="f_name" value="<?php echo $_SESSION['f_name'] ?>">
                      </div>

                      <div class="form-group">
                        <label for=""> Last name</label>
                        <input type="text" class="form-control" name="l_name" value="<?php echo $_SESSION['l_name'] ?>">
                      </div>

                      <div class="form-group">
                        <label for=""> Image</label> <br>
                        <img src="../images/users/<?php echo $_SESSION['image'] ?>" width="150px"> <br>
                        <input type="file" class="form-control" name="image">
                      </div>

                      <div class="form-group">     <!-- hide choose role from users without admin status -->
                        <?php 
                      if($_SESSION['role'] == 'admin')
                      { ?>
                            <label for=""> Role </label>
                            <select class="form-control" name="role"> 
                                <option value=" <?php echo $_SESSION['role']?>"> <?php echo $_SESSION['role'] ?> </option>
                                <option value="user"> USER  </option>
                                <option value="admin"> ADMIN </option>
                            </select>
                      </div>
                        <?php 
                      } ?>
                                  
                      <div>                        
                        <button class="btn btn-primary" type="submit" name="submit"> Update Profile </button>
                        <a href="../delete_account.php" class="btn btn-danger"> Delete Account </a>
                      </div>

            
                </form>
            </div>
                       

    </div>
             

 <?php include_once "admin_includes/footer.php" ?>
