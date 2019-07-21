<?php 

   if(!$connection)
   {
      require_once '../includes/db.php'; 
   } 

    // check if we have posted something if we have convert it to upper case and return true
      function ifItIsMethod($method = null)
      {
         if($_SERVER['REQUEST_METHOD'] == strtoupper($method))
         {
            return true;
         }else
         {
            return false;
         }
      
      }

   // check if we have set session role to see if user is logged in 
   function isLoggedIn()
   {
      if(isset($_SESSION['role']))
      {
         return true;
      }
      else
      {
         return false;
      }
     
   }


   // redirect the user the user to our chosen location if they are logged in 
   function checkIfUserIsLoggedInAndRedirect($redirect_location)
   {
      if(isLoggedIn())
      {
         redirect($redirect_location);
      }
   }


   // redirect function
   function redirect($location)
   {
      return header("Location:".$location);
   }

 
   // real escape string and trim out blank spaces
   function R_E_S($string)
   {
      global $connection;
      return mysqli_real_escape_string($connection,trim($string));
   }


   // see if user is admin
   function is_admin($username)
   {
      global $connection;

      $query = "SELECT user_role FROM users WHERE user_name = '$username' "; 
      $result = mysqli_query($connection,$query);
      
      if(!$result)
      {
         die("is_admin query failed".mysqli_error($connection));
      }

      $row = mysqli_fetch_assoc($result);

      if($row['user_role'] == 'admin')
      {
         return true ;
      }
      else
      {
         return false;
      }

   }


   // see if username exists when we get people signing up
   function username_exists($username)
   {
      global $connection;
      
      //  we are just testing for num rows to see if usernames there   
      $query = "SELECT user_name FROM users WHERE user_name = '$username' "; 
         $result = mysqli_query($connection,$query);
         
         if(!$result)
         {
            die("does username already exist query failed".mysqli_error($connection));
         }
         
      $rows = mysqli_num_rows($result);
         
      if($rows !== 0)
      {
         // username already exists      
         return true ;
      }
      else
      {
         // username is available     
         return false;
      } 

   }


   // see if email exists when we get people signing up
   function email_exists($email)
   {
      global $connection;
   
      $query = "SELECT user_email FROM users WHERE user_email = '$email' "; 
         $result = mysqli_query($connection,$query);
         if(!$result)
         {
            die("does username already exist query failed".mysqli_error($connection));
         }
         
      $rows = mysqli_num_rows($result);
         
      if($rows !== 0)
      {
         // email already exists      
         return true ;
      }
      else
      {
         // email is available     
            return false;
      } 
   
   }
  

   // admin_index function for widget dynamic display 
   function widget_numbers1($table)
   {
      global $connection;

      $query = "SELECT * FROM $table";
      $result = mysqli_query($connection,$query);
         
      if(!$result)
      {
         die("widget numbers query failed".mysqli_error($connection));
      }
         
      return mysqli_num_rows($result);

   }

 
   // admin_index function for widget dynamic display with WHERE case
   function widget_numbers($table,$column,$status)
   {
      global $connection;

      R_E_S($status);

      $query = "SELECT * FROM $table WHERE $column = '$status' ";
      $result = mysqli_query($connection,$query);
      if(!$result){die("widget numbers query failed".mysqli_error($connection));}
      return mysqli_num_rows($result);

   }


  function log_in($email,$password)
  {

      global $connection;

      $email = mysqli_real_escape_string($connection,trim($email));
      $password = mysqli_real_escape_string($connection,trim($password));



      if(empty($email) || empty($password))
      {
         // failed....  //SWITCH STATEMENT FOR THESE ?MESG= GETS IS IN SIDEBAR.PHP INCLUDES 
         header("Location: index.php?msg=login_empty");   
      }
      if(!email_exists($email))
      {
         header("Location: index.php?msg=email_not_found");   
      }
      else
      { 
            $query = "SELECT * FROM users WHERE user_email = '$email' ";
            $result = mysqli_query($connection,$query);
   
            if(!$result)
            {
               die('result query failed'.mysqli_error($connection));
            }

            while($row = mysqli_fetch_assoc($result))
            {
                  $db_id = $row['user_id'];
                  $db_username = $row['user_name'];
                  $db_password = $row['user_password'];
                  $db_email = $row['user_email'];

                  $db_f_name = $row['user_first_name'];
                  $db_l_name = $row['user_last_name']; 
                  $db_image = $row['user_image'];
                  $db_role = $row['user_role'];
            }
            // login passed   
            if(password_verify($password,$db_password))
            {
                  $_SESSION['id'] = $db_id;  
                  $_SESSION['username'] = $db_username;  
                  $_SESSION['password'] = $db_password; 
                  $_SESSION['email'] = $db_email;  
                  $_SESSION['f_name'] = $db_f_name;  
                  $_SESSION['l_name'] = $db_l_name; 
                  $_SESSION['image'] = $db_image;  
                  $_SESSION['role'] = $db_role; 
               
                  // redirect admin to thier homepage
                  if($db_role === 'admin')
                  {
                     header("Location: admin/admin_index.php");
                  }
                  else
                  {
                     //  and redirect users to thiers
                     header("Location: admin/user_index.php");
                  }

            }
            else
            {
               // login failed redirect to homepage and output message   
               header("Location: index.php?msg=login_failed");
            }

      }

  } 


?>