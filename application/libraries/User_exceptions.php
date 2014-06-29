<?php
class Exception_user_exist extends Exception{
    public function __construct(){
        $code = 101;
        $meesage = "This user already exist in the database";
        parent::__construct();
    }
}

class Login_fail extends Exception{
    public function __construct()
     {
     	$code = 103;
        $message = "Username or password was wrong";
        parent::__construct();
     }
}

class Exception_user_insertion_failed extends Exception{
    public function __construct(){
        $code = 101;
        $meesage = "The insertion for the user was not successful";
        parent::__construct();
    }
}
?>