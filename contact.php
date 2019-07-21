<?php  
require_once "includes/header.php"; 
require_once 'vendor/autoload.php'; 
require_once "includes/navigation.php"; 

 $error_message = 
 [
    "subject" => "",
    "f_name" => "",
    "l_name" => "",
    "email" => "",
    "message" =>"",
    "final_fail" =>"",
    "final_pass" => ""
 ];

if(isset($_POST['send']))
{
    $to  = "matthewmetsoja1@icloud.com";
    $subject = R_E_S($_POST['subject']);
    $f_name = R_E_S($_POST['first_name']);
    $l_name = R_E_S($_POST['last_name']);
    $email = filter_var($_POST['email'],FILTER_SANITIZE_EMAIL);
    $message = wordwrap(R_E_S($_POST['message']), 70);

    if(empty($subject))
    {
        $error_message['subject'] = "Please enter a subject for the message.";
    }

    if(empty($f_name))
    {
        $error_message['f_name'] = "Please enter your first name!";
    }

    if(empty($l_name))
    {
        $error_message['l_name'] = "Please enter your last name!";
    }

    if(empty($email))
    {
        $error_message['email'] = "Please enter your email address.";
    }

    if(!filter_var($email,FILTER_VALIDATE_EMAIL))
    {
        $error_message['email'] = "Please enter a valid email!";
    }

    if(empty($message))
    {
        $error_message['message'] = "Message can not be empty!";
    }

    if(str_word_count($message) < 7 )
    {
        $error_message['message'] = "Message must at least 7 words long.";
    }

    else if( empty($error_message['subject']) && empty($error_message['f_name']) && empty($error_message['l_name']) && 
            empty($error_message['email']) && empty($error_message['message']) )
    {

            $header = "From: " .$email. "($f_name $l_name)";
    

            // send email
            if(!mail($to,$subject,$message,$header))
            {
                $error_message['final_fail'] = "Message failed to send. please try again, contact admin at admim@admin.admin if the
                                problem persists";
            }
            else
            {
                $error_message['final_pass'] = "Message sent successfully. We will reply to you shortly.";
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
                    <div class="form-wrap">
                        <h1 class="login_head">Contact Us</h1>

                        <form role="form" action="" method="post" autocomplete="off">
                        

                            <div class="form-group">
                                    <label for="subject" id="login_head">Subject:</label>
                                    <div class="text-danger"> <?= $error_message['subject']; ?></div>
                                    <input type="text" name="subject" id="subject" class="form-control" placeholder="Please tell us what your message is about here"
                                    value="<?php echo isset($subject) ? trim($subject) : '' ?>">
                            </div>


                            <div class="form-group">
                                <label for="username" id="login_head">First Name:</label>
                                <div class="text-danger"> <?= $error_message['f_name']; ?></div>
                                <input type="text" name="first_name" id="first_name" class="form-control" placeholder="Please write your first name here"
                                value="<?php echo isset($f_name) ? trim($f_name) : '' ?>">
                            </div>


                            <div class="form-group">
                                <label for="username" id="login_head">Last Name:</label>
                                <div class="text-danger"> <?= $error_message['l_name']; ?></div>
                                <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Please write your last name here"
                                value="<?php echo isset($l_name) ? trim($l_name) : '' ?>">
                            </div>

                            <div class="form-group">
                                <label for="email" id="login_head">Email:</label>
                                <div class="text-danger"> <?= $error_message['email']; ?></div>
                                <input type="email" name="email" id="email" class="form-control" placeholder="somebody@example.com" autocomplete="on" value="<?php echo isset($email) ? trim($email) : '' ?>" >
                            </div>
                      
                            
                            <div class="form-group">
                                <label for="message" id="login_head"> Message: </label>
                                <div class="text-danger"> <?= $error_message['message'] ?></div>
                                <textarea name="message" rows="10"  class="form-control" placeholder="Please write your message to us here"><?php echo isset($message) ? $message : '' ?></textarea>
                            </div>
                    
                            <input type="submit" name="send" id="btn-login" class="btn btn-success btn-lg btn-block contact_button" value="Send">
                        
                        </form>
                     <h2 class="text-success text-center"> <?php echo $error_message['final_pass']; ?> </h2>
                     <h2 class="text-danger text-center"> <?php echo $error_message['final_fail']; ?> </h2>
                    </div>
                </div> <!-- /.col-xs-12 -->

                <div class="col-md-3 hidden-sm"> </div>
            </div> <!-- /.row -->
        </div> <!-- /.container -->
    </section>

    <hr>

<?php require_once "includes/footer.php";?>
