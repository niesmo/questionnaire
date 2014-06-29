<?php
class Exception_user_insertion_failed extends Exception{
    public function __construct(){
        $code = 101;
        $meesage = "The insertion for the user was not successful";
        parent::__construct();
    }
}

class Exception_user_exist extends Exception{
    public function __construct(){
        $code = 101;
        $meesage = "This user already exist in the database";
        parent::__construct();
    }
}
?>