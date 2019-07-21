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

?>        

<div class="container-fluid" id="main_contain">
   
    <!-- Page Heading -->
    <div class="row">
    <?php require_once "admin_includes/sidebar.php" ?>
        
        <div class="col-sm-12 col-md-7">
            <h1 class="page-header" id="login_head" >   Admin <i class="fa fa-cog"></i>   </h1>
    
                <div class="row">
                        <div class=" col-md-6">
                           
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <i class="fa fa-file-text fa-5x"></i>
                                        </div>
                                    
                                        <div class="col-xs-9 text-right">
                                        
                                                <?php  $active_posts = widget_numbers(posts,post_status,published); ?>
                                            
                                                <div class='huge'>
                                                    <?php echo $active_posts ?>
                                                </div>

                                                <div>Posts/published</div>
                                        </div>

                                    </div>
                                </div>
                                        <a href="admin_posts.php">
                                            <div class="panel-footer">
                                                <span class="pull-left">View Details</span>
                                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                                <div class="clearfix"></div>
                                            </div>
                                        </a>
                            </div>

                        </div>



                        <div class=" col-md-6">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <i class="fa fa-file-text fa-5x"></i>
                                        </div>
                                        <div class="col-xs-9 text-right">
                                            <?php $unpublished_posts = widget_numbers(posts,post_status,unpublished); ?>
                                    
                                            <div class='huge'> 
                                                <?php echo $unpublished_posts ?> 
                                            </div>
                                            
                                            <div>Posts/un-published</div>
                                        </div>

                                    </div>
                                </div>

                                <a href="admin_posts.php">
                                    <div class="panel-footer">
                                        <span class="pull-left">View Details</span>
                                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                        <div class="clearfix"></div>
                                    </div>
                                </a>
                            </div>
                        </div>



                        <div class=" col-md-6">
                            <div class="panel panel-green">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <i class="fa fa-comments fa-5x"></i>
                                        </div>
                                        <div class="col-xs-9 text-right">
                                           <?php $approved_comments = widget_numbers(comments,comment_status,approved); ?>
                                        
                                           <div class='huge'> 
                                               <?php echo $approved_comments ?> 
                                            </div>
                                        
                                            <div>Comments/approved</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <a href="admin_comments.php">
                                    <div class="panel-footer">
                                        <span class="pull-left">View Details</span>
                                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                        <div class="clearfix"></div>
                                    </div>
                                </a>
                            </div>
                        </div>



                        <div class=" col-md-6">
                            <div class="panel panel-green">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <i class="fa fa-comments fa-5x"></i>
                                        </div>
                                        <div class="col-xs-9 text-right">
                                            <?php  $denied_comments = widget_numbers(comments,comment_status,denied); ?>
                                        
                                            <div class='huge'>
                                                 <?php echo $denied_comments ?> 
                                            </div>
                                       
                                            <div>Comments/denied</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <a href="admin_comments.php">
                                    <div class="panel-footer">
                                        <span class="pull-left">View Details</span>
                                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                        <div class="clearfix"></div>
                                    </div>
                                </a>
                            </div>
                        </div>



                        <div class=" col-md-6">
                            <div class="panel panel-yellow">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <i class="fa fa-user fa-5x"></i>
                                        </div>
                                        <div class="col-xs-9 text-right">
                                            <?php $users_user = widget_numbers(users,user_role,user); ?>
                                            
                                            <div class='huge'>
                                                <?php echo $users_user ?>
                                             </div>
                                               
                                             <div> Users/user</div>
                                        </div>
                                    </div>
                                </div>

                                <a href="admin_users.php">
                                    <div class="panel-footer">
                                        <span class="pull-left">View Details</span>
                                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                        <div class="clearfix"></div>
                                    </div>
                                </a>
                            </div>
                        </div>



                        <div class=" col-md-6">
                            <div class="panel panel-yellow">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <i class="fa fa-user fa-5x"></i>
                                        </div>
                                        <div class="col-xs-9 text-right">
                                            <?php $users_admin = widget_numbers(users,user_role,admin); ?>
                                            <div class='huge'> 
                                                <?php echo $users_admin ?>
                                            </div>
                                           
                                            <div> Users/admin </div>
                                        </div>
                                    </div>
                                </div>

                                <a href="admin_users.php">
                                    <div class="panel-footer">
                                        <span class="pull-left">View Details</span>
                                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                        <div class="clearfix"></div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="panel panel-red">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <i class="fa fa-list fa-5x"></i>
                                        </div>
                                        <div class="col-xs-9 text-right">
                                            <?php $all_categories = widget_numbers1(category); ?>
                                            <div class='huge'>
                                                <?php echo $all_categories ?>
                                            </div>
                                            
                                            <div>Categories</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <a href="admin_categories.php">
                                    <div class="panel-footer">
                                        <span class="pull-left">View Details</span>
                                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                        <div class="clearfix"></div>
                                    </div>
                                </a>
                            </div>
                        </div>



        </div>

            <canvas id="myChart"></canvas>

    </div>
</div>
             

<?php include_once "admin_includes/footer.php" ?>

            
<script>
            
            
$(document).ready(function(){

    var ctx = document.getElementById("myChart").getContext('2d');
                        var myChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: ["Posts/published", "Posts/un-published", "Comments/approved", "Comments/denied", "Users/user", "Users/admin",  "Categories" ],
                                datasets: [{
                                    label: 'stats',
                                    data: [ <?php echo $active_posts ?> , <?php echo $unpublished_posts ?>, <?php echo $approved_comments ?> , <?php echo $denied_comments ?> ,  <?php echo $users_user ?> , <?php echo $users_admin ?>, <?php echo $all_categories ?>],
                                    backgroundColor: [
                                        'rgba(66,134,244,1.0)',
                                        'rgba(66,134,244,0.5)',
                                        'rgba(92,184,92, 1.0)',
                                        'rgba(92, 184, 92, 0.5)',
                                        'rgba(240, 173, 78, 1.0)',
                                        'rgba(240, 173, 78, 0.5)',
                                        'rgba(217, 83, 79, 1.0)'
                                    ],
                                    borderColor: [
                                        'rgba(66, 134, 244, 1.0)',
                                        'rgba(66, 134, 244, 0.5)',
                                        'rgba(92, 184, 92, 1.0)',
                                        'rgba(92, 184, 92, 0.5)',
                                        'rgba(240, 173, 78, 1.0)',
                                        'rgba(240, 173, 78, 0.5)',
                                        'rgba(217, 83, 79, 1.0)'
                                    ],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero:true
                                        }
                                    }]
                                }
                            }
                        });

            
            
            
            
            
            var pusher = new Pusher('1ee6ea8af11685d8d72b', {
            cluster: 'eu',
            forceTLS: true,
            
            });

                //Enable pusher logging - don't include this in production
            //   Pusher.logToConsole = true;

            var channel_1 = pusher.subscribe('notifications');
            
            channel_1.bind('new_user', function(new_users_name) {

                var message = new_users_name.message;

                toastr.success(`new user registered by the name of: ${message}`)


                 
            });
                        


});

            
</script>
