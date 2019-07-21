<?php 
require_once "admin_includes/header.php";
require_once '../vendor/autoload.php'; 
require_once "functions.php"; 
require_once "admin_includes/navigation.php";
 
// get session values for queries to show all data in dashboard for user
$username = $_SESSION['username'];
$user_id = $_SESSION['id'];
 ?>     

<div id="page-wrapper">
    <div class="container-fluid" id="main_contain">
    
        <?php require_once "admin_includes/sidebar.php" ?>
       
            <div class="row">
                <div class="col-sm-12 col-md-7">
                        <h1 class="page-header" id="login_head" >  Admin <i class="fa fa-cog"></i>  </h1>

                        
                
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-xs-3">
                                                    <i class="fa fa-file-text fa-5x"></i>
                                                </div>
                                               
                                                <div class="col-xs-9 text-right">
                                                    <?php          
                                                    function users_widgets2($table,$row,$data,$row2,$data2,$query_name,$stmt_name,$task)
                                                    {
                                    
                                                        global $connection;

                                                        $query_name = "SELECT * FROM $table WHERE $row = ? AND $row2 = ? ";

                                                        $stmt_name = mysqli_stmt_init($connection);

                                                        if(!mysqli_stmt_prepare($stmt_name,$query_name))
                                                        {
                                                            die('stmt'."$task".'prepare failed'.mysqli_error($connection));    
                                                        }

                                                        if(!mysqli_stmt_bind_param($stmt_name,"ss",$data,$data2))
                                                        {
                                                            die('stmt'."$task".'bind failed'.mysqli_error($connection));    
                                                        }
                                                    
                                                        if(!mysqli_stmt_execute($stmt_name))
                                                        {
                                                            die('stmt'."$task".'execute failed'.mysqli_error($connection));    
                                                        }  
                                                            
                                                        $result = mysqli_stmt_get_result($stmt_name);
                                                        if(!$result)
                                                        {
                                                            die('stmt'."$task".'execute failed'.mysqli_error($connection));    
                                                        }     
                                                            
                                                        return mysqli_num_rows($result);
                                    
                                                    } 
                                                    ?>
                                                        <div class='huge'>
                                                            <?php echo $active_posts = users_widgets2(posts,post_status,published,post_author,$username,postquery,stmt1,1); ?>
                                                        </div>
                                                   
                                                        <div>Posts/published</div>
                                                </div>
                                            </div>
                                        </div>
                                       
                                        <a href="view_all_posts_user.php">
                                            <div class="panel-footer">
                                                <span class="pull-left">View Details</span>
                                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                                <div class="clearfix"></div>
                                            </div>
                                        </a>
                                    </div>
                                </div>



                                <div class="col-md-6">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-xs-3">
                                                    <i class="fa fa-file-text fa-5x"></i>
                                                </div>
                                                <div class="col-xs-9 text-right">
                                                    <div class='huge'>
                                                         <?php echo $unpublished_posts = users_widgets2(posts,post_status,unpublished,post_author,$username,postquery1,stmt2,2) ?>
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



                                <div class="col-md-6">
                                    <div class="panel panel-green">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-xs-3">
                                                    <i class="fa fa-comments fa-5x"></i>
                                                </div>
                                               
                                                <div class="col-xs-9 text-right">

                                                        <div class='huge'> 
                                                            <?php
                                                            $commented_query = "SELECT * FROM posts WHERE post_comment_count > 0 AND post_author = '$username' AND post_status = 'published' ";
                                                            $commented_result = mysqli_query($connection,$commented_query);
                                                            if(!$commented_result)
                                                            {
                                                                die('commened result failed'.mysqli_error($connection));
                                                            }
                                                            
                                                            echo $posts_commented = mysqli_num_rows($commented_result);    ?>
                                                        </div> 
                                                        
                                                        <div> Posts that have been Commented on</div>
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



                                <div class="col-md-6">
                                    <div class="panel panel-red">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-xs-3">
                                                    <i class="fa fa-list fa-5x"></i>
                                                </div>
                                               
                                                <div class="col-xs-9 text-right">
                            
                                                    <div class='huge'> 
                                                        <?php 
                                                        $liked_posts_query = "SELECT * FROM posts WHERE post_author = '$username' AND post_been_liked = 1 ";
                                                        $liked_post_result = mysqli_query($connection,$liked_posts_query);
                                                        if(!$liked_post_result)
                                                        {
                                                            die('liked post result failed'.mysqli_error($connection));
                                                        }
                                                        
                                                        echo $liked_posts = mysqli_num_rows($liked_post_result);
                                                        ?> 
                                                    </div>
                                                   
                                                    <div>Posts liked by others</div>
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
                                            <!-- /.row -->


             <?php include_once "admin_includes/footer.php" ?>

            
    <script>
            
            
           $(document).ready(function(){

            var ctx = document.getElementById("myChart").getContext('2d');
            
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ["Posts/published", "Posts/un-published", " Posts others/Commented on ", "Posts ohters/liked" ],
                    datasets: [{
                        label: 'stats',
                        data: [ <?php echo $active_posts; ?> , <?php echo $unpublished_posts; ?>, <?php echo $posts_commented; ?> , <?php echo $liked_posts; ?> ],
                        backgroundColor: [
                            'rgba(66,134,244,1.0)',
                            'rgba(66,134,244,0.5)',
                            'rgba(92,184,92, 1.0)',
                            // 'rgba(92, 184, 92, 0.5)',
                            // 'rgba(240, 173, 78, 1.0)',
                            // 'rgba(240, 173, 78, 0.5)',
                            'rgba(217, 83, 79, 1.0)'
                        ],
                        borderColor: [
                            'rgba(66, 134, 244, 1.0)',
                            'rgba(66, 134, 244, 0.5)',
                            'rgba(92, 184, 92, 1.0)',
                            //'rgba(92, 184, 92, 0.5)',
                        // 'rgba(240, 173, 78, 1.0)',
                            //'rgba(240, 173, 78, 0.5)',
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

            var channel_1 = pusher.subscribe('notifications');
            
            channel_1.bind('new_user', function(new_users_name) {

                var message = new_users_name.message;

                toastr.success(`new user registered by the name of: ${message}`)

            });
                        
        });

 </script>
