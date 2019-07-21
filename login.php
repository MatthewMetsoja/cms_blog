<?php 
require_once "includes/header.php"; 
require_once "includes/navigation.php";

	$msg = 
	[
	"username" => " ",
	"password" => " ",
	"failed_username" => " ",
	"failed_password" => " ",
	"failed" => " "
	];

	$msg_class = 
	[
	"username" => " ",
	"password" => " ",
	"failed_username" => " ",
	"failed_password" => " ",
	"failed" => " "
	];



	if(isset($_POST['submit']))
	{

		$email = $_POST['email'];
		$password = $_POST['password'];

		if(empty($email))
		{
			// failed.... submitted empty email ... output message
			$msg['email'] = 'You forgot to enter your email';
			$msg_class['email'] = 'text text-danger';
		}
		
		if(empty($password))
		{
			// failed.... submitted empty password ... output message
			$msg['password'] = 'You forgot to enter your password';
			$msg_class['password'] = 'text text-danger';
		}
		elseif(!empty($email) && !empty($password))
		{ 	
			// passed stage 1 check username is in db
			$query = "SELECT * FROM users WHERE user_email = '$email' ";
			$result = mysqli_query($connection,$query);
			if(!$result)
			{
				die('result query failed'.mysqli_error($connection));
			}
			
			$email_exists = mysqli_num_rows($result);

			if($email_exists !== 0)
			{

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

				if(password_verify($password,$db_password))
				{
					// login passed   
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
						// and redirect users to thiers
						header("Location: admin/user_index.php");
					}

				}
				else
				{
					// failed.... password wrong ... output message
					$msg['failed_password'] = 'incorrect password';
					$msg_class['failed_password'] = 'text text-warning';
					$msg['failed'] = 'login failed';
					$msg_class['failed'] = 'alert alert-danger';
				}

			}

		}
		else
		{
			// failed.... email doesnt exist ... output message
			$msg['failed_email'] = 'email does not exist';
			$msg_class['failed_email'] = 'text text-warning';
			$msg['failed'] = 'login failed';
			$msg_class['failed'] = 'alert alert-danger';
		}

	}
?>

<!-- Page Content -->
<div class="container" id="main_contain">

	<div class="form-gap"></div>
		<div id="top_login" class="container">
			<div class="row">
				<div class="col-md-3 hidden-sm"> </div>
	
				<div id="main_div" class="col-xs-12 col-md-6">

					<div class="panel-body">
						<div class="text-center">
							<h3><i class="fa fa-user fa-4x"></i></h3>
							<h2 id="login_head" class="text-center">Login</h2>
							<div class="panel-body">
								<form id="login-form" role="form" autocomplete="off" class="form" method="post" action="">
									
									<div class="form-group">
										<label id="login_head"> email</label>	
										<div class="input-group">
											<span class="input-group-addon"><i class="glyphicon glyphicon-user color-blue"></i></span>
											<!-- output message if user submits form and leaves username blank -->
											<?php 
											if($msg['email'] !== '')
											{ ?> 
												<div class="<?php echo $msg_class['email'];?>"> 
													<?php echo $msg['email']; ?>  
												</div> <?php 
											} 
											if($msg['failed_email'] !== '')
											{ ?>
												<div class="<?php echo $msg_class['failed_email'];?>">
													<?php echo $msg['failed_email']; ?>  
												</div> <?php 
											} ?>					
									
											<input name="email" type="text" class="form-control" name="email" >
										</div>
									</div>

									<div class="form-group">
										<label id="login_head">password</label>	
										<div class="input-group">
											<span class="input-group-addon"><i class="glyphicon glyphicon-lock color-blue"></i></span>
											<!-- output message if user submits form and leaves password blank -->
											<?php
											if($msg['password'] !== '')
											{ ?>
												<div class="<?php echo $msg_class['password'];?>">
													<?php echo $msg['password']; ?>  
												</div> <?php 
											}
											if($msg['failed_password'] !== '')
											{ ?>
												<div class="<?php echo $msg_class['failed_password']; ?>">
													<?php echo $msg['failed_password']; ?>  
												</div> <?php 
											} ?>			
											
											<input name="password" type="password" class="form-control" name="password" >
										</div>
									</div>

									<div class="form-group">
										<button name="submit" class="btn btn-lg btn-primary btn-block" type="submit"> Login </button> <br>
										<div> 
											<a href="forgot.php?forgot=<?php echo uniqid(); ?>" id="login_head" class="forgot_btn"> 
												Forgotten password ?     
											</a>  
										</div>
									</div>

									<?php 
									if($msg['failed'] !== '')
									{ ?> 
										<div class="<?php echo $msg_class['failed']; ?>">
											<?php echo $msg['failed'] ?>  
										</div> <?php 
									} ?>

								</form>

							</div>
						</div>
					</div>
				</div> 

				<div class="col-md-3 hidden-sm"> </div>
			</div>
		</div>

	<hr>

</div> <!-- /.container -->
      
<?php   require_once "includes/footer.php";  ?>