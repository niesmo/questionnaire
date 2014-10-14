<!--
/**
 * Created by PhpStorm.
 * User: Niesmo
 * Date: 10/7/14
 * Time: 6:18 AM
 */
-->
<div class="container">
    <div class="row">
        <br />
        <div class="col-md-6 col-md-offset-3 well">
            <?php
            echo heading("Find Your Account", 3, "class='text-center'");
            if(isset($UI_message)){
                echo "<p>{$UI_message}</p>";
            }
            if(isset($url)){
                echo $url;
            }

            echo "<hr />";
            echo form_open("auth/password/search","class='form'");

            echo "<div class='input-group'><p>Email or full name</p></div>";

            echo "<div class='input-group'>";
                echo "<span class='input-group-addon'><i class='fa fa-envelope'></i></span>";
                $inputArr = array("class"=>"form-control col-md-5", "autofocus"=>"autofocus", "name"=>"account-info");

                echo form_input($inputArr);
            echo "</div>";
            $attributes = array("class"=>"btn btn-primary btn-sm top-margin-sm", "value"=>"Search");
            echo form_submit($attributes);
            echo form_close();
            ?>
        </div>
    </div>
</div>