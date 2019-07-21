<?php
if($_SESSION['role'] === 'user')
{
   header('location: user_index.php'); 
}


      
      if(isset($_POST['delete_user']))
      {
          $master_password = R_E_S($_POST['master_password']);
          $master_password_confirm = R_E_S($_POST['master_password_confirm']);
        
        
          if($master_password !== $secret)
          {
            $error_msg = 'Wrong master password!';
          }
          if($master_password !== $master_password_confirm)
          {
            $error_msg = 'Passwords do not match!';
          }
          if(empty($master_password_confirm))
          {
            $error_msg = 'Password can not be left empty!';
          }
          if(empty($master_password))
          {
            $error_msg = 'Password can not be left empty!';
          }
          else if( empty($error_msg) )
          {
                $delete = R_E_S($_POST['id_to_delete']);
              
                $delete_query = "DELETE FROM users WHERE user_id = ? ";
                      
                $stmt1 = mysqli_stmt_init($connection);
          
                if(!mysqli_stmt_prepare($stmt1,$delete_query) )
                {
                  die('stmt1 prepare failed'.mysqli_error($connection));
                }
                
                // bind
                if(!mysqli_stmt_bind_param($stmt1,"i",$delete))
                {
                  die("stmt1 bind failed".mysqli_error($connection));
                }
                      
                // execute
                if(!mysqli_stmt_execute($stmt1))
                {
                  die('stmt1 execute failed '.mysqli_error($connection));
                }
                else
                {
                    mysqli_stmt_close($stmt1);
                  
                    header("Location: admin_users.php");
      
                }   
          
          }  
      }

    // make admin query 
    if(isset($_POST['change_status_submit']))
    {

            $master_password = R_E_S($_POST['master_password']);
            $master_password_confirm = R_E_S($_POST['master_password_confirm']);
        
            if($master_password !== $secret)
            {
                $error_msg = 'Wrong master password!';
            }
            if($master_password !== $master_password_confirm)
            {
                $error_msg = 'Passwords do not match!';
            }
            if(empty($master_password_confirm))
            {
                $error_msg = 'Password can not be left empty!';
            }
            if(empty($master_password))
            {
                $error_msg = 'Password can not be left empty!';
            }
            else if( empty($error_msg) )
            {
        
                $db_id = R_E_S($_POST['id']);
                
                $new_role = R_E_S($_POST['status']);
            
                $admin_query = "UPDATE users SET user_role = ? WHERE user_id = ? ";
                    
                $stmt4 = mysqli_stmt_init($connection);

                if(!mysqli_stmt_prepare($stmt4,$admin_query))
                {
                    die('stmt4 prepare failed'.mysqli_error($connection));    
                }

                if(!mysqli_stmt_bind_param($stmt4,"si",$new_role,$db_id))
                {
                    die('stmt4 bind failed'.mysqli_error($connection));    
                }
                    
                if(!mysqli_stmt_execute($stmt4))
                {
                    die('stmt4 execute failed'.mysqli_error($connection));    
                } 
                    
                mysqli_stmt_close($stmt4); 

                //now check if session role needs update, (if user is changing there own role)
                $query_sess = "SELECT user_name FROM users WHERE user_id = ? ";
                $stmt5 = mysqli_stmt_init($connection);
                
                if(!mysqli_stmt_prepare($stmt5,$query_sess))
                {
                    die('stmt5 prepare failed'.mysqli_error($connection));    
                }

                if(!mysqli_stmt_bind_param($stmt5,"i",$db_id))
                {
                    die('stmt5 bind failed'.mysqli_error($connection));    
                }
                    
                if(!mysqli_stmt_execute($stmt5))
                {
                    die('stmt5 execute failed'.mysqli_error($connection));    
                }
                    
                $result_sess = mysqli_stmt_get_result($stmt5);
                if(!$result_sess)
                {
                    die('result sess failed'.mysqli_error($connection));
                } 
                            
                $row1 = mysqli_fetch_assoc($result_sess);
                        
                $username_db = $row1['user_name'];

                // update if it is 
                if($_SESSION['username'] === $username_db)
                {  
                    $_SESSION['role'] = $new_role;
                }
                    
                mysqli_stmt_close($stmt5);           
                        
                header("Location: admin_users.php");    
    
    
            }    
    
    }
    

  ?>

<div class="col-xs-12">
    <h4 class="text-center"> All Users</h4>     
    <?php
    if(!empty($error_msg))
    { ?>
        <h2>  <div class="alert-danger text-danger text-center"> <?php echo $error_msg ?> </div>  </h2><?php
    } ?>  
    <form action="" method="post">
        <table class="table table-bordered table-hover">
            <thead>

                <th>Id</th>
                <th>Username</th>
                <th>Email</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Image</th>
                <th>Role</th>
                <th>Edit</th>
                <th>Delete</th>

            </thead>

            <tbody>
                <?php 
                $query = "SELECT * FROM users";
                $result = mysqli_query($connection,$query);
                if(!$result)
                {
                    die('result query failed'.mysqli_error($connection));
                }
                
                while($row = mysqli_fetch_assoc($result))
                {
                    $id = $row['user_id'];
                    $username = $row['user_name'];
                    $email = $row['user_email'];
                    $password = $row['user_password'];
                    $f_name = $row['user_first_name'];
                    $l_name = $row['user_last_name']; 
                    $image = $row['user_image'];
                    $role = $row['user_role'];

                    $id = R_E_S($id);

                    ?> 
                    <tr>
                            <td><?php echo $id ?> </td>
                            <td><?php echo $username ?> </td>
                            <td><?php echo $email ?> </td>
                            <td><?php echo $f_name ?> </td>
                            <td><?php echo $l_name ?> </td>
                            <td><?php echo "<img src='../images/users/$image' width='80px' height='120px' >" ?> </td> 
                            <td>
                                <?php echo $role ?> <br> 
                                <!-- change role to admin  -->
                                <form action="" method="post"> 
                                    <?php
                                    if($role === 'admin')
                                    { ?>
                                        <button type="button" data="user" value="<?php echo $id ?>" class="text text-warning change_status_modal_btn">Make user</button>
                                    <?php 
                                    }
                                    else
                                    { ?>  
                                       <button type="button" data="admin" value="<?php echo $id ?>" class="text text-warning change_status_modal_btn">Make Admin</button>
                                        <?php 
                                    }  ?>
                                </form> 
                            </td>

                            <td> <a href="admin_users.php?source=edit_user&p_id=<?php echo $id ?>"> Edit </a> </td>

                            <td>
                              <button type="button" value="<?php echo $id ?>" rel="<?php echo $image; ?>" data="<?php echo $username; ?> " class="text text-danger delete_user_modal_btn">Delete</button>
                            </td> 

                    </tr>
                    <?php 
                } ?>
            </tbody>
          
        </table>
    </form>
    <?php
    if(!empty($error_msg))
    { ?>
        <h2>  <div class="alert-danger text-danger text-center"> <?php echo $error_msg ?> </div>  </h2><?php
    } ?>       
</div>     <!--  // end <div class=col-xs-12> -->
    
