<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin_Controller extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        //check if the user is logged in and is an admin
        if(!is_admin()){
            redirect("auth/login?location=".urlencode($_SERVER['REQUEST_URI']),"refresh");
        }
        
        //$this->output->enable_profiler(TRUE);
    }
}
?>