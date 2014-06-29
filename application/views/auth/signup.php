<?php
$baseUrl= $this->config->item('base_url');
$attributes = array("class" => "form", "autocomplete" , "off");
echo form_open($baseUrl."/index.php/auth/login/".$formAction, $attributes); 
?>
<div class="row">
    <div class="col-md-6">
        <div class="input-group">
			<span class="input-group-addon"><i class="fa fa-user"></i></span>
            <?php
            $attributes = array("class"=>"form-control", "name"=>"firstName", "placeholder"=>"First Name", "autofocus"=>"autofocus", "value"=>set_value("firstName"), "required"=>"required" );
            echo form_input($attributes);
            ?>
		</div>
    </div>
    <div class="col-md-6">
        <div class="input-group">
			<span class="input-group-addon"><i class="fa fa-user"></i></span>
			<?php
            $attributes = array("class"=>"form-control", "name"=>"lastName", "placeholder"=>"Last Name", "value"=>set_value("lastName"), "required"=>"required");
            echo form_input($attributes);
            ?>
		</div>
    </div>
</div>
<span class="help-block"></span>
<div class="input-group">
	<span class="input-group-addon"><i class="fa fa-user"></i></span>
    <?php
    $attributes = array("class"=>"form-control", "name"=>"email", "placeholder"=>"Email Address", "value"=>set_value("email"), "required"=>"required");
    echo form_input($attributes);
    ?>
</div>
<span class="help-block"></span>
<div class="input-group">
	<span class="input-group-addon"><i class="fa fa-lock"></i></span>
    <?php
    $attributes = array("class"=>"form-control", "name"=>"password", "placeholder"=>"Password", "required"=>"required");
    echo form_password($attributes);
    ?>
</div>
<span class="help-block"></span>
<div class="input-group">
	<span class="input-group-addon"><i class="fa fa-lock"></i></span>   
	<?php
    $attributes = array("class"=>"form-control", "name"=>"re-password", "placeholder"=>"Re-enter Password", "required"=>"required");
    echo form_password($attributes);
    ?>
</div>
<span class="help-block" id="signin-error-placeholder"></span>
<?php
$attributes = array("class"=>"btn btn-success", "value"=>"Sign Un");
echo form_submit($attributes);
?>
<?php echo form_close();?>