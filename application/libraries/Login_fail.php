<?php
class Login_fail extends Exception{
    public function __construct()
     {
     	$code = 103;
        $message = "Username or password was wrong";
        parent::__construct();
     }
}
?>