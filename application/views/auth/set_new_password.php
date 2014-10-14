<!--
/**
 * Created by PhpStorm.
 * User: Niesmo
 * Date: 10/7/14
 * Time: 1:55 PM
 */
-->
<div class="container">
    <div class="row">
        <br />
        <div class="col-md-6 col-md-offset-3 well">
            <?php
            echo heading("Set your new password", 3, "class='text-center'");
            echo "<hr />";
            if(isset($passwordErrors)){
                foreach($passwordErrors as $error){
                    echo $error;
                }
            }
            echo form_open("auth/password/set_new_password","class='form'", array("user_id"=>$user_id));
            echo "<div class='input-group'><p>New password</p></div>";
            echo "<div class='input-group'>";
                echo "<span class='input-group-addon'><i class='fa fa-lock'></i></span>";
                $inputArr = array("class"=>"form-control col-md-5", "autofocus"=>"autofocus", "name"=>"new_password");
                echo form_password($inputArr);
            echo "</div>";
            echo "<div class='input-group'><p>Confirm new password</p></div>";
                echo "<div class='input-group'>";
                echo "<span class='input-group-addon'><i class='fa fa-lock'></i></span>";
                $inputArr = array("class"=>"form-control col-md-5", "name"=>"re_new_password");
                echo form_password($inputArr);
            echo "</div>";
            $attributes = array("class"=>"btn btn-primary btn-sm top-margin-sm", "value"=>"Reset Password");
            echo form_submit($attributes);
            echo form_close();
            ?>
        </div>
    </div>
</div>