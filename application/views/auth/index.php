<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6">
                    <?=heading('Login', 2);?>
                    <?php if(isset($loginErrors)){
                        $errorData['errors'] = $loginErrors;
                        $this->load->view("message/form_validation_error_panel.php", $errorData);
                    } 
                    ?>
                    <div class="row">
                        <?= br();?>
                        <div class="col-md-10 ">
                            <button class="btn btn-sm btn-social btn-google-plus" disabled><i class="fa fa-google-plus top-padding-sm"></i>Sign in with Google</button>
                            <button class="btn btn-sm btn-social btn-facebook" disabled><i class="fa fa-facebook top-padding-sm"></i>Sign in with Facebook</button>
                            <hr />
                            <?php
                            $this->load->view('auth/login.php', array("formAction"=>"authenticate"));
                            ?>
                        </div> <!-- col-md-10 END -->
                    </div> <!-- Inner row END (3rd row) -->
                </div> <!-- col-md-6 for login END -->
                <div class="col-md-6">
                    <?=heading('Sign Up', 2);?>
                    <?php if(isset($signupErrors)){?>
                        <div class="row">
                            <div class="col-md-10 error-box" >
                                <ul class="list-group">
                                <?php
                                foreach($signupErrors as $error){
                                    echo $error;
                                }?>
                                </ul>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="row">
                        <?= br();?>
                        <div class="col-md-10">
                            <?php
                            $this->load->view('auth/signup.php', array("formAction"=>"sign_up"));
                            ?>
                        </div> <!-- col-md-10 END -->
                    </div> <!-- Inner row END (3rd level) -->
                </div> <!-- col-md-6 for Sign up END -->
            </div> <!-- Inner Row END (2nd level) -->
        </div> <!-- Main col-md-12 END -->
    </div> <!-- Main Row END -->
</div> <!-- Main Container END -->
