<?php 
    if($_SESSION['role'] === 'user')
    {
        header('location: user_index.php'); 
    }      
 
    if(isset($_GET['p_id']))
    {

        $p_id = R_E_S($_GET['p_id']);
        
        $query = "SELECT * FROM users WHERE user_id = ? ";
        $stmt = mysqli_stmt_init($connection);
        
        if(!mysqli_stmt_prepare($stmt,$query))
        {
            die('prepare stmt failed'.mysqli_error($connection));
        }

        if(!mysqli_stmt_bind_param($stmt,"i",$p_id))
        {
            die('bind stmt failed'.mysqli_error($connection));
        }

        if(!mysqli_stmt_execute($stmt))
        {
            die('execute stmt failed'.mysqli_error($connection));
        }
        
        $result = mysqli_stmt_get_result($stmt);
        if(!$result)
        {
            die('result query failed'.mysqli_error($connection));
        }

        while($row = mysqli_fetch_assoc($result))
        {
            $db_id = $row['user_id'];
            $db_username = $row['user_name'];
            $db_email = $row['user_email'];
            $db_password = $row['user_password'];
            $db_f_name = $row['user_first_name'];
            $db_l_name = $row['user_last_name']; 
            $db_image = $row['user_image'];
            $db_role = $row['user_role'];

            ?> 
                       
            <!-- edit user form -->  
            <form action="" method="post" enctype="multipart/form-data">            
                <div class="col-xs-12">
               
                    <h4 class="text-center"> Update/Edit User</h4>

                        <div class="form-group">
                            <label for=""> Username</label>
                            <input type="text" class="form-control" name="username" value="<?php echo $db_username ?>">
                        </div>

                        <div class="form-group">
                            <label for=""> Email</label>
                            <input type="email" class="form-control" name="email" value="<?php echo $db_email ?>">
                        </div>

                        <div class="form-group">
                            <label for=""> Password</label>
                            <input type="password" class="form-control" name="password" value="<?php echo $db_password ?>">
                        </div>

                        <div class="form-group">
                            <label for=""> First name</label>
                            <input type="text" class="form-control" name="f_name" value="<?php echo $db_f_name ?>">
                        </div>

                        <div class="form-group">
                            <label for=""> Last name</label>
                            <input type="text" class="form-control" name="l_name" value="<?php echo $db_l_name ?>">
                        </div>

                        <div class="form-group">
                            <label for=""> Image</label>
                            <img src="../images/users/<?php echo $db_image?>" alt=""> <br>
                            <input type="file" class="form-control" name="image">
                        </div>

                        <div class="form-group">
                            <label for=""> Role </label>
                            <select class="form-control" name="role"> 
                                <option value=" <?php echo $db_role ?>"> <?php echo $db_role ?> </option>
                                <option value="user"> USER  </option>
                                <option value="admin"> ADMIN </option>
                            </select>
                        </div>

                        <div>                        
                            <button class="btn btn-primary" type="submit" name="submit"> Update user </button>
                        </div>

                </div>
            </form>

            <?php 
       
        } 
         mysqli_stmt_close($stmt);  
    
    }      

// <!-- UPDATE/EDIT POST QUERY  -->


    if(isset($_POST['submit']))
    {

        $username = R_E_S($_POST['username']);
        $password = R_E_S($_POST['password']);

        // only hash the password if we are going to change it// so that are already hashed password doesnt get hashed again leaving us unable to log in 
        if($password !== $db_password )
        { 
            $password = password_hash("$password", PASSWORD_BCRYPT,["cost" => 12]);
        }

        $email = R_E_S($_POST['email']);
        $f_name = R_E_S($_POST['f_name']);
        $l_name = R_E_S($_POST['l_name']);
        $image = $_FILES['image']['name'];
        $tmp_image = $_FILES['image']['tmp_name'];
        $role = R_E_S($_POST['role']);

        // get old image from db if user does not change there profile pic 
        if( empty($image))
        { 
            $image_query = "SELECT user_image FROM users WHERE user_id = ? ";
        
            $stmt_img = mysqli_stmt_init($connection);
        
            if(!mysqli_stmt_prepare($stmt_img,$image_query))
            {
                die('prepare stmt_img failed'.mysqli_error($connection));
            }
            
            if(!mysqli_stmt_bind_param($stmt_img,"i",$p_id))
            {
                die('bind stmt_img failed'.mysqli_error($connection));
            }
            
            if(!mysqli_stmt_execute($stmt_img))
            {
                die('execute stmt_img failed'.mysqli_error($connection));
            }   

            $image_result = mysqli_stmt_get_result($stmt_img);
            if(!$image_result)
            {
                die('get former image result query failed'.mysqli_error($connection));
            }

            $row = mysqli_fetch_assoc($image_result);
            $image = $row['user_image'];

            mysqli_stmt_close($stmt_img); 
        }

        // output message if for any fields get left blank
        if(empty($username) || empty($email) ||empty($f_name) ||empty($l_name) ||empty($role) )
        {
             echo "<script> alert('please do not leave any fields blank') </script>";
        }
        else // passed
        {
            move_uploaded_file($tmp_image, "../images/users/$image");    

                
            // if user is editing there own profile then update the session to show new user details 
                if( $db_username === $_SESSION['username'])
                {
                    $_SESSION['username'] = $username;  
                    $_SESSION['password'] = $password; 
                    $_SESSION['email'] = $email;  
                    $_SESSION['f_name'] = $f_name;  
                    $_SESSION['l_name'] = $l_name; 
                    $_SESSION['image'] = $image;  
                    $_SESSION['role'] = $role; 
                }
                
            // UPDATE ALL OF THE POST AUTHORS SO THAT WE ARE NOT SHOWING OLD POST AUTHOR WHO NO LONGER EXISTS 
                $update_db_query = "UPDATE posts SET post_author = ? WHERE post_author = ? ";

                $stmt_author = mysqli_stmt_init($connection);
                if(!mysqli_stmt_prepare($stmt_author,$update_db_query))
                {
                    die('prepare stmt_author failed'.mysqli_error($connection));   
                }

                if(!mysqli_stmt_bind_param($stmt_author,"ss",$username,$db_username))
                {
                    die('bind stmt_author failed'.mysqli_error($connection));   
                }

                if(!mysqli_stmt_execute($stmt_author))
                {
                    die('execute stmt_author.. update all posts where authors the same failed'.mysqli_error($connection));   
                }   

                mysqli_stmt_close($stmt_author);

            // update user prepared statement     
                $update_query = "UPDATE users SET user_name = ? , user_password = ? , user_email = ?,
                user_first_name = ?, user_last_name = ?, user_image = ?, user_role = ?
                WHERE user_id = ? ";

                $stmt_up = mysqli_stmt_init($connection);
                if(!$stmt_up)
                {
                    die('initialize stmt_up failed'.mysqli_error($connection));
                }    

                if(!mysqli_stmt_prepare($stmt_up,$update_query))
                {
                    die('prepare stmt_up failed'.mysqli_error($connection));   
                }

                if(!mysqli_stmt_bind_param($stmt_up,"sssssssi",$username,$password,$email,$f_name,$l_name,$image,$role,$p_id))
                {
                    die('bind stmt_up failed'.mysqli_error($connection));   
                }

                if(!mysqli_stmt_execute($stmt_up))
                {
                    die('execute stmt_up update db failed'.mysqli_error($connection));   
                }

                else
                {
                    mysqli_stmt_close($stmt_up);
                    header("Location: admin_users.php"); 
                }

        }
    }

