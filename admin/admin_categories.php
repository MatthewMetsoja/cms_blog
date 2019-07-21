<?php 
require_once "admin_includes/header.php";
require_once '../vendor/autoload.php'; 
require_once "functions.php"; 
require_once "admin_includes/navigation.php";


$dotenv = Dotenv\Dotenv::create('../');
$dotenv->load();

if($_SESSION['role'] === 'user')
{
   header('location: user_index.php'); 
}    

$master = getenv('MASS_PASS'); 

$message = '' ;
$message_class = '';


if(isset($_POST['submit_add']))
{
     $category_name = $_POST['category_title'];
     $pass= trim($_POST['p_add']);
     $pass_con = trim($_POST['p_c_add']);
    
     if(empty($category_name))
     {
       $message = "You must choose a category name!";
       $message_class = "text text-danger";   
     }

     if($pass != $pass_con)
     {
       $message = 'error: passwords dont match';
       $message_class = 'text text-danger';    
     }

     if($pass != $master)
     {
        $message = 'error: wrong password ';
        $message_class = 'text text-danger'; 
     }
     else
     { 
        $stmt = mysqli_stmt_init($connection);

        if(!mysqli_stmt_prepare($stmt, "INSERT INTO category(cat_title) VALUES(?) ") )
        {
          die('stmt prepare failed'.mysqli_error($connection));
        }
            
        if(!mysqli_stmt_bind_param($stmt,"s",$category_name))
        {
          die("stmt bind failed".mysqli_error($connection));
        }
            
        if(!mysqli_stmt_execute($stmt))
        {
          die('stmt execute insert query failed '.mysqli_error($connection));
        }
        else
        {
           mysqli_stmt_close($stmt);
           header("Location: admin_categories.php");
        }
        
     }
}   




if(isset($_POST['submit_edit']))
{
           
         $new_cat_title = trim($_POST['new_title']);
         $old_cat_id = $_POST['old_id'];

  

         $p_edit = mysqli_real_escape_string($connection,trim($_POST['p_edit']));
         $p_c_edit = mysqli_real_escape_string($connection,trim($_POST['p_c_edit']));
       
         if(strlen($new_cat_title) == 0 )
         {
            $message = "You left category name blank!";
            $message_class = "text text-danger";   
         }

         else if($p_edit != $p_c_edit)
         {
             $message = 'error: passwords dont match';
             $message_class = 'text text-danger';    
         }

         else if($p_edit != $master)
         {
            $message = 'error: wrong password ';
            $message_class = 'text text-danger'; 
         }
         else
         {
             $edit_query = " UPDATE category SET cat_title = ? WHERE cat_id = ? ";

             $stmt2 = mysqli_stmt_init($connection);

             if(!mysqli_stmt_prepare($stmt2,$edit_query) )
             {
               die('stmt2 prepare failed'.mysqli_error($connection));
             }
   
             // bind
             if(!mysqli_stmt_bind_param($stmt2,"si",$new_cat_title,$old_cat_id))
             {
               die("stmt2 bind failed".mysqli_error($connection));
             }
           
             // execute
             if(!mysqli_stmt_execute($stmt2))
             {
               die('stmt2 execute failed '.mysqli_error($connection));
             }
             else
             {
               mysqli_stmt_close($stmt2);
               header("Location: admin_categories.php");
             }   
   
         }
} 


if(isset($_POST['delete_category_submit']))
{
            
    // get cat id from javascript (hidden input value in form with id give_id)
    $delete = $_POST['cat_id_delete'];

    $p_del = mysqli_real_escape_string($connection,trim($_POST['p_del']));
    $p_c_del = mysqli_real_escape_string($connection,trim($_POST['p_c_del']));
      
    if($p_del != $p_c_del)
    {
        $message = 'error: passwords dont match';
        $message_class = 'text text-danger';    
    }
    else if($p_del != $master)
    {
       $message = 'error: wrong password ';
       $message_class = 'text text-danger'; 
    }
    else
    {
        $delete_query = "DELETE FROM category WHERE cat_id = ? ";
      
        $stmt3 = mysqli_stmt_init($connection);

        if(!mysqli_stmt_prepare($stmt3,$delete_query) )
        {
          die('stmt3 prepare failed'.mysqli_error($connection));
        }

        // bind
        if(!mysqli_stmt_bind_param($stmt3,"i",$delete))
        {
          die("stmt3 bind failed".mysqli_error($connection));
        }
        // execute
        
        if(!mysqli_stmt_execute($stmt3))
        {
          die('stmt3 execute failed '.mysqli_error($connection));
        }
        else
        {
          mysqli_stmt_close($stmt3);
          header("Location: admin_categories.php");
        }  
    }

}

