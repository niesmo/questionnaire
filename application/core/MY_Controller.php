<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MY_Controller extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        
        //THIS IS FOR THE DEBUGGING TOOLS
        //$this->output->enable_profiler(TRUE);
    }
}
?>