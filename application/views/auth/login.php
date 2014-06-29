<?php
$baseUrl= $this->config->item('base_url');
$attributes = array("class" => "form", "autocomplete"=>"off", "data-toggle"=>"validator");
echo form_open($baseUrl."/index.php/auth/login/".$formAction."?".$this->input->server('QUERY_STRING'), $attributes);
?>
<div class="input-group">
	<span class="input-group-addon"><i class="fa fa-user"></i></span>
	<?php
    $attributes = array("type"=>"email", "class"=>"form-control", "name"=>"email", "placeholder"=>"Email Address", "autofocus"=>"autofocus", "value"=>set_value('email'), "data-error"=>"Email address is required", "required"=>"required");
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
<span class="help-block" id="signin-error-placeholder"></span>
<?php
$attributes = array("class"=>"btn btn-primary", "value"=>"Login");
echo form_submit($attributes);
?>
<?php echo form_close();?>