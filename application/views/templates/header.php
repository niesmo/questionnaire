<?php
$projectName = $this->config->item('project_name');
$baseUrl= $this->config->item('base_url');
$controllerName = $this->uri->segment(1);
$methodName = $this->uri->segment(2);
if(!isset($projectName) || $projectName == "") $projectName = "iUSuR";
?>
<div class="navbar navbar-fixed-top navbar-default PMS-5473" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?php echo $baseUrl; ?>"> <?php echo $projectName; ?> </a>
		</div>
		<div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <?php
                if(is_logged_in()){
                    echo "<li>".anchor("user/dashboard","<i class='fa fa-user'></i> Dashboard") ."</li>";
                }
                ?>
                <!--<li><?php //echo anchor("questionnaire","Questionnaires");?></li>-->
            </ul>
            <div class="navbar-form navbar-right">
                <div class="form-group">
                    <?php
                    if(is_logged_in()){
                        $userInfo = get_logged_in_user_info();
                        $notificationCount = get_notification_count();
                        echo "<span class='user-name'>Welcome ".anchor("user/dashboard/",$userInfo['username'])." <span class='badge notification-badge'>".($notificationCount==0?"":$notificationCount)."</span></span>";
                        echo "<div id='notification-list' class='hidden'><p>Notifications</p><ul id='notifications'></ul></div>";
                        
                        echo anchor("auth/login/logout","Logout",array("class"=>"btn btn-danger"));
                    }
                    else{
                        if(strtolower($controllerName)== "auth" && strtolower($methodName) == "login"){
                            //do nothing
                        }
                        else{
                            echo form_open("auth/login/authenticate?location=".urlencode($_SERVER['REQUEST_URI']),"class='navbar-form no-margin'");

                                echo "<div class='form-group'>";
                                    $attributes = array("type"=>"email", "class"=>"form-control", "name"=>"email", "placeholder"=>"Email", "value"=>set_value('email'));
                                    echo form_input($attributes);
                                echo "</div>";

                                echo "<div class='form-group'>";
                                    $attributes = array("class"=>"form-control left-margin-sm", "name"=>"password", "placeholder"=>"Password");
                                    echo form_password($attributes);
                                echo "</div>";

                                echo form_submit("Login","Login","class='btn btn-primary left-margin-sm'");
                                echo anchor("auth/login?location=".urlencode($_SERVER['REQUEST_URI']),"Sign Up",array("class"=>"btn btn-success left-margin-sm"));
                            echo form_close();
                        }
                    }?>

                </div>
            </div>
		</div><!--/.navbar-collapse -->
	</div>
</div>	