?>

<div class="container-fluid" id="main_contain">
  
  <!-- Page Heading -->
  <div class="row">
    <?php require_once "admin_includes/sidebar.php"; ?>
      
    <div class="col-sm-12 col-md-7">
      <h1 class="page-header" id="login_head">  Admin Categories  </h1>
                                                  

        <!-- show all categories table  -->
        <div class="col-xs-8">
          <h4 class="text-center"> All Categories</h4>
            <table class="table table-bordered table-hover">
              <thead>
                <th>Cat id</th>
                <th>Cat Title</th>
                <th>Edit</th>
                <th>Delete</th>
              </thead>
              
              <tbody>
                    <?php 
                    $query = "SELECT * FROM category";
                    $result = mysqli_query($connection,$query);
                    if(!$result)
                    {
                      die('result query failed'.mysqli_error($connection));
                    }
                  
                    while($row = mysqli_fetch_assoc($result))
                    {
                      $cat_id = $row['cat_id'];
                      $cat_title = $row['cat_title'];
                      ?>
                      <tr>
                          <td><?php echo $cat_id ?> </td>

                          <td><?php echo $cat_title ?> </td>
                      
                          <!-- open bootstrap modal to edit -->
                          <td> 
                            <button type="button" class="text text-primary edit_button" value="<?php echo $cat_title ?>" id="edit_button" rel="<?php echo $cat_id ?>" href="javascript:(void(0)"> Edit </button> 
                          </td>
                          
                          <!-- open bootstrap modal to delete -->
                          <td> 
                            <button type="button" class="text text-danger delete_button" value="<?php echo $cat_title ?>" id="delete_button" rel="<?php echo $cat_id ?>" href="javascript:(void(0)"> Delete </button> 
                          </td>
                      
                      </tr>

                      <?php 
                    } ?>
                </tbody>
            </table>
                
            <button type="button" class="btn-lg btn-success"  data-toggle="modal" data-target="#AddCatModal"> Add category </button> 
        </div>

<!-- Add Modal -->
<div class="modal fade" id="AddCatModal" tabindex="-1" role="dialog" aria-labelledby="EditCatModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="AddCatModalLabel">Add Category </h5>
        <small> please enter category name, master password and confirm </small> 
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" method="post" >
        
        <label for="">Category name</label> <br>
            <input type="text" id="cat_name" class="form-control" name="category_title"> <br> <br>
            
        <label for="">Password</label> <br>
     
        <input type="password" class="form-control" name="p_add"> <br>  <br>
        
        <label for="">Confirm Password</label> <br>

        <input type="password" class="form-control" name="p_c_add"> <br> <br>
                     
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
        <button type="submit" name="submit_add" class="btn btn-success">Create New Category</button>
      </div>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="EditCatModal" tabindex="-1" role="dialog" aria-labelledby="EditCatModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="EditCatModalLabel">Are you sure that you really want to update the category <span id="modal_edit_title"> </span> ?</h5>
        <small> if so please enter new category name, master password and confirm </small> 
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" method="post" >
        
        <label for="">New Category name</label> <br>
            <input type="text" id="old_cat_name" class="form-control" name="new_title" value=" "> <br> <br>
            
            <input type="hidden" id="old_cat_id" class="form-control" name="old_id" value=" ">

        <label for="">Password</label> <br>
     
        <input type="password" class="form-control" name="p_edit"> <br>  <br>
        
        <label for="">Confirm Password</label> <br>

        <input type="password" class="form-control" name="p_c_edit"> <br> <br>
        
        <input id="give_edit_id" type="hidden" value="" name="cat_id_edit"> 
                     
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
        <button type="submit" name="submit_edit" class="btn btn-warning">Update</button>
      </div>
    </div>
  </div>
</div>



<!-- Delete Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Are you sure that you really want to delete the category <span id="modal_delete_title"> </span> ?</h5>
        <small> if so please enter master password and confirm </small> 
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" method="post" >
        
        <label for="">Password</label>
     
        <input type="password" name="p_del"> <br>  <br>
        
        <label for="">Confirm Password</label>

        <input type="password" name="p_c_del"> <br> <br>
        
        <input id="give_del_id" type="hidden" value="" name="cat_id_delete"> 
                     
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
        <button type="submit" name="delete_category_submit" class="btn btn-danger">Delete</button>
      </div>
    </div>
  </div>
</div>

  <?php if(!empty($message))
        { ?> 
          <div class="<?php echo $message_class ?> ">
            <?php echo $message; ?> 
          </div>  <?php 
        } ?>           
            
  </div>
                
</div>

<?php include_once "admin_includes/footer.php" ?>
