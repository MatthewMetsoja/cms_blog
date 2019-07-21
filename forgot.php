<?php 
require_once "includes/header.php";
require_once 'vendor/autoload.php'; 
require_once "includes/navigation.php"; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$msg = " ";
$msg_class = " ";

if(!isset($_GET['forgot']))
{
    redirect('index.php');  
}
else
{
    if(isset($_POST['forgot_submit']))
    {

        $email = $_POST['email'];  
     
        $length = 50;
        // create some random number for password reset
        $token = bin2hex(openssl_random_pseudo_bytes($length));

        if(empty($email))
        {
            $msg = 'you forgot to enter email';
            $msg_class = 'text text-danger';
        } 
        else
        {

            if(email_exists($email))
            {
     
                    $query = "UPDATE users SET token = ? WHERE user_email = ? ";

                    $stmt = mysqli_stmt_init($connection);

                    if(!mysqli_stmt_prepare($stmt,$query))
                    {
                        die('prepare stmt failed'.mysqli_error($connection));    
                    }
                    
                    if(!mysqli_stmt_bind_param($stmt,"ss",$token,$email))
                    {
                        die('bind stmt failed'.mysqli_error($connection));    
                    }

                    if(!mysqli_stmt_execute($stmt))
                    {
                        die('execute stmt failed'.mysqli_error($connection));    
                    }
                    
                    mysqli_stmt_close($stmt);

                    // CONFIGURE PHP MAILER
                    $mail = new PHPMailer();    // Passing `true` enables exceptions
                    try 
                    {
                        //Server settings
                        $mail->isSMTP();                                      // Set mailer to use SMTP
                        $mail->Host = 'smtp.mailtrap.io' ;  // Specify main and backup SMTP servers
                        $mail->SMTPAuth = true;                               // Enable SMTP authentication
                        $mail->Username = getenv(mailer_username);                 // SMTP username
                        $mail->Password = getenv(mailer_password);                           // SMTP password
                        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
                        $mail->Port = 2525 ;                                    // TCP port to connect to

                        $mail->CharSet = 'UTF-8' ; 

                        //Recipients
                        $mail->setFrom('matthewmetsoja1@icloud.com','Matty');  // Name is optional
                        $mail->addAddress($email); // Add a recipient
                    

                        //Content
                        $mail->isHTML(true);                                  // Set email format to HTML
                        $mail->Subject = 'Password reset';
                        $mail->Body    = '<p> Please click the link to reset your password 
                        <a href="http://localhost:8888/CMS/reset_pw.php?email='.$email.'&token='.$token. ' " >  CLICK HERE TO RESET PASSWORD </a>
                        </p> ';
                        // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                        $mail->send();
                    
                        $msg = ' Thank you please check your email for password reset link ';
                        $msg_class = "text text-success ";
                    }
                    catch (Exception $e) 
                    {
                        $msg = 'Message could not be sent. Mailer Error:'.$mail->ErrorInfo;
                        $msg_class = "text text-danger";
                    }


            }
            else
            {
                $msg = "email not in system ";
                $msg_class = "text text-danger ";
            }

        }

    }

}

?>

<!-- Page Content -->
<div class="container" id="main_contain">

    <div class="form-gap"></div>
   
    <div class="container">
        <div class="row">
            <div class="col-md-3 hidden-sm"> </div>
            
            <div id="main_div" class="col-xs-12 col-md-6">
                <div class="">
                    <div class="">
                        <div class="text-center">

                                <h3><i class="fa fa-lock fa-4x"></i></h3>
                                <h2 id="login_head" class="text-center">Forgotton Password?</h2>
                                <p> <b>  You can reset your password here.  </b> </p>
                                <div class="panel-body">

                                    <form id="register-form" role="form" autocomplete="off" class="form" method="post">

                                        <div class="form-group">
                                            <label for="">Please Enter Your Email</label> <br>        
                                            <div class="input-group"> 
                                                <span class="input-group-addon"><i class="glyphicon glyphicon-envelope color-blue"></i></span> 
                                                <input  id="email" name="email" placeholder="email address" class="form-control"  type="email">
                                           </div> 
                                        </div>
                                        
                                        <div class="form-group">
                                            <input name="forgot_submit" class="btn btn-lg btn-primary btn-block" value="Reset Password" type="submit">
                                        </div>

                                        <input type="hidden" class="hide" name="token" id="token" value="">
                                    </form>

                                     <?php 
                                    if(!empty($msg))
                                    { ?>
                                        <div class="<?php echo $msg_class?>" > <?php echo $msg ?> </div> <?php 
                                    } ?> 

                                </div><!-- Body-->

                        </div>
                    </div>
                </div>
           </div>    <!--main div -->
           
           <div class="col-md-3 hidden-sm"> </div>
        </div>
    </div>

<hr>

<?php include "includes/footer.php";?>

</div> <!-- /.container -->